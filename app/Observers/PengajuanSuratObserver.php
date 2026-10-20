<?php

namespace App\Observers;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanStatusChanged;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class PengajuanSuratObserver
{
    /**
     * Saat pengajuan dibuat.
     */
    public function created(PengajuanSurat $pengajuan): void
    {
        $causer = auth()->user();

        $logger = activity()
            ->performedOn($pengajuan)
            ->event('created')
            ->withProperties([
                'no_tiket' => $pengajuan->no_tiket,
                'jenis_surat_id' => $pengajuan->jenis_surat_id,
            ]);

        if ($causer) {
            $logger->causedBy($causer);
        }

        $logger->log('Pengajuan surat dibuat.');
    }

    /**
     * Saat pengajuan diperbarui.
     */
    public function updated(PengajuanSurat $pengajuan): void
    {
        if (! $pengajuan->wasChanged('status')) {
            return;
        }

        $original = $pengajuan->getOriginal('status');

        $oldValue = $original instanceof StatusPengajuan
            ? $original->value
            : ($original ? (string) $original : '');

        $oldLabel = StatusPengajuan::tryFrom($oldValue)?->label() ?: $oldValue;

        $this->logStatusChange($pengajuan, $oldLabel);

        $this->sendStatusNotification($pengajuan, $oldLabel);
    }

    /**
     * Catat perubahan status ke activity log.
     */
    protected function logStatusChange(PengajuanSurat $pengajuan, string $oldLabel): void
    {
        $causer = auth()->user();

        $logger = activity()
            ->performedOn($pengajuan)
            ->event('status_changed')
            ->withProperties([
                'old_status' => $oldLabel,
                'new_status' => $pengajuan->status->label(),
            ]);

        if ($causer) {
            $logger->causedBy($causer);
        }

        $logger->log(sprintf(
            'Status pengajuan berubah dari %s menjadi %s.',
            $oldLabel,
            $pengajuan->status->label()
        ));
    }

    /**
     * Kirim notifikasi ke pihak terkait.
     */
    protected function sendStatusNotification(PengajuanSurat $pengajuan, string $oldLabel): void
    {
        $recipients = $this->getRecipients($pengajuan);

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new PengajuanStatusChanged($pengajuan, $oldLabel)
        );
    }

    /**
     * Tentukan penerima notifikasi berdasarkan status baru.
     */
    protected function getRecipients(PengajuanSurat $pengajuan): Collection
    {
        $recipients = collect();

        // Pemohon selalu diberi tahu jika status berubah
        if ($pengajuan->pemohon) {
            $recipients->push($pengajuan->pemohon);
        }

        // BAAK perlu tahu saat menunggu nomor atau menunggu verifikasi
        if (in_array($pengajuan->status, [
            StatusPengajuan::MENUNGGU_NOMOR,
            StatusPengajuan::MENUNGGU_VERIFIKASI,
        ], true)) {
            $recipients = $recipients->merge(
                User::role(['BAAK', 'Super Admin'])->get()
            );
        }

        // Penandatangan perlu tahu saat menunggu TTD
        if ($pengajuan->status === StatusPengajuan::MENUNGGU_TTD) {
            $recipients = $recipients->merge(
                User::role(['Penandatangan', 'Super Admin'])->get()
            );
        }

        return $recipients
            ->unique('id')
            ->values()
            ->filter(function (User $user) {
                // Jangan kirim notifikasi ke user yang melakukan aksi
                if (auth()->check() && $user->is(auth()->user())) {
                    return false;
                }

                return $user->is_active;
            });
    }
}