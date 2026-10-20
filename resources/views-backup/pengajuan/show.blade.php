@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Kolom kiri --}}
        <div class="space-y-6 xl:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Detail Pengajuan
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            No Tiket: {{ $pengajuan->no_tiket }}
                        </p>
                    </div>

                    @include('partials.status-badge', ['status' => $pengajuan->status])
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jenis Surat</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $pengajuan->jenisSurat?->nama }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pemohon</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $pengajuan->pemohon?->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor Surat</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $pengajuan->nomor_surat ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $pengajuan->created_at->format('d M Y H:i') }}
                        </dd>
                    </div>

                    @if ($pengajuan->penandatangan)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Penandatangan</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $pengajuan->penandatangan->name }}
                            </dd>
                        </div>
                    @endif

                    @if ($pengajuan->tanggal_ttd)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal TTD</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $pengajuan->tanggal_ttd->format('d M Y H:i') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">
                    Data Isian Surat
                </h3>

                <div class="mt-4 divide-y divide-gray-100">
                    @foreach ($pengajuan->data_json as $key => $value)
                        <div class="grid grid-cols-1 gap-2 py-3 md:grid-cols-3">
                            <div class="text-sm font-medium text-gray-500">
                                {{ ucwords(str_replace('_', ' ', $key)) }}
                            </div>

                            <div class="text-sm text-gray-900 md:col-span-2">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">
                    Lampiran
                </h3>

                <div class="mt-4 space-y-2">
                    @forelse ($pengajuan->lampiran as $lampiran)
                        <a
                            href="{{ asset('storage/' . $lampiran->file_path) }}"
                            target="_blank"
                            class="flex items-center justify-between rounded-md border border-gray-200 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            <span>{{ $lampiran->jenis }}</span>
                            <span class="text-xs text-blue-600">Lihat File</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">
                            Tidak ada lampiran.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="space-y-6">
            @if ($pengajuan->catatan_revisi)
                <div class="rounded-lg border border-orange-200 bg-orange-50 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-orange-900">
                        Catatan Revisi / Penolakan
                    </h3>

                    <p class="mt-2 text-sm text-orange-800">
                        {{ $pengajuan->catatan_revisi }}
                    </p>
                </div>
            @endif

            <div
                x-data="{ openReject: false }"
                class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm"
            >
                <h3 class="text-sm font-semibold text-gray-900">
                    Aksi Proses
                </h3>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <h3 class="text-sm font-semibold text-gray-900">
        Aksi Proses
    </h3>

    <div class="mt-4 space-y-3">
        {{-- Tombol Submit ke BAAK --}}
        @can('update', $pengajuan)
            <form
                method="POST"
                action="{{ route('pengajuan.submit', $pengajuan) }}"
                onsubmit="return confirm('Ajukan surat ini untuk diproses?')"
            >
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Submit ke BAAK
                </button>
            </form>
        @endcan

        {{-- Tombol Approve (BAAK) --}}
        @can('verify', $pengajuan)
            <form
                method="POST"
                action="{{ route('verifikasi.approve', $pengajuan) }}"
                onsubmit="return confirm('Setujui pengajuan ini?')"
            >
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700"
                >
                    Approve
                </button>
            </form>

            <button
                type="button"
                @click="openReject = true"
                class="w-full rounded-md bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700"
            >
                Revisi / Tolak
            </button>
        @endcan

        {{-- Tombol Tanda Tangani (Penandatangan) --}}
        @can('sign', $pengajuan)
            <form
                method="POST"
                action="{{ route('ttd.sign', $pengajuan) }}"
                onsubmit="return confirm('Tanda tangani surat ini?')"
            >
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-md bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700"
                >
                    Tanda Tangani
                </button>
            </form>
        @endcan

        {{-- Tombol Download PDF (Semua Role yang Diizinkan) --}}
        @can('download', $pengajuan)
            <a
                href="{{ route('pengajuan.download', $pengajuan) }}"
                class="block w-full rounded-md bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700"
            >
                Download PDF
            </a>
        @endcan
    </div>
</div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">
                    Riwayat Aktivitas
                </h3>

                <div class="mt-4 space-y-4">
                    @forelse ($pengajuan->logAktivitas as $log)
                        <div class="border-l-2 border-gray-200 pl-4">
                            <p class="text-sm font-medium text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $log->aksi)) }}
                            </p>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ $log->keterangan }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                {{ $log->user?->name }} - {{ $log->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">
                            Belum ada riwayat aktivitas.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @if ($pengajuan->status === \App\Enums\StatusPengajuan::SELESAI && $pengajuan->qr_token)
    <a
        href="{{ route('verifikasi.publik', $pengajuan->qr_token) }}"
        target="_blank"
        class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50"
    >
        Lihat Halaman Verifikasi
    </a>
@endif
@endsection