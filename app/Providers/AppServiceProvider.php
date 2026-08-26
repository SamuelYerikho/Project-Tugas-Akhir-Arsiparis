<?php

namespace App\Providers;

use App\Models\Archive;
use App\Policies\ArchivePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super Admin otomatis lolos semua pengecekan permission/role,
        // tanpa perlu daftar permission satu-satu.
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Daftarkan ArchivePolicy secara eksplisit untuk pengecekan
        // akses per-dokumen (view/download) berdasarkan kepemilikan
        // arsip atau AccessRequest yang sudah 'approved'.
        Gate::policy(Archive::class, ArchivePolicy::class);
    }
}