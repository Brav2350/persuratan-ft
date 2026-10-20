@extends('layouts.admin')

@section('title', 'Nomor & Verifikasi')

@section('content')
    <div x-data="nomorSuratManager()" x-cloak>
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Antrian Nomor & Verifikasi
            </h2>
            <p class="text-sm text-gray-500">
                Generate nomor surat otomatis atau input manual.
            </p>
        </div>

        {{-- Tabel Antrian --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-6 py-3">No Tiket</th>
                            <th class="px-6 py-3">Jenis Surat</th>
                            <th class="px-6 py-3">Pemohon</th>
                            <th class="px-6 py-3">Nomor Surat</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($antrian as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $item->no_tiket }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->jenisSurat?->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->pemohon?->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $item->nomor_surat ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @include('partials.status-badge', ['status' => $item->status])
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a
                                            href="{{ route('pengajuan.show', $item) }}"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                        >
                                            Detail
                                        </a>

                                        @if ($item->status === \App\Enums\StatusPengajuan::MENUNGGU_NOMOR)
                                            <button
                                                type="button"
                                                @click="openForm({{ $item->id }}, '{{ $item->no_tiket }}')"
                                                class="text-xs font-semibold text-green-600 hover:text-green-700"
                                            >
                                                Proses Nomor
                                            </button>
                                        @endif

                                        @if ($item->status === \App\Enums\StatusPengajuan::MENUNGGU_VERIFIKASI)
                                            <button
                                                type="button"
                                                @click="openForm({{ $item->id }}, '{{ $item->no_tiket }}', '{{ $item->nomor_surat }}')"
                                                class="text-xs font-semibold text-orange-600 hover:text-orange-700"
                                            >
                                                Ubah Nomor
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada antrian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($antrian->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $antrian->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Form Nomor Surat --}}
        <div
            x-show="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display: none;"
        >
            <div
                class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
                @click.outside="closeModal()"
            >
                <h3 class="text-lg font-semibold text-gray-900">
                    Proses Nomor Surat
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    No Tiket: <span x-text="selectedNoTiket" class="font-mono"></span>
                </p>

                <form
                    :action="'/baak/' + selectedId + '/generate-nomor'"
                    method="POST"
                    class="mt-6 space-y-4"
                >
                    @csrf

                    {{-- Pilihan Mode Nomor --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 cursor-pointer hover:bg-gray-50">
                            <input
                                type="radio"
                                name="mode"
                                value="otomatis"
                                x-model="mode"
                                class="text-blue-600 focus:ring-blue-500"
                            >
                            <div>
                                <span class="text-sm font-medium text-gray-900">Nomor Otomatis</span>
                                <p class="text-xs text-gray-500">
                                    Preview: <span x-text="nomorPreview || 'Memuat...'" class="font-mono"></span>
                                </p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 cursor-pointer hover:bg-gray-50">
                            <input
                                type="radio"
                                name="mode"
                                value="manual"
                                x-model="mode"
                                class="text-blue-600 focus:ring-blue-500"
                            >
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Nomor Manual</span>
                                <input
                                    type="text"
                                    name="nomor_manual"
                                    x-model="nomorManual"
                                    :disabled="mode !== 'manual'"
                                    placeholder="Contoh: 001.A/SAK/FT/X/2025"
                                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
                                >
                            </div>
                        </label>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            type="button"
                            @click="closeModal()"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                        >
                            Simpan Nomor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <script>
        function nomorSuratManager() {
            return {
                showModal: false,
                selectedId: null,
                selectedNoTiket: '',
                mode: 'otomatis',
                nomorPreview: '',
                nomorManual: '',

                openForm(id, noTiket, existingNomor = '') {
                    this.selectedId = id;
                    this.selectedNoTiket = noTiket;
                    this.showModal = true;
                    this.mode = existingNomor ? 'manual' : 'otomatis';
                    this.nomorManual = existingNomor;
                    this.fetchPreview(id);
                },

                closeModal() {
                    this.showModal = false;
                    this.selectedId = null;
                    this.nomorManual = '';
                    this.mode = 'otomatis';
                },

                fetchPreview(id) {
                    fetch(`/baak/${id}/preview-nomor`)
                        .then(res => res.json())
                        .then(data => {
                            this.nomorPreview = data.nomor_surat;
                        })
                        .catch(() => {
                            this.nomorPreview = 'Error memuat preview';
                        });
                }
            };
        }
    </script>
@endsection