<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LaporanService
{
    /**
     * Query utama laporan pengajuan.
     */
    public function query(array $filters): Builder
    {
        return PengajuanSurat::query()
            ->with(['jenisSurat', 'pemohon', 'penandatangan'])
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $jenisSuratId) {
                $query->where('jenis_surat_id', $jenisSuratId);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['dari'] ?? null, function ($query, $dari) {
                $query->whereDate('created_at', '>=', $dari);
            })
            ->when($filters['sampai'] ?? null, function ($query, $sampai) {
                $query->whereDate('created_at', '<=', $sampai);
            })
            ->latest();
    }

    /**
     * Statistik pengajuan per status.
     */
    public function statistikPerStatus(): Collection
    {
        return PengajuanSurat::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->mapWithKeys(function ($total, $status) {
                $enum = StatusPengajuan::tryFrom($status);

                return [
                    $enum?->label() ?? $status => $total,
                ];
            });
    }

    /**
     * Statistik pengajuan per jenis surat.
     */
    public function statistikPerJenis(int $limit = 5): Collection
    {
        return PengajuanSurat::query()
            ->join('jenis_surat', 'jenis_surat.id', '=', 'pengajuan_surat.jenis_surat_id')
            ->selectRaw('jenis_surat.nama, COUNT(pengajuan_surat.id) as total')
            ->groupBy('jenis_surat.nama')
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('total', 'nama');
    }

    /**
     * Statistik bulanan untuk chart.
     */
    public function statistikBulanan(int $tahun): array
    {
        $data = PengajuanSurat::query()
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        return array_map(
            fn ($bulan) => $data[$bulan] ?? 0,
            range(1, 12)
        );
    }

    /**
     * Sanitasi nilai CSV untuk mencegah CSV injection.
     */
    public function sanitizeCsv(mixed $value): string
    {
        $value = (string) $value;

        if (preg_match('/^[=+\-@\t\r]/', $value)) {
            return "'" . $value;
        }

        return $value;
    }
}