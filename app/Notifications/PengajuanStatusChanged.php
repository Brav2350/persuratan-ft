<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Jumlah percobaan jika queue gagal.
     */
    public $tries = 3;

    /**
     * Jeda retry dalam detik.
     */
    public $backoff = 60;

    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $oldStatus
    ) {
        // Set afterCommit di constructor untuk menghindari konflik dengan trait Queueable
        $this->afterCommit = true;
    }

    public function via(object $notifiable): array
    {
        $via = ['database'];

        if (config('surat.notification_email_enabled') && ! empty($notifiable->email)) {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembaruan Status Pengajuan Surat')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Status pengajuan surat berikut telah berubah.')
            ->line('No Tiket: ' . $this->pengajuan->no_tiket)
            ->line('Jenis Surat: ' . ($this->pengajuan->jenisSurat?->nama ?? '-'))
            ->line('Status sebelumnya: ' . $this->oldStatus)
            ->line('Status sekarang: ' . $this->pengajuan->status->label())
            ->action('Lihat Detail', route('pengajuan.detail', $this->pengajuan))
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Status Pengajuan Berubah',
            'pengajuan_id' => $this->pengajuan->id,
            'no_tiket' => $this->pengajuan->no_tiket,
            'jenis_surat' => $this->pengajuan->jenisSurat?->nama,
            'old_status' => $this->oldStatus,
            'new_status' => $this->pengajuan->status->label(),
            'url' => route('pengajuan.detail', $this->pengajuan),
        ];
    }
}