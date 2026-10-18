<?php

namespace App\Http\Controllers;

use App\Jobs\OcrDocumentJob;
use App\Models\Archive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ArchiveController extends Controller
{
    /**
     * Batas ukuran file dalam KB (10 MB). Tetap dipertahankan di sini
     * walau nginx/php.ini mengizinkan request yang lebih besar masuk,
     * supaya pesan error tetap ditangani rapi oleh Laravel, bukan
     * ditolak mentah oleh web server (413) atau PHP (silent drop).
     */
    private const MAX_FILE_SIZE_KB = 10240; // 10 MB

    /**
     * Display a listing of the archives.
     */
    public function index(Request $request): View
    {
        $query = Archive::query()->with('user')->latest();

        // JIKA TIDAK PUNYA PERMISSION MELIHAT SEMUA ARSIP:
        // (Super Admin otomatis lolos karena Gate::before, sedangkan staff biasa akan difilter)
        if ($request->user()->cannot('view all archives')) {
            $userId = $request->user()->id;

            $query->where(function ($q) use ($userId) {
                // 1. Arsip miliknya sendiri
                $q->where('user_id', $userId)
                    // 2. ATAU arsip yang access request-nya sudah approved
                    ->orWhereHas('accessRequests', function ($subQuery) use ($userId) {
                        $subQuery->where('user_id', $userId)
                            ->where('status', 'approved');
                    });
            });
        }

        // Pencarian juga mencakup isi dokumen hasil OCR.
        // ilike = tidak peduli huruf besar/kecil (khusus PostgreSQL).
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'ilike', "%{$keyword}%")
                    ->orWhere('category', 'ilike', "%{$keyword}%")
                    ->orWhere('description', 'ilike', "%{$keyword}%")
                    ->orWhere('ocr_text', 'ilike', "%{$keyword}%");
            });
        }

        $archives = $query->paginate(10)->withQueryString();

        return view('archives.index', compact('archives'));
    }

    /**
     * Show the form for creating a new archive.
     */
    public function create(): View
    {
        return view('archives.create');
    }

    /**
     * Store a newly uploaded archive.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title'       => ['required', 'string', 'max:255'],
                'category'    => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'file'        => ['required', 'file', 'max:' . self::MAX_FILE_SIZE_KB],
            ], [
                'file.max' => 'Ukuran berkas terlalu besar. Maksimal 10 MB.',
                'file.required' => 'Berkas wajib diunggah.',
            ]);

            $file = $request->file('file');
            $path = $file->store('archives', 'public');
            $ext  = strtolower($file->getClientOriginalExtension());
            $ocrSupported = in_array($ext, Archive::OCR_EXTENSIONS, true);

            $archive = Archive::create([
                'user_id'           => $request->user()->id,
                'title'             => $validated['title'],
                'category'          => $validated['category'] ?? null,
                'description'       => $validated['description'] ?? null,
                'file_path'         => $path,
                'original_filename' => $file->getClientOriginalName(),
                'file_type'         => $ext,
                'file_size'         => $file->getSize(),
                'status'            => 'active',
                'ocr_status'        => $ocrSupported ? 'pending' : 'skipped',
            ]);

            // Dispatch setelah file tersimpan dan record dibuat.
            if ($ocrSupported) {
                OcrDocumentJob::dispatch($archive->id, $path);
            }

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil diunggah.');
        } catch (ValidationException $e) {
            // Pesan validasi (termasuk "file terlalu besar") otomatis
            // dikirim balik ke form lewat session errors bawaan Laravel.
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withInput($request->except('file'))
                ->with('error', 'Gagal mengunggah arsip. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified archive.
     *
     * Akses per-dokumen: Super Admin selalu lolos (via Gate::before
     * global). Selain itu, hanya pemilik arsip atau user dengan
     * AccessRequest 'approved' untuk arsip ini yang boleh masuk.
     */
    public function show(Request $request, Archive $archive): View|RedirectResponse
    {
        if ($request->user()->cannot('view', $archive)) {
            return redirect()->route('archives.index')
                ->with('error', 'Kamu belum memiliki izin untuk melihat arsip ini. Silakan ajukan permintaan akses terlebih dahulu.');
        }

        $archive->load('user');

        return view('archives.show', compact('archive'));
    }

    /**
     * Show the form for editing the archive.
     */
    public function edit(Archive $archive): View
    {
        return view('archives.edit', compact('archive'));
    }

    /**
     * Update the specified archive.
     */
    public function update(Request $request, Archive $archive): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title'       => ['required', 'string', 'max:255'],
                'category'    => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status'      => ['required', 'in:active,archived,deleted'],
                'file'        => ['nullable', 'file', 'max:' . self::MAX_FILE_SIZE_KB],
            ], [
                'file.max' => 'Ukuran berkas terlalu besar. Maksimal 10 MB.',
            ]);

            $ocrSupported = false;

            if ($request->hasFile('file')) {
                Storage::disk('public')->delete($archive->file_path);

                $file = $request->file('file');
                $ext  = strtolower($file->getClientOriginalExtension());
                $ocrSupported = in_array($ext, Archive::OCR_EXTENSIONS, true);

                $validated['file_path']         = $file->store('archives', 'public');
                $validated['original_filename'] = $file->getClientOriginalName();
                $validated['file_type']         = $ext;
                $validated['file_size']         = $file->getSize();

                // Teks OCR lama sudah tidak berlaku untuk file baru.
                $validated['ocr_status']  = $ocrSupported ? 'pending' : 'skipped';
                $validated['ocr_text']    = null;
                $validated['ocr_error']   = null;
                $validated['ocr_pages']   = null;
                $validated['ocr_seconds'] = null;
                $validated['ocr_at']      = null;
            }

            // 'file' bukan kolom tabel, jangan ikut di-update.
            unset($validated['file']);

            $archive->update($validated);

            if ($ocrSupported) {
                OcrDocumentJob::dispatch($archive->id, $archive->file_path);
            }

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil diperbarui.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->withInput($request->except('file'))
                ->with('error', 'Gagal memperbarui arsip. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified archive.
     */
    public function destroy(Archive $archive): RedirectResponse
    {
        try {
            Storage::disk('public')->delete($archive->file_path);
            $archive->delete();

            return redirect()->route('archives.index')
                ->with('success', 'Arsip berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('archives.index')
                ->with('error', 'Gagal menghapus arsip. Silakan coba lagi.');
        }
    }

    /**
     * Proses ulang OCR untuk arsip yang gagal.
     */
    public function ocrRetry(Request $request, Archive $archive): RedirectResponse
    {
        if ($request->user()->cannot('edit archives') || $request->user()->cannot('view', $archive)) {
            abort(403);
        }

        if (! $archive->isOcrSupported()) {
            return back()->with('error', 'Tipe berkas ini tidak didukung OCR.');
        }

        if ($archive->ocr_status !== 'failed') {
            return back()->with('error', 'OCR hanya bisa diulang untuk arsip yang gagal.');
        }

        $archive->update([
            'ocr_status'  => 'pending',
            'ocr_text'    => null,
            'ocr_error'   => null,
            'ocr_pages'   => null,
            'ocr_seconds' => null,
            'ocr_at'      => null,
        ]);

        OcrDocumentJob::dispatch($archive->id, $archive->file_path);

        return back()->with('success', 'OCR dijalankan ulang.');
    }

    /**
     * Download the specified archive file.
     *
     * Akses per-dokumen: aturan sama persis dengan show().
     */
    public function download(Request $request, Archive $archive)
    {
        if ($request->user()->cannot('download', $archive)) {
            return redirect()->route('archives.index')
                ->with('error', 'Kamu belum memiliki izin untuk mengunduh arsip ini. Silakan ajukan permintaan akses terlebih dahulu.');
        }

        try {
            return Storage::disk('public')->download(
                $archive->file_path,
                $archive->original_filename ?? ($archive->title . '.' . $archive->file_type)
            );
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('archives.index')
                ->with('error', 'Berkas tidak ditemukan atau gagal diunduh.');
        }
    }
}
