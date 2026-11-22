<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        $expiryMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Atur ulang kata sandi akun Anda')
            ->greeting('Halo,')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Galeri Magang Anda.')
            ->action('Buat kata sandi baru', $url)
            ->line('Tautan ini berlaku selama '.$expiryMinutes.' menit.')
            ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini.');
    }
}
