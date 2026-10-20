@extends('layouts.admin')

@section('title', 'Pengajuan Surat')

@section('content')
    <!-- Header Page & Tombol Buat Baru -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Daftar Pengajuan Surat
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Kelola pengajuan surat sesuai role dan status.
            </p>
        </div>

        @if (auth()->user()->hasAnyRole(['Admin Fakultas', 'Super Admin']))
            <a
                href="{{ route('pengajuan.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-[#517085] px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
            >
                + Buat Pengajuan Baru
            </a>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('pengajuan.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-64">
                <label class="mb-1.5 block text-xs font-semibold text-slate-600">
                    Filter Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                >
                    <option value="">Semua Status</option>

                    @foreach (\App\Enums\StatusPengajuan::cases() as $status)
                        <option
                            value="{{ $status->value }}"
                            @selected(request('status') === $status->value)
                        >
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-[#517085] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] transition duration-150"
                >
                    Filter
                </button>

                <a
                    href="{{ route('pengajuan.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Pengajuan Surat -->
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs">
                <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No Tiket</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($pengajuanList as $item)
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

                            <td class="px-6 py-4 font-mono text-slate-600">
                                {{ $item->nomor_surat ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @include('partials.status-badge', ['status' => $item->status])
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('pengajuan.show', $item) }}"
                                        class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition duration-150"
                                    >
                                        Lihat
                                    </a>

                                    @can('update', $item)
                                        <a
                                            href="{{ route('pengajuan.edit', $item) }}"
                                            class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition duration-150"
                                        >
                                            Edit
                                        </a>
                                    @endcan

                                    @can('submit', $item)
                                        <form method="POST" action="{{ route('pengajuan.submit', $item) }}" class="inline">
                                            @csrf
                                            <button
                                                type="submit"
                                                onclick="return confirm('Ajukan surat ini ke BAAK?')"
                                                class="rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition duration-150"
                                            >
                                                Ajukan
                                            </button>
                                        </form>
                                    @endcan

                                    @can('delete', $item)
                                        <form method="POST" action="{{ route('pengajuan.destroy', $item) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                onclick="return confirm('Hapus draft ini?')"
                                                class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition duration-150"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Belum ada data pengajuan surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuanList->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $pengajuanList->links() }}
            </div>
        @endif
    </div>
@endsection