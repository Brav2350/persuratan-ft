<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use App\Models\Lampiran;
use App\Models\LogAktivitas;
use App\Enums\StatusPengajuan;
use App\Http\Requests\StorePengajuanRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;


class PengajuanSuratController extends Controller
{
    use AuthorizesRequests;
    public function index()
{
    $user = auth()->user();

    $query = PengajuanSurat::query()
        ->with(['jenisSurat', 'pemohon'])
        ->latest();

    // Pemohon non-staff hanya melihat surat miliknya
    if (! $user->hasAnyRole(['Admin Fakultas', 'BAAK', 'Super Admin'])) {
        $query->where('user_id', $user->id);
    }

    if (request()->filled('status')) {
        $query->where('status', request('status'));
    }

    if (request()->filled('jenis_surat_id')) {
        $query->where('jenis_surat_id', request('jenis_surat_id'));
    }

    $pengajuanList = $query->paginate(10)->withQueryString();

    return view('pengajuan.index', compact('pengajuanList'));
}

    public function show(PengajuanSurat $pengajuan)
{
    $this->authorize('view', $pengajuan);

    $pengajuan->load([
        'jenisSurat',
        'pemohon',
        'penandatangan',
        'targetSigner',
        'lampiran',
        'logAktivitas.user',
    ]);

    return view('pengajuan.show', compact('pengajuan'));
}
    public function create()
{
    $this->authorize('create', PengajuanSurat::class);

    $jenisSurat = JenisSurat::all();

    $jenisListData = $jenisSurat->map(fn ($item) => [
        'id' => (int) $item->id,
        'nama' => $item->nama,
        'fields' => array_values($item->fields_json ?? []),
    ])->values()->all();

    $penandatangan = User::query()
        ->role(['Penandatangan', 'Super Admin'])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('pengajuan.create', compact('jenisListData', 'penandatangan'));
}

    public function store(StorePengajuanRequest $request)
{
    $this->authorize('create', PengajuanSurat::class);

    $submit = $request->input('submit_action') === 'submit';

    DB::transaction(function () use ($request, $submit) {
        $pengajuan = PengajuanSurat::create([
            'jenis_surat_id' => $request->jenis_surat_id,
            'user_id' => auth()->id(),
            'target_signer_id' => $request->target_signer_id,
            'data_json' => $request->data_json,
            'status' => $submit ? StatusPengajuan::MENUNGGU_NOMOR : StatusPengajuan::MENGAJUKAN,
        ]);

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $index => $file) {
                $path = $file->store('lampiran/'.$pengajuan->id, 'public');

                Lampiran::create([
                    'pengajuan_id' => $pengajuan->id,
                    'file_path' => $path,
                    'jenis' => 'dokumen_pendukung_'.($index + 1),
                ]);
            }
        }

        LogAktivitas::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => auth()->id(),
            'aksi' => $submit ? 'submit' : 'create',
            'keterangan' => $submit
                ? 'Pengajuan dibuat dan langsung diajukan ke BAAK.'
                : 'Pengajuan disimpan sebagai draft.',
        ]);
    });

    return redirect()->route('pengajuan.index')->with(
        'success',
        $submit ? 'Surat berhasil diajukan ke BAAK.' : 'Draft tersimpan. Anda bisa mengajukan kapan saja.'
    );
}

    
    // Method submit untuk mengubah status dari Draft ke Menunggu Nomor
    public function submit(PengajuanSurat $pengajuan)
    {
        
        $this->authorize('update', $pengajuan); // Cek Policy
        
        $pengajuan->update(['status' => StatusPengajuan::MENUNGGU_NOMOR]);
        
        LogAktivitas::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'aksi' => 'submit',
            'keterangan' => 'Mengajukan surat untuk diproses BAAK.',
        ]);

        return back()->with('success', 'Surat berhasil diajukan.');
    }
    public function download(PengajuanSurat $pengajuan)
{
    $this->authorize('download', $pengajuan);

    if (! $pengajuan->file_pdf) {
        abort(404, 'PDF final belum tersedia.');
    }

    $disk = Storage::disk(config('surat.pdf_disk', 'local'));

    if (! $disk->exists($pengajuan->file_pdf)) {
        abort(404, 'File PDF tidak ditemukan.');
    }

   
    $nomorSafe = str_replace(['/', '\\', ' '], '-', $pengajuan->nomor_surat ?? $pengajuan->no_tiket);

    $filename = sprintf(
        '%s-%s.pdf',
        $nomorSafe,
        $pengajuan->jenisSurat?->kode ?? 'surat'
    );

    return response()->download(
        $disk->path($pengajuan->file_pdf),
        $filename
    );
}
public function edit(PengajuanSurat $pengajuan)
{
    $this->authorize('update', $pengajuan);

    $jenisListData = JenisSurat::all()->map(fn ($item) => [
        'id' => (int) $item->id,
        'nama' => $item->nama,
        'fields' => array_values($item->fields_json ?? []),
    ])->values()->all();

    $penandatangan = User::query()
        ->role(['Penandatangan', 'Super Admin'])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('pengajuan.edit', compact('pengajuan', 'jenisListData', 'penandatangan'));
}
public function update(UpdatePengajuanRequest $request, PengajuanSurat $pengajuan)
{
    $this->authorize('update', $pengajuan);

    $submit = $request->input('submit_action') === 'submit';

    DB::transaction(function () use ($request, $pengajuan, $submit) {
        $pengajuan->update([
            'jenis_surat_id' => $request->jenis_surat_id,
            'target_signer_id' => $request->target_signer_id,
            'data_json' => $request->data_json,
            'status' => $submit ? StatusPengajuan::MENUNGGU_NOMOR : StatusPengajuan::MENGAJUKAN,
        ]);

        if ($request->hasFile('lampiran')) {
            $existing = $pengajuan->lampiran()->count();

            foreach ($request->file('lampiran') as $index => $file) {
                $path = $file->store('lampiran/'.$pengajuan->id, 'public');

                Lampiran::create([
                    'pengajuan_id' => $pengajuan->id,
                    'file_path' => $path,
                    'jenis' => 'dokumen_pendukung_'.($existing + $index + 1),
                ]);
            }
        }

        LogAktivitas::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => auth()->id(),
            'aksi' => $submit ? 'submit' : 'update',
            'keterangan' => $submit
                ? 'Draft diperbarui dan diajukan ke BAAK.'
                : 'Draft diperbarui.',
        ]);
    });

    return redirect()->route('pengajuan.index')->with(
        'success',
        $submit ? 'Surat berhasil diajukan ke BAAK.' : 'Perubahan draft tersimpan.'
    );
}
public function destroy(PengajuanSurat $pengajuan)
{
    $this->authorize('delete', $pengajuan);

    DB::transaction(function () use ($pengajuan) {
        foreach ($pengajuan->lampiran as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
        }

        $pengajuan->delete();
    });

    return redirect()->route('pengajuan.index')->with('success', 'Draft dihapus.');
}

}