<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\LogAktivitas;
use App\Enums\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function approve(PengajuanSurat $pengajuan)
    {
        $this->authorize('verify', $pengajuan);

        $pengajuan->update(['status' => StatusPengajuan::MENUNGGU_TTD]);
        
        LogAktivitas::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'aksi' => 'approve',
            'keterangan' => 'Verifikasi disetujui, diteruskan ke Penandatangan.',
        ]);

        return back()->with('success', 'Surat berhasil diverifikasi.');
    }

    public function reject(Request $request, PengajuanSurat $pengajuan)
    {
        $this->authorize('verify', $pengajuan);
        $request->validate(['catatan_revisi' => 'required|string']);

        $pengajuan->update([
            'status' => StatusPengajuan::REVISI, // Atau DITOLAK sesuai logic bisnis
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        LogAktivitas::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'aksi' => 'reject',
            'keterangan' => 'Dikembalikan untuk revisi: ' . $request->catatan_revisi,
        ]);

        return back()->with('success', 'Surat dikembalikan untuk revisi.');
    }
}