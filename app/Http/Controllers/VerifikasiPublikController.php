<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Illuminate\Http\Response;

class VerifikasiPublikController extends Controller
{
    /**
     * Halaman verifikasi publik berdasarkan QR token.
     */
    public function show(string $qrToken): Response
    {
        $pengajuan = PengajuanSurat::query()
            ->with(['jenisSurat', 'penandatangan'])
            ->where('qr_token', $qrToken)
            ->first();

        $isValid = $this->isValid($pengajuan);

        return response()
            ->view('verifikasi.show', [
                'isValid' => $isValid,
                'pengajuan' => $pengajuan,
                'namaPemohon' => $this->extractNama($pengajuan),
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Validasi apakah surat dianggap valid.
     */
    protected function isValid(?PengajuanSurat $pengajuan): bool
    {
        if (! $pengajuan) {
            return false;
        }

        if ($pengajuan->status !== StatusPengajuan::SELESAI) {
            return false;
        }

        if (blank($pengajuan->nomor_surat)) {
            return false;
        }

        if (is_null($pengajuan->tanggal_ttd)) {
            return false;
        }

        return true;
    }

    /**
     * Ambil nama yang akan ditampilkan ke publik.
     * Hanya field tertentu yang ditampilkan untuk menjaga privasi.
     */
    protected function extractNama(?PengajuanSurat $pengajuan): string
    {
        if (! $pengajuan) {
            return '-';
        }

        $data = $pengajuan->data_json ?? [];

        if (! is_array($data)) {
            return '-';
        }

        $possibleKeys = [
            'nama',
            'nama_lengkap',
            'nama_mahasiswa',
            'nama_dosen',
            'nama_peserta',
        ];

        foreach ($possibleKeys as $key) {
            if (isset($data[$key]) && is_scalar($data[$key])) {
                return (string) $data[$key];
            }
        }

        return '-';
    }
}