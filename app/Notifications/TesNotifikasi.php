<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TesNotifikasi extends Notification
{
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'judul' => 'Tes notifikasi',
            'pesan' => 'Reverb berhasil terhubung.',
        ];
    }
}