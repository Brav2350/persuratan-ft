<?php

namespace App\Services;

use App\Models\JenisSurat;
use App\Models\NomorSuratCounter;
use App\Models\PengajuanSurat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    protected array $romawi = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    /**
     * Preview nomor surat tanpa menaikkan counter.
     */
    public function preview(JenisSurat $jenisSurat, ?Carbon $date = null): string
    {
        $date = $date ?? now();

        $counter = NomorSuratCounter::query()
            ->where('jenis_surat_id', $jenisSurat->id)
            ->where('tahun', $date->year)
            ->value('counter') ?? 0;

        return $this->format($counter + 1, $jenisSurat->kode, $date);
    }

    /**
     * Generate nomor surat dan menaikkan counter secara aman.
     */
    public function generate(PengajuanSurat $pengajuan): string
    {
        return DB::transaction(function () use ($pengajuan) {
            $tahun = now()->year;

            $counter = NomorSuratCounter::firstOrCreate(
                [
                    'jenis_surat_id' => $pengajuan->jenis_surat_id,
                    'tahun' => $tahun,
                ],
                [
                    'counter' => 0,
                ]
            );

            $counter = NomorSuratCounter::query()
                ->whereKey($counter->id)
                ->lockForUpdate()
                ->first();

            $counter->increment('counter');

            return $this->format(
                $counter->counter,
                $pengajuan->jenisSurat->kode,
                now()
            );
        });
    }

    protected function format(int $counter, string $kode, Carbon $date): string
    {
        $nomorUrut = str_pad((string) $counter, 3, '0', STR_PAD_LEFT);
        $suffix = config('surat.number_suffix', '');
        $unit = config('surat.unit_code', 'FT');
        $bulanRomawi = $this->romawi[$date->month];
        $tahun = $date->year;

        return sprintf(
            '%s%s/%s/%s/%s/%s',
            $nomorUrut,
            $suffix,
            $kode,
            $unit,
            $bulanRomawi,
            $tahun
        );
    }
}