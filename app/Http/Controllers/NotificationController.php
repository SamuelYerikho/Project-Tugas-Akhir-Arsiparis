<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** Daftar 10 notifikasi terbaru + jumlah belum dibaca. */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $items = $user->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($n) => [
                'id'     => $n->id,
                'judul'  => $n->data['judul'] ?? 'Notifikasi',
                'pesan'  => $n->data['pesan'] ?? '',
                'url'    => $n->data['url'] ?? null,
                'dibaca' => $n->read_at !== null,
                'waktu'  => $n->created_at->locale('id')->diffForHumans(),
            ]);

        return response()->json([
            'unread' => $user->unreadNotifications()->count(),
            'items'  => $items,
        ]);
    }

    /** Tandai satu notifikasi sudah dibaca. */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        // Dicari lewat relasi user, jadi tidak bisa menyentuh notifikasi milik orang lain.
        $user->notifications()->findOrFail($id)->markAsRead();

        return response()->json(['unread' => $user->unreadNotifications()->count()]);
    }

    /** Tandai semua notifikasi sudah dibaca. */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['unread' => 0]);
    }
}