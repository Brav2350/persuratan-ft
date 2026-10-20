<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\JenisSurat;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    protected function filters(Request $request): array
    {
        return $request->validate([
            'dari' => [
                'nullable',
                'date',
            ],
            'sampai' => [
                'nullable',
                'date',
                'after_or_equal:dari',
            ],
            'status' => [
                'nullable',
                Rule::enum(StatusPengajuan::class),
            ],
            'jenis_surat_id' => [
                'nullable',
                'exists:jenis_surat,id',
            ],
        ]);
    }

    public function index(Request $request, LaporanService $service)
    {
        $filters = $this->filters($request);

        $laporan = $service->query($filters)
            ->paginate(20)
            ->withQueryString();

        $jenisSurat = JenisSurat::query()
            ->orderBy('nama')
            ->get();

        $statuses = StatusPengajuan::cases();

        return view('laporan.index', compact(
            'laporan',
            'jenisSurat',
            'statuses',
            'filters'
        ));
    }

    public function exportCsv(Request $request, LaporanService $service)
    {
        $filters = $this->filters($request);

        $filename = 'laporan-pengajuan-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($service, $filters) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM agar CSV lebih rapi dibuka di Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'No Tiket',
                'Nomor Surat',
                'Jenis Surat',
                'Pemohon',
                'Status',
                'Tanggal Dibuat',
                'Tanggal TTD',
                'Penandatangan',
            ]);

            $service->query($filters)->chunk(500, function ($items) use ($handle, $service) {
                foreach ($items as $item) {
                    fputcsv($handle, [
                        $service->sanitizeCsv($item->no_tiket),
                        $service->sanitizeCsv($item->nomor_surat ?? ''),
                        $service->sanitizeCsv($item->jenisSurat?->nama ?? ''),
                        $service->sanitizeCsv($item->pemohon?->name ?? ''),
                        $service->sanitizeCsv($item->status->label()),
                        $item->created_at->format('Y-m-d H:i'),
                        $item->tanggal_ttd?->format('Y-m-d H:i') ?? '',
                        $service->sanitizeCsv($item->penandatangan?->name ?? ''),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request, LaporanService $service)
    {
        $filters = $this->filters($request);

        $laporan = $service->query($filters)
            ->limit(500)
            ->get();

        $pdf = Pdf::loadView('laporan.export-pdf', [
            'laporan' => $laporan,
            'filters' => $filters,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengajuan-' . now()->format('Y-m-d-His') . '.pdf');
    }
}