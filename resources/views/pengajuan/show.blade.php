@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Kolom kiri --}}
        <div class="space-y-6 xl:col-span-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                            Detail Pengajuan
                        </h2>
                        <p class="mt-1 text-xs font-semibold text-[#517085]">
                            No Tiket: {{ $pengajuan->no_tiket }}
                        </p>
                    </div>

                    @include('partials.status-badge', ['status' => $pengajuan->status])
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 text-xs">
                    <div>
                        <dt class="font-semibold uppercase tracking-wider text-slate-500">Jenis Surat</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $pengajuan->jenisSurat?->nama }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold uppercase tracking-wider text-slate-500">Pemohon</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $pengajuan->pemohon?->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold uppercase tracking-wider text-slate-500">Nomor Surat</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800">
                            {{ $pengajuan->nomor_surat ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold uppercase tracking-wider text-slate-500">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-slate-700">
                            {{ $pengajuan->created_at->format('d M Y H:i') }}
                        </dd>
                    </div>

                    @if ($pengajuan->targetSigner)
                        <div>
                            <dt class="font-semibold uppercase tracking-wider text-slate-500">Target Penandatangan</dt>
                            <dd class="mt-1 font-semibold text-slate-800">
                                {{ $pengajuan->targetSigner->name }}
                                <span class="block text-[11px] font-normal text-slate-400">
                                    NIP: {{ $pengajuan->targetSigner->nip ?? '-' }}
                                </span>
                            </dd>
                        </div>
                    @endif

                    @if ($pengajuan->penandatangan)
                        <div>
                            <dt class="font-semibold uppercase tracking-wider text-slate-500">Penandatangan (Aktual)</dt>
                            <dd class="mt-1 font-semibold text-slate-800">
                                {{ $pengajuan->penandatangan->name }}
                            </dd>
                        </div>
                    @endif

                    @if ($pengajuan->tanggal_ttd)
                        <div>
                            <dt class="font-semibold uppercase tracking-wider text-slate-500">Tanggal TTD</dt>
                            <dd class="mt-1 text-slate-700">
                                {{ $pengajuan->tanggal_ttd->format('d M Y H:i') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                    Data Isian Surat
                </h3>

                <div class="divide-y divide-slate-100 text-xs">
                    @foreach ($pengajuan->data_json ?? [] as $key => $value)
                        <div class="grid grid-cols-1 gap-2 py-3 md:grid-cols-3">
                            <div class="font-semibold text-slate-500">
                                {{ ucwords(str_replace('_', ' ', $key)) }}
                            </div>

                            <div class="text-slate-800 font-medium md:col-span-2">
                                {{ is_array($value) ? implode(', ', $value) : $value }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                    Lampiran
                </h3>

                <div class="space-y-2 text-xs">
                    @forelse ($pengajuan->lampiran as $lampiran)
                        <a
                            href="{{ asset('storage/' . $lampiran->file_path) }}"
                            target="_blank"
                            class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-slate-50/50 px-4 py-3 text-slate-700 hover:bg-slate-100 transition duration-150"
                        >
                            <span class="font-medium">{{ $lampiran->jenis }}</span>
                            <span class="font-semibold text-[#517085]">Lihat File</span>
                        </a>
                    @empty
                        <p class="text-slate-400">
                            Tidak ada lampiran.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Aktivitas --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                    Riwayat Aktivitas
                </h3>

                <div class="space-y-4 text-xs">
                    @forelse ($pengajuan->logAktivitas as $log)
                        <div class="border-l-2 border-[#517085] pl-4 py-1">
                            <p class="font-bold text-slate-800">{{ $log->deskripsi }}</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">
                                Oleh: <span class="font-semibold text-slate-600">{{ $log->user?->name ?? 'Sistem' }}</span> • {{ $log->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-slate-400">Belum ada riwayat aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar Aksi Tambahan / Status Progres --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                    Aksi Pengajuan
                </h3>

                <div class="space-y-3">
                    <a
                        href="{{ route('pengajuan.index') }}"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                    >
                        Kembali ke Daftar
                    </a>

                    @can('update', $pengajuan)
                        <a
                            href="{{ route('pengajuan.edit', $pengajuan) }}"
                            class="block w-full rounded-xl bg-amber-50 border border-amber-200/80 px-4 py-2.5 text-center text-xs font-semibold text-amber-800 hover:bg-amber-100 transition duration-150"
                        >
                            Edit Pengajuan
                        </a>
                    @endcan

                    @can('submit', $pengajuan)
                        <form method="POST" action="{{ route('pengajuan.submit', $pengajuan) }}">
                            @csrf
                            <button
                                type="submit"
                                onclick="return confirm('Ajukan surat ini ke BAAK?')"
                                class="w-full rounded-xl bg-[#517085] px-4 py-2.5 text-center text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] transition duration-150"
                            >
                                Ajukan ke BAAK
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection