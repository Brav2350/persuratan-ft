@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Dashboard & Filter Tahun -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Dashboard Persuratan
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Statistik pengajuan surat tahun {{ $tahun }}.
            </p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
            <label class="text-xs font-semibold text-slate-600">Tahun:</label>
            <select
                name="tahun"
                onchange="this.form.submit()"
                class="rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
                @foreach (range(now()->year, now()->subYears(3)->year) as $item)
                    <option
                        value="{{ $item }}"
                        @selected($item == $tahun)
                    >
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Grid Cards Statistik Ringkasan -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Card Total Pengajuan -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengajuan</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $statistikStatus->sum() }}
            </p>
        </div>

        @foreach ($statistikStatus as $label => $total)
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $total }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Grafik Bulanan & Jenis Surat Terbanyak -->
    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <!-- Grafik Pengajuan Bulanan -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm xl:col-span-2">
            <div class="mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Grafik Pengajuan Bulanan
                </h3>
            </div>

            <div class="mt-4">
                <canvas id="chartBulanan" height="110"></canvas>
            </div>
        </div>

        <!-- Jenis Surat Terbanyak -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Jenis Surat Terbanyak
                </h3>
            </div>

            <div class="mt-4 space-y-2.5">
                @forelse ($statistikJenis as $nama => $total)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3">
                        <p class="text-xs font-medium text-slate-700">{{ $nama }}</p>
                        <span class="rounded-lg bg-[#517085]/10 px-2.5 py-1 text-xs font-bold text-[#517085]">
                            {{ $total }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">
                        Belum ada data.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabel Pengajuan Terbaru -->
    <div class="mt-8 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                Pengajuan Terbaru
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs">
                <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No Tiket</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($recent as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                            <td class="px-6 py-4 font-bold text-[#517085]">
                                {{ $item->no_tiket }}
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

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('pengajuan.show', $item) }}"
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-[#517085] hover:text-[#425d70]"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada pengajuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartBulanan');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($bulanLabels),
                    datasets: [
                        {
                            label: 'Jumlah Pengajuan',
                            data: @json(array_values($bulananData)),
                            backgroundColor: '#517085',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection