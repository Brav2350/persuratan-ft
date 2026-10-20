@extends('layouts.admin')

@section('title', 'Laporan Pengajuan')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Laporan Pengajuan Surat
            </h2>
            <p class="text-sm text-gray-500">
                Filter berdasarkan tanggal, status, dan jenis surat.
            </p>
        </div>

        <div class="flex gap-2">
            <a
                href="{{ route('laporan.export.csv', request()->query()) }}"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Export CSV
            </a>

            <a
                href="{{ route('laporan.export.pdf', request()->query()) }}"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Export PDF
            </a>
        </div>
    </div>

    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form
            method="GET"
            action="{{ route('laporan.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal Dari
                </label>

                <input
                    type="date"
                    name="dari"
                    value="{{ request('dari') }}"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal Sampai
                </label>

                <input
                    type="date"
                    name="sampai"
                    value="{{ request('sampai') }}"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Semua Status</option>

                    @foreach ($statuses as $status)
                        <option
                            value="{{ $status->value }}"
                            @selected(request('status') === $status->value)
                        >
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Jenis Surat
                </label>

                <select
                    name="jenis_surat_id"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Semua Jenis</option>

                    @foreach ($jenisSurat as $item)
                        <option
                            value="{{ $item->id }}"
                            @selected(request('jenis_surat_id') == $item->id)
                        >
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 md:col-span-4">
                <button
                    type="submit"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Filter
                </button>

                <a
                    href="{{ route('laporan.index') }}"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">No Tiket</th>
                        <th class="px-6 py-3">Nomor Surat</th>
                        <th class="px-6 py-3">Jenis Surat</th>
                        <th class="px-6 py-3">Pemohon</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal Dibuat</th>
                        <th class="px-6 py-3">Tanggal TTD</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($laporan as $item)
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

                            <td class="px-6 py-4">
                                @include('partials.status-badge', ['status' => $item->status])
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->tanggal_ttd?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($laporan->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>
@endsection