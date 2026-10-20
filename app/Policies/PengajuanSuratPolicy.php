<?php

namespace App\Policies;

use App\Models\PengajuanSurat;
use App\Models\User;
use App\Enums\StatusPengajuan;

class PengajuanSuratPolicy
{
    // Admin bisa edit jika status masih Draft (Mengajukan) atau Revisi
    public function update(User $user, PengajuanSurat $pengajuan): bool
{
    if (! in_array($pengajuan->status, [StatusPengajuan::MENGAJUKAN, StatusPengajuan::REVISI], true)) {
        return false;
    }

    if ($user->hasAnyRole(['Admin Fakultas', 'Super Admin'])) {
        return true;
    }

    // Pemohon (dosen/mahasiswa) hanya bisa edit surat miliknya sendiri
    return $user->id === $pengajuan->user_id;
}

    // BAAK bisa generate nomor jika status Menunggu Nomor
    public function generateNomor(User $user, PengajuanSurat $pengajuan): bool
{
    return $user->hasRole(['BAAK', 'Super Admin']) 
        && in_array($pengajuan->status, [
            \App\Enums\StatusPengajuan::MENUNGGU_NOMOR,
            \App\Enums\StatusPengajuan::MENUNGGU_VERIFIKASI,
        ]);
}

    // BAAK / Verifikator bisa approve jika status Menunggu Verifikasi
    public function verify(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->hasRole(['BAAK', 'Super Admin']) && 
               $pengajuan->status === StatusPengajuan::MENUNGGU_VERIFIKASI;
    }

    // Penandatangan bisa TTD jika status Menunggu TTD
    public function sign(User $user, PengajuanSurat $pengajuan): bool
{
    if ($pengajuan->status !== StatusPengajuan::MENUNGGU_TTD) {
        return false;
    }

    // Jika ada target spesifik, hanya user itu yang boleh menandatangani
    if ($pengajuan->target_signer_id) {
        return $user->id === $pengajuan->target_signer_id;
    }

    // Fallback untuk pengajuan lama tanpa target
    return $user->hasRole(['Penandatangan', 'Super Admin']);
}
    public function download(User $user, PengajuanSurat $pengajuan): bool
    {
        return $pengajuan->status === StatusPengajuan::SELESAI
        && $user->hasAnyRole([
            'Admin Fakultas',
            'BAAK',
            'Penandatangan',
            'Super Admin',
        ]);
    }
    public function view(User $user, PengajuanSurat $pengajuan): bool
{
    // Pemohon selalu bisa melihat pengajuan miliknya
    if ($pengajuan->user_id === $user->id) {
        return true;
    }

    // Role petugas bisa melihat semua pengajuan
    return $user->hasAnyRole([
        'Admin Fakultas',
        'BAAK',
        'Penandatangan',
        'Super Admin',
    ]);
}
public function create(User $user): bool
{
    return $user->hasAnyRole(['Admin Fakultas', 'Dosen', 'Mahasiswa', 'Super Admin']);
}
public function submit(User $user, PengajuanSurat $pengajuan): bool
{
    if (! in_array($pengajuan->status, [StatusPengajuan::MENGAJUKAN, StatusPengajuan::REVISI], true)) {
        return false;
    }

    return $user->id === $pengajuan->user_id
        || $user->hasAnyRole(['Admin Fakultas', 'Super Admin']);
}

public function delete(User $user, PengajuanSurat $pengajuan): bool
{
    return $pengajuan->status === StatusPengajuan::MENGAJUKAN
        && $user->id === $pengajuan->user_id;
}
}