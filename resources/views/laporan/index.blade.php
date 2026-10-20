@extends('layouts.admin')

@section('title', 'Laporan Pengajuan')

@section('content')
    <!-- Header Page & Tombol Export -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Laporan Pengajuan Surat
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Filter berdasarkan tanggal, status, dan jenis surat.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('laporan.export.csv', request()->query()) }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition duration-150"
            >
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>

            <a
                href="{{ route('laporan.export.pdf', request()->query()) }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[#517085] px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
            >
                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Card Form -->
    <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <form
            method="GET"
            action="{{ route('laporan.index') }}"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4"
        >
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Tanggal Dari
                </label>

                <input
                    type="date"
                    name="dari"
                    value="{{ request('dari') }}"
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Tanggal Sampai
                </label>

                <input
                    type="date"
                    name="sampai"
                    value="{{ request('sampai') }}"
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
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
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Jenis Surat
                </label>

                <select
                    name="jenis_surat_id"
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
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

            <div class="flex items-center gap-2 sm:col-span-2 md:col-span-4 pt-2">
                <button
                    type="submit"
                    class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] transition duration-150"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('laporan.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                >
                    Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Data Laporan -->
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs">
                <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No Tiket</th>
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal Dibuat</th>
                        <th class="px-6 py-4">Tanggal TTD</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($laporan as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                            <td class="px-6 py-4 font-bold text-[#517085]">
                                {{ $item->no_tiket }}
                            </td>

                            <td class="px-6 py-4 font-mono text-slate-600">
                                {{ $item->nomor_surat ?? '-' }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $item->jenisSurat?->nama }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $item->pemohon?->name }}
                            </td>

                            <td class="px-6 py-4">
                                @include('partials.status-badge', ['status' => $item->status])
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $item->tanggal_ttd?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada data sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($laporan->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>
@endsection