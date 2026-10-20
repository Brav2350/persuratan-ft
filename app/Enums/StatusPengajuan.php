<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case MENGAJUKAN = 'mengajukan';
    case MENUNGGU_NOMOR = 'menunggu_nomor';
    case MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    case REVISI = 'revisi';
    case DITOLAK = 'ditolak';
    case MENUNGGU_TTD = 'menunggu_ttd';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match($this) {
            self::MENGAJUKAN => 'Mengajukan (Draft)',
            self::MENUNGGU_NOMOR => 'Menunggu Nomor Surat',
            self::MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::REVISI => 'Revisi',
            self::DITOLAK => 'Ditolak',
            self::MENUNGGU_TTD => 'Menunggu TTD',
            self::SELESAI => 'Selesai',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MENGAJUKAN => 'gray',
            self::MENUNGGU_NOMOR => 'yellow',
            self::MENUNGGU_VERIFIKASI => 'blue',
            self::REVISI => 'orange',
            self::DITOLAK => 'red',
            self::MENUNGGU_TTD => 'purple',
            self::SELESAI => 'green',
        };
    }
}