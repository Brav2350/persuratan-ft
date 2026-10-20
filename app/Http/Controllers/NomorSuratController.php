<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\LogAktivitas;
use App\Models\PengajuanSurat;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NomorSuratController extends Controller
{
    public function index()
{
    $antrian = PengajuanSurat::query()
        ->with(['jenisSurat', 'pemohon', 'targetSigner'])
        ->whereIn('status', [
            \App\Enums\StatusPengajuan::MENUNGGU_NOMOR,
            \App\Enums\StatusPengajuan::MENUNGGU_VERIFIKASI,
        ])
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('baak.index', compact('antrian'));
}

    public function preview(PengajuanSurat $pengajuan, NomorSuratService $service)
    {
        $this->authorize('generateNomor', $pengajuan);

        $nomor = $service->preview($pengajuan->jenisSurat);

        return response()->json([
            'nomor_surat' => $nomor,
        ]);
    }

    public function generate(
        Request $request,
        PengajuanSurat $pengajuan,
        NomorSuratService $service
    ) {
        $this->authorize('generateNomor', $pengajuan);

        $validated = $request->validate([
            'nomor_manual' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('pengajuan_surat', 'nomor_surat')->ignore($pengajuan->id),
            ],
        ]);

        DB::transaction(function () use ($request, $pengajuan, $service, $validated) {
            $nomorSurat = $validated['nomor_manual'] ?? $service->generate($pengajuan);

            $pengajuan->update([
                'nomor_surat' => $nomorSurat,
                'status' => StatusPengajuan::MENUNGGU_VERIFIKASI,
            ]);

            LogAktivitas::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $request->user()->id,
                'aksi' => 'generate_nomor',
                'keterangan' => 'Nomor surat ditetapkan: ' . $nomorSurat,
            ]);
        });

        return back()->with('success', 'Nomor surat berhasil dibuat.');
    }
}