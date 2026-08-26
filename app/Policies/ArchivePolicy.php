<?php

namespace App\Policies;

use App\Models\AccessRequest;
use App\Models\Archive;
use App\Models\User;

class ArchivePolicy
{
    /**
     * Tentukan apakah user boleh melihat detail arsip ini.
     *
     * Catatan: Super Admin TIDAK dicek di sini — sudah otomatis lolos
     * lewat Gate::before() global di AppServiceProvider. Policy ini
     * hanya menangani logika untuk role selain Super Admin.
     *
     * Aturan:
     * - Pemilik arsip (yang mengunggah) selalu boleh lihat.
     * - Selain itu, hanya boleh lihat kalau punya AccessRequest
     *   berstatus 'approved' untuk arsip SPESIFIK ini.
     */
    public function view(User $user, Archive $archive): bool
    {
        return $this->hasApprovedAccess($user, $archive);
    }

    /**
     * Tentukan apakah user boleh mengunduh berkas arsip ini.
     * Aturan sama persis dengan view().
     */
    public function download(User $user, Archive $archive): bool
    {
        return $this->hasApprovedAccess($user, $archive);
    }

    /**
     * Logika inti pengecekan akses per-dokumen.
     */
    private function hasApprovedAccess(User $user, Archive $archive): bool
    {
        if ($archive->user_id === $user->id) {
            return true;
        }

        return AccessRequest::query()
            ->where('archive_id', $archive->id)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();
    }
}