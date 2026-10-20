@extends('layouts.admin')

@section('title', 'Template Surat')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Template Surat
            </h2>
            <p class="text-sm text-gray-500">
                {{ $jenisSurat->nama }} - Kode: {{ $jenisSurat->kode }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Upload Template HTML
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
    Template berupa HTML. Gunakan placeholder seperti
    <span class="font-mono">@{{nama}}</span>,
    <span class="font-mono">@{{nomor_surat}}</span>,
    <span class="font-mono">@{{signature}}</span>,
    atau
    <span class="font-mono">@{{qr}}</span>.
</p>

                    <form
                        method="POST"
                        action="{{ route('jenis-surat.template.store', $jenisSurat) }}"
                        enctype="multipart/form-data"
                        class="mt-6 space-y-6"
                    >
                        @csrf

                        <div>
                            <label
                                for="template"
                                class="block text-sm font-medium text-gray-700"
                            >
                                File Template HTML
                            </label>

                            <input
                                type="file"
                                id="template"
                                name="template"
                                accept=".html,.htm"
                                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-800"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                Format: HTML atau HTM. Maksimal 1 MB.
                            </p>

                            @error('template')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Posisi Tanda Tangan X
                                </label>

                                <input
                                    type="number"
                                    name="ttd_x"
                                    value="{{ old('ttd_x', $jenisSurat->ttd_x) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                @error('ttd_x')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Posisi Tanda Tangan Y
                                </label>

                                <input
                                    type="number"
                                    name="ttd_y"
                                    value="{{ old('ttd_y', $jenisSurat->ttd_y) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                @error('ttd_y')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Posisi QR X
                                </label>

                                <input
                                    type="number"
                                    name="qr_x"
                                    value="{{ old('qr_x', $jenisSurat->qr_x) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                @error('qr_x')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Posisi QR Y
                                </label>

                                <input
                                    type="number"
                                    name="qr_y"
                                    value="{{ old('qr_y', $jenisSurat->qr_y) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                >

                                @error('qr_y')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                @if ($jenisSurat->template_path)
                                    Template saat ini: <span class="font-medium text-green-700">Tersimpan</span>
                                @else
                                    Template saat ini: <span class="font-medium text-gray-700">Default sistem</span>
                                @endif
                            </div>

                            <button
                                type="submit"
                                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                            >
                                Simpan Template & Posisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Placeholder Sistem
                    </h3>

                   <div class="mt-4 flex flex-wrap gap-2">
    @foreach ($knownPlaceholders as $placeholder)
        <span class="rounded bg-gray-100 px-2 py-1 font-mono text-xs text-gray-700">
            {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
        </span>
    @endforeach
</div>
                </div>

                @if (count($unknownPlaceholders))
                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-6 shadow-sm">
                        <h3 class="text-sm font-semibold text-yellow-900">
                            Placeholder Tidak Dikenali
                        </h3>

                        <p class="mt-2 text-sm text-yellow-800">
                            Placeholder berikut ada di template tetapi tidak tersedia saat render:
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
    @foreach ($knownPlaceholders as $placeholder)
        <span class="rounded bg-gray-100 px-2 py-1 font-mono text-xs text-gray-700">
            {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
        </span>
    @endforeach
</div>
                    </div>
                @endif

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Aksi
                    </h3>
                    <a
    href="{{ route('jenis-surat.template.edit', $jenisSurat) }}"
    class="block w-full rounded-md bg-gray-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-gray-800"
>
    Edit Template (Online)
</a>
                    <div class="mt-4 space-y-3">
                        <a
                            href="{{ route('jenis-surat.template.preview', $jenisSurat) }}"
                            target="_blank"
                            class="block w-full rounded-md bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700"
                        >
                            Preview Template PDF
                        </a>

                        @if ($jenisSurat->template_path)
                            <form
                                method="POST"
                                action="{{ route('jenis-surat.template.destroy', $jenisSurat) }}"
                                onsubmit="return confirm('Hapus template ini? Sistem akan kembali menggunakan template default.')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="w-full rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                                >
                                    Hapus Template
                                </button>
                            </form>
                        @endif

                        <a
                            href="{{ route('jenis-surat.index') }}"
                            class="block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection