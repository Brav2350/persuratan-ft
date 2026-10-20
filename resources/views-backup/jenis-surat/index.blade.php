@extends('layouts.admin')

@section('title', 'Jenis Surat')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Jenis Surat
            </h2>
            <p class="text-sm text-gray-500">
                Master jenis surat dan template.
            </p>
        </div>

        <a
            href="{{ route('jenis-surat.create') }}"
            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
        >
            Tambah Jenis Surat
        </a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Jumlah Field</th>
                        <th class="px-6 py-3">Template</th>
                        <th class="px-6 py-3">Pengajuan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($jenisSuratList as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->nama }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->kode }}
                            </td>

                            <td class="px-6 py-4">
                                {{ count($item->fields_json ?? []) }} field
                            </td>

                            <td class="px-6 py-4">
                                @if ($item->template_path)
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                        Tersimpan
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        Default
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->pengajuan_surat_count }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('jenis-surat.edit', $item) }}"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="{{ route('jenis-surat.template', $item) }}"
                                        class="text-xs font-semibold text-purple-600 hover:text-purple-700"
                                    >
                                        Template
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('jenis-surat.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus jenis surat ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-xs font-semibold text-red-600 hover:text-red-700"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada jenis surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jenisSuratList->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $jenisSuratList->links() }}
            </div>
        @endif
    </div>
@endsection