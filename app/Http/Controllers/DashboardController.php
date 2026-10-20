<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Enums\StatusPengajuan;
use App\Services\LaporanService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(Request $request, LaporanService $laporanService)
    {
        $tahun = (int) $request->input('tahun', now()->year);

        $statistikStatus = $laporanService->statistikPerStatus();

        $statistikJenis = $laporanService->statistikPerJenis();

        $bulananData = $laporanService->statistikBulanan($tahun);

        $bulanLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $recent = PengajuanSurat::query()
            ->with(['jenisSurat', 'pemohon'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'statistikStatus',
            'statistikJenis',
            'bulananData',
            'bulanLabels',
            'recent',
            'tahun'
        ));
    }
}