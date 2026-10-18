<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Throwable;

class AktivitasNotifikasi extends Notification
{
    public function __construct(
        public string $judul,
        public string $pesan,
        public ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'url'   => $this->url,
        ];
    }

    /**
     * Kirim ke satu/banyak user. Pelaku (user yang sedang login) otomatis
     * dikecualikan. Dikirim langsung (tanpa queue) supaya tidak antre di
     * belakang job OCR, dan error-nya tidak menggagalkan proses utama.
     */
    public static function kirim($penerima, string $judul, string $pesan, ?string $url = null): void
    {
        try {
            $penerima = collect($penerima instanceof User ? [$penerima] : $penerima)
                ->filter()
                ->unique('id')
                ->reject(fn ($u) => $u->id === auth()->id());

            if ($penerima->isEmpty()) {
                return;
            }

            NotificationFacade::sendNow($penerima, new static($judul, $pesan, $url));
        } catch (Throwable $e) {
            report($e);
        }
    }

    /** Semua user yang punya izin tertentu (Super Admin ikut lewat Gate::before). */
    public static function penggunaDenganIzin(string $izin)
    {
        return User::all()->filter(fn ($u) => $u->can($izin));
    }
}