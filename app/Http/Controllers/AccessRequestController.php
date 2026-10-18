<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\Archive;
use App\Notifications\AktivitasNotifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AccessRequestController extends Controller
{
    /**
     * Display a listing of access requests.
     * Admin melihat semua, User biasa melihat miliknya sendiri.
     */
    public function index(Request $request): View
    {
        $query = AccessRequest::query()->with(['archive', 'user', 'reviewer'])->latest();

        // Filter berdasarkan status dari form pencarian (opsional)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // PEMBATASAN AKSES: Jika bukan admin/tidak bisa melihat semua, filter hanya miliknya sendiri
        if (! $request->user()->can('view all access requests')) {
            $query->where('user_id', $request->user()->id);
        }

        $accessRequests = $query->paginate(10)->withQueryString();

        return view('access-requests.index', compact('accessRequests'));
    }

    /**
     * Show the form for submitting a new access request (Staff).
     */
    public function create(Request $request): View
    {
        $archives = Archive::query()->orderBy('title')->get(['id', 'title']);

        return view('access-requests.create', compact('archives'));
    }

    /**
     * Submit a new access request for a specific archive (Staff).
     */
    public function store(Request $request, ?Archive $archive = null): RedirectResponse
    {
        try {
            $isDirectRequest = $archive !== null;

            $rules = [
                'reason' => ['nullable', 'string', 'max:1000'],
            ];

            if (! $isDirectRequest) {
                $rules['archive_id'] = ['required', 'exists:archives,id'];
            }

            $validated = $request->validate($rules);

            if (! $isDirectRequest) {
                $archive = Archive::findOrFail($request->input('archive_id'));
            }

            $alreadyRequested = AccessRequest::query()
                ->where('archive_id', $archive->id)
                ->where('user_id', $request->user()->id)
                ->where('status', 'pending')
                ->exists();

            if ($alreadyRequested) {
                return redirect()->back()
                    ->with('error', 'Kamu sudah mengajukan permintaan akses untuk arsip ini dan masih menunggu persetujuan.');
            }

            AccessRequest::create([
                'archive_id' => $archive->id,
                'user_id'    => $request->user()->id,
                'reason'     => $validated['reason'] ?? null,
                'status'     => 'pending',
            ]);

            // Beri tahu semua yang berhak menyetujui.
            AktivitasNotifikasi::kirim(
                AktivitasNotifikasi::penggunaDenganIzin('approve archive access'),
                'Permintaan akses baru',
                "{$request->user()->name} meminta akses ke arsip \"{$archive->title}\".",
                route('access-requests.index', [], false),
            );

            return $isDirectRequest
                ? redirect()->back()
                    ->with('success', 'Permintaan izin akses berhasil diajukan. Menunggu persetujuan admin.')
                : redirect()->route('access-requests.index')
                    ->with('success', 'Permintaan izin akses berhasil diajukan. Menunggu persetujuan admin.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Gagal mengajukan permintaan akses. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified access request in detail.
     */
    public function show(Request $request, AccessRequest $accessRequest): View
    {
        // PEMBATASAN AKSES: Cek apakah user berhak melihat detail ini
        // Tolak jika: User tidak punya izin lihat semua (bukan admin) DAN request ini bukan miliknya.
        if (! $request->user()->can('view all access requests') && $accessRequest->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki hak untuk melihat detail permintaan ini.');
        }

        $accessRequest->load(['archive', 'user', 'reviewer']);

        return view('access-requests.show', compact('accessRequest'));
    }

    /**
     * Approve the specified access request (Admin).
     */
    public function approve(Request $request, AccessRequest $accessRequest): RedirectResponse
    {
        try {
            if ($accessRequest->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
            }

            $validated = $request->validate([
                'review_note' => ['nullable', 'string', 'max:1000'],
            ]);

            $accessRequest->update([
                'status'      => 'approved',
                'reviewed_by' => $request->user()->id,
                'review_note' => $validated['review_note'] ?? null,
                'reviewed_at' => now(),
            ]);

            AktivitasNotifikasi::kirim(
                $accessRequest->user,
                'Permintaan akses disetujui',
                "Kamu sekarang bisa mengakses arsip \"{$accessRequest->archive->title}\".",
                route('archives.show', $accessRequest->archive_id, false),
            );

            return redirect()->route('access-requests.index')
                ->with('success', 'Permintaan akses berhasil disetujui.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Gagal menyetujui permintaan akses. Silakan coba lagi.');
        }
    }

    /**
     * Reject the specified access request (Admin).
     */
    public function reject(Request $request, AccessRequest $accessRequest): RedirectResponse
    {
        try {
            if ($accessRequest->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
            }

            $validated = $request->validate([
                'review_note' => ['nullable', 'string', 'max:1000'],
            ]);

            $accessRequest->update([
                'status'      => 'rejected',
                'reviewed_by' => $request->user()->id,
                'review_note' => $validated['review_note'] ?? null,
                'reviewed_at' => now(),
            ]);

            AktivitasNotifikasi::kirim(
                $accessRequest->user,
                'Permintaan akses ditolak',
                "Permintaan akses ke arsip \"{$accessRequest->archive->title}\" ditolak.",
                route('access-requests.show', $accessRequest, false),
            );

            return redirect()->route('access-requests.index')
                ->with('success', 'Permintaan akses ditolak.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Gagal menolak permintaan akses. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified access request (Admin).
     */
    public function destroy(AccessRequest $accessRequest): RedirectResponse
    {
        try {
            $accessRequest->delete();

            return redirect()->route('access-requests.index')
                ->with('success', 'Permintaan akses berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('access-requests.index')
                ->with('error', 'Gagal menghapus permintaan akses. Silakan coba lagi.');
        }
    }
}