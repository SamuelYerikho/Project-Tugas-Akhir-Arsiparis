<?php

namespace App\Jobs;

use App\Models\Archive;
use App\Notifications\AktivitasNotifikasi;
use App\Services\OcrService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class OcrDocumentJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 600;   // harus > OCR_TIMEOUT; retry_after di config/queue.php harus > ini
    public int $backoff = 30;    // jeda sebelum percobaan ulang (detik)

    /**
     * $filePath dikirim bersama ID untuk mengecek bahwa file arsip
     * belum diganti pengguna saat job masih antre.
     */
    public function __construct(
        public int $archiveId,
        public string $filePath,
    ) {}

    public function handle(OcrService $ocr): void
    {
        $archive = Archive::find($this->archiveId);

        // Arsip sudah dihapus, atau filenya sudah diganti (ada job lain untuk file baru)
        if (! $archive || $archive->file_path !== $this->filePath) {
            return;
        }

        $archive->update(['ocr_status' => 'processing', 'ocr_error' => null]);

        $disk = Storage::disk('public');

        if (! $disk->exists($this->filePath)) {
            $this->markFailed('File fisik tidak ditemukan di storage.');
            $this->fail();
            return;
        }

        try {
            $res = $ocr->extract(
                $disk->path($this->filePath),
                $archive->original_filename ?: basename($this->filePath),
            );
        } catch (Throwable $e) {
            // 4xx dari FastAPI = file tidak bisa diproses, percuma diulang
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $this->markFailed($e->getMessage());
                $this->fail($e);
                return;
            }
            throw $e; // sementara: biarkan Laravel mencoba ulang
        }

        // Pastikan file belum diganti selama OCR berjalan
        $fresh = Archive::with('user')->find($this->archiveId);
        if (! $fresh || $fresh->file_path !== $this->filePath) {
            return;
        }

        $fresh->update([
            'ocr_text'    => $this->clean($res['text'] ?? ''),
            'ocr_pages'   => $res['n_pages'] ?? null,
            'ocr_seconds' => $res['seconds'] ?? null,
            'ocr_status'  => 'done',
            'ocr_error'   => null,
            'ocr_at'      => now(),
        ]);

        // Di dalam job tidak ada user yang login, jadi pemilik arsip pasti menerima ini.
        AktivitasNotifikasi::kirim(
            $fresh->user,
            'OCR selesai',
            "Dokumen \"{$fresh->title}\" selesai diproses dan teksnya sudah bisa dicari.",
            route('archives.show', $fresh, false),
        );
    }

    /**
     * Dipanggil Laravel setelah semua percobaan habis (atau fail() dipanggil).
     * Notifikasi gagal ditaruh di sini (bukan di markFailed) supaya tidak dobel,
     * karena markFailed() dan fail() sering dipanggil bersamaan.
     */
    public function failed(?Throwable $e): void
    {
        $this->markFailed($e?->getMessage() ?? 'Job OCR gagal.');

        $archive = Archive::with('user')->find($this->archiveId);

        if ($archive && $archive->file_path === $this->filePath) {
            AktivitasNotifikasi::kirim(
                $archive->user,
                'OCR gagal',
                "Dokumen \"{$archive->title}\" gagal diproses. Kamu bisa mencoba lagi dari halaman arsip.",
                route('archives.show', $archive, false),
            );
        }
    }

    private function markFailed(string $message): void
    {
        Archive::where('id', $this->archiveId)
            ->where('file_path', $this->filePath)
            ->update([
                'ocr_status' => 'failed',
                'ocr_error'  => mb_substr($message, 0, 1000),
            ]);
    }

    /**
     * Postgres menolak karakter NUL (\0) di kolom text, dan UTF-8 rusak
     * akan membuat query error. Bersihkan keduanya.
     */
    private function clean(string $text): string
    {
        $text = str_replace("\0", '', $text);

        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }
}