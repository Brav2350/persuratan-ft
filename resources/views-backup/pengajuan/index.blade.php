@extends('layouts.admin')

@section('title', 'Pengajuan Surat')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Daftar Pengajuan Surat
            </h2>
            <p class="text-sm text-gray-500">
                Kelola pengajuan surat sesuai role dan status.
            </p>
        </div>

        @if (auth()->user()->hasAnyRole(['Admin Fakultas', 'Super Admin']))
            <a
                href="{{ route('pengajuan.create') }}"
                class="inline-flex items-center justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Buat Pengajuan Baru
            </a>
        @endif
    </div>

    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
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

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Filter
                </button>

                <a
                    href="{{ route('pengajuan.index') }}"
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
                        <th class="px-6 py-3">Jenis Surat</th>
                        <th class="px-6 py-3">Pemohon</th>
                        <th class="px-6 py-3">Nomor Surat</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($pengajuanList as $item)
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

                            <td class="px-6 py-4">
                                {{ $item->nomor_surat ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @include('partials.status-badge', ['status' => $item->status])
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('pengajuan.show', $item) }}"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                    >
                                        Detail
                                    </a>

                                    @can('update', $item)
                                        <form
                                            method="POST"
                                            action="{{ route('pengajuan.submit', $item) }}"
                                            onsubmit="return confirm('Ajukan surat ini untuk diproses?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-green-600 hover:text-green-700"
                                            >
                                                Submit
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data pengajuan surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengajuanList->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $pengajuanList->links() }}
            </div>
        @endif
    </div>
@endsection