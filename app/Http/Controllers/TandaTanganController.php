<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\LogAktivitas;
use App\Enums\StatusPengajuan;
use App\Services\SuratPdfService;
use App\Notifications\PengajuanStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TandaTanganController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $antrian = PengajuanSurat::query()
            ->with(['jenisSurat', 'pemohon', 'targetSigner'])
            ->where('status', StatusPengajuan::MENUNGGU_TTD)
            ->where(function ($query) use ($userId) {
                $query->where('target_signer_id', $userId)
                    ->orWhereNull('target_signer_id');
            })
            ->latest()
            ->paginate(10);

        return view('ttd.index', compact('antrian'));
    }

    public function preview(PengajuanSurat $pengajuan, SuratPdfService $pdfService)
    {
        $this->authorize('sign', $pengajuan);

        $pdfContent = $pdfService->render($pengajuan, auth()->user());

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Tampilkan form pilihan mode tanda tangan
     */
    public function showSignForm(PengajuanSurat $pengajuan)
    {
        $this->authorize('sign', $pengajuan);

        $hasSignature = auth()->user()->signature_path !== null;

        return view('ttd.sign-form', compact('pengajuan', 'hasSignature'));
    }

    /**
     * Proses tanda tangan (digital atau manual)
     */
    public function sign(Request $request, PengajuanSurat $pengajuan, SuratPdfService $pdfService)
    {
        $this->authorize('sign', $pengajuan);

        $validated = $request->validate([
            'mode_ttd' => ['required', 'in:digital,manual'],
            'tanggal_ttd' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $user = auth()->user();

        // Validasi: jika mode digital, user wajib punya signature
        if ($validated['mode_ttd'] === 'digital' && ! $user->signature_path) {
            return back()->withErrors([
                'mode_ttd' => 'Anda belum mengunggah tanda tangan digital. Silakan unggah terlebih dahulu.',
            ]);
        }

        DB::transaction(function () use ($pengajuan, $pdfService, $validated, $user) {
            $oldStatus = $pengajuan->status;

            // Generate PDF final
            $pdfPath = $pdfService->finalize($pengajuan, $user);

            // Update pengajuan
            $pengajuan->update([
                'status' => StatusPengajuan::SELESAI,
                'mode_ttd' => $validated['mode_ttd'],
                'tanggal_ttd' => $validated['tanggal_ttd'],
                'penandatangan_id' => $user->id,
                'file_pdf' => $pdfPath,
            ]);

            // Log aktivitas
            LogAktivitas::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $user->id,
                'aksi' => 'sign',
                'keterangan' => sprintf(
                    'Surat ditandatangani (%s) pada %s. PDF final dibuat.',
                    $validated['mode_ttd'] === 'digital' ? 'Digital' : 'Konfirmasi Manual',
                    $pengajuan->tanggal_ttd->format('d M Y')
                ),
            ]);

            // Kirim notifikasi
            $this->sendNotifications($pengajuan, $oldStatus);
        });

        return redirect()
            ->route('ttd.antrian')
            ->with('success', 'Surat berhasil diproses.');
    }

    protected function sendNotifications(PengajuanSurat $pengajuan, $oldStatus)
    {
        // Notifikasi ke pemohon
        if ($pengajuan->pemohon) {
            $pengajuan->pemohon->notify(new PengajuanStatusChanged($pengajuan, $oldStatus));
        }

        // Notifikasi ke Super Admin
        $superAdmins = \App\Models\User::role('Super Admin')->get();
        foreach ($superAdmins as $admin) {
            $admin->notify(new PengajuanStatusChanged($pengajuan, $oldStatus));
        }
    }
}