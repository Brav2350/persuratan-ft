@extends('layouts.admin')

@section('title', 'Antrian Tanda Tangan')

@section('content')
    <!-- Header Page -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">
            Antrian Tanda Tangan
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Daftar surat yang menunggu tanda tangan digital.
        </p>
    </div>

    {{-- Peringatan Belum Upload TTD --}}
    @if (! auth()->user()->signature_path)
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-200/80 bg-amber-50/80 p-4 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="text-xs">
                <p class="font-bold text-amber-800">
                    Tanda Tangan Digital Belum Diunggah
                </p>
                <p class="mt-0.5 text-amber-700">
                    Anda belum memiliki spesimen tanda tangan. 
                    <a href="{{ route('signature.create') }}" class="font-semibold underline hover:text-amber-900">
                        Unggah sekarang
                    </a>
                </p>
            </div>
        </div>
    @endif

    {{-- Tabel Antrian TTD --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs">
                <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No Tiket</th>
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($antrian as $item)
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

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('view', $item)
                                        <a
                                            href="{{ route('pengajuan.show', $item) }}"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition duration-150"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Surat
                                        </a>
                                    @endcan

                                    @can('sign', $item)
                                        <a
                                            href="{{ route('ttd.sign-form', $item) }}"
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-[#517085] px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Proses TTD
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada surat yang menunggu tanda tangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($antrian->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $antrian->links() }}
            </div>
        @endif
    </div>
@endsection