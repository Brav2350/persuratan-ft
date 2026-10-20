@extends('layouts.admin')

@section('title', 'Antrian Tanda Tangan')

@section('content')
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Antrian Tanda Tangan
        </h2>
        <p class="text-sm text-gray-500">
            Daftar surat yang menunggu tanda tangan digital.
        </p>
    </div>

    @if (! auth()->user()->signature_path)
        <div class="mb-4 rounded-md border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-800">
            Anda belum mengunggah tanda tangan digital.

            <a
                href="{{ route('signature.create') }}"
                class="font-semibold underline"
            >
                Unggah sekarang
            </a>
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">No Tiket</th>
                        <th class="px-6 py-3">Nomor Surat</th>
                        <th class="px-6 py-3">Jenis Surat</th>
                        <th class="px-6 py-3">Pemohon</th>
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
                                {{ $item->nomor_surat ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->jenisSurat?->nama }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->pemohon?->name }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('ttd.preview', $item) }}"
                                        target="_blank"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                    >
                                        Preview
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('ttd.sign', $item) }}"
                                        onsubmit="return confirm('Tanda tangani surat ini?')"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="text-xs font-semibold text-purple-600 hover:text-purple-700"
                                        >
                                            Tanda Tangani
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada surat yang menunggu tanda tangan.
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
@endsection