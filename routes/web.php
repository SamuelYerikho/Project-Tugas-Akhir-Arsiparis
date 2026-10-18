<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;



Route::get('/', function () {
    return view('home');
})->name('home');

// Autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.process');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/baca-semua', 'markAllAsRead')->name('baca-semua');
        Route::post('/{id}/baca', 'markAsRead')->name('baca');
    });

    // Pengaturan Akun
    Route::prefix('pengaturan-akun')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
    });

    // Manajemen Role
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/', 'index')->name('index')->middleware('permission:view roles');
            Route::get('/create', 'create')->name('create')->middleware('permission:create roles');
            Route::post('/', 'store')->name('store')->middleware('permission:create roles');
            Route::get('/{role}/edit', 'edit')->name('edit')->middleware('permission:edit roles');
            Route::put('/{role}', 'update')->name('update')->middleware('permission:edit roles');
            Route::delete('/{role}', 'destroy')->name('destroy')->middleware('permission:delete roles');
        });

        // Kelola Permission per Role
        Route::controller(RolePermissionController::class)->group(function () {
            Route::get('/{role}/permissions', 'edit')->name('permissions.edit')->middleware('permission:assign role permissions');
            Route::put('/{role}/permissions', 'update')->name('permissions.update')->middleware('permission:assign role permissions');
        });
    });

    // Manajemen Pengguna
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view users');
        Route::get('/create', 'create')->name('create')->middleware('permission:create users');
        Route::post('/', 'store')->name('store')->middleware('permission:create users');
        Route::get('/{user}/edit', 'edit')->name('edit')->middleware('permission:edit users');
        Route::put('/{user}', 'update')->name('update')->middleware('permission:edit users');
        Route::delete('/{user}', 'destroy')->name('destroy')->middleware('permission:delete users');

        // Reset Password Pengguna
        Route::get('/{user}/password', 'editPassword')->name('password.edit')->middleware('permission:reset user password');
        Route::put('/{user}/password', 'updatePassword')->name('password.update')->middleware('permission:reset user password');
    });

    // Manajemen Arsip
    Route::prefix('archives')->name('archives.')->group(function () {
        Route::controller(ArchiveController::class)->group(function () {
            Route::get('/', 'index')->name('index')->middleware('permission:view archives');
            Route::get('/create', 'create')->name('create')->middleware('permission:upload archives');
            Route::post('/', 'store')->name('store')->middleware('permission:upload archives');
            Route::get('/{archive}', 'show')->name('show')->middleware('permission:show archives');
            Route::get('/{archive}/edit', 'edit')->name('edit')->middleware('permission:edit archives');
            Route::put('/{archive}', 'update')->name('update')->middleware('permission:edit archives');
            Route::delete('/{archive}', 'destroy')->name('destroy')->middleware('permission:delete archives');
            Route::get('/{archive}/download', 'download')->name('download')->middleware('permission:download archives');
            // Proses ulang OCR (hanya untuk arsip yang gagal)
            Route::post('/{archive}/ocr-retry', 'ocrRetry')->name('ocr-retry')->middleware('permission:edit archives');
        });

        // Permintaan Izin Akses Arsip (berdasarkan ID arsip)
        Route::post('/{archive}/access-requests', [AccessRequestController::class, 'store'])
            ->name('access-requests.store')
            ->middleware('permission:request archive access');
    });

    // Manajemen Access Requests
    Route::prefix('access-requests')->name('access-requests.')->controller(AccessRequestController::class)->group(function () {
        // Gunakan separator | (OR) agar yang punya akses 'own' ATAU 'all' bisa membuka halamannya
        Route::get('/', 'index')->name('index')->middleware('permission:view own access requests|view all access requests');
        Route::get('/create', 'create')->name('create')->middleware('permission:request archive access');
        Route::post('/', 'store')->name('store')->middleware('permission:request archive access');
        Route::get('/{accessRequest}', 'show')->name('show')->middleware('permission:view own access requests|view all access requests');

        Route::put('/{accessRequest}/approve', 'approve')->name('approve')->middleware('permission:approve archive access');
        Route::put('/{accessRequest}/reject', 'reject')->name('reject')->middleware('permission:reject archive access');
        Route::delete('/{accessRequest}', 'destroy')->name('destroy')->middleware('permission:delete archive access request');
    });

    // Manajemen Laporan
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view reports');
        Route::get('/create', 'create')->name('create')->middleware('permission:generate reports');
        Route::post('/', 'store')->name('store')->middleware('permission:generate reports');
        Route::delete('/{report}', 'destroy')->name('destroy')->middleware('permission:generate reports');
        Route::get('/{report}/download', 'download')->name('download')->middleware('permission:download reports');
    });
});
