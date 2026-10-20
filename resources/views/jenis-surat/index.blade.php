@extends('layouts.admin')

@section('title', 'Jenis Surat')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Master Jenis Surat
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Kelola master jenis surat dan template kustom.
            </p>
        </div>

        <a
            href="{{ route('jenis-surat.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-[#517085] px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
        >
            + Tambah Jenis Surat
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs">
                <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Jumlah Field</th>
                        <th class="px-6 py-4">Template</th>
                        <th class="px-6 py-4">Pengajuan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($jenisSuratList as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $item->nama }}
                            </td>

                            <td class="px-6 py-4 font-mono text-slate-600">
                                {{ $item->kode }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ count($item->fields_json ?? []) }} field
                            </td>

                            <td class="px-6 py-4">
                                @if ($item->template_path)
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                        Tersimpan
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">
                                        Default
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-semibold text-[#517085]">
                                {{ $item->pengajuan_surat_count }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('jenis-surat.edit', $item) }}"
                                        class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition duration-150"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="{{ route('jenis-surat.template', $item) }}"
                                        class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition duration-150"
                                    >
                                        Template
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('jenis-surat.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus jenis surat ini?')"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition duration-150"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada jenis surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jenisSuratList->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $jenisSuratList->links() }}
            </div>
        @endif
    </div>
@endsection