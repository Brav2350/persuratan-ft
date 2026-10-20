@extends('layouts.admin')

@section('title', 'Editor Template')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Editor Template: {{ $jenisSurat->nama }}
                </h2>
                <p class="text-sm text-gray-500">
                    Edit isi surat langsung dari browser. Perubahan tersimpan tanpa mengubah kode aplikasi.
                </p>
            </div>

            <a
                href="{{ route('jenis-surat.template.preview', $jenisSurat) }}"
                target="_blank"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Preview PDF
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Editor --}}
            <div class="xl:col-span-2">
                <form
                    method="POST"
                    action="{{ route('jenis-surat.template.update', $jenisSurat) }}"
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm"
                >
                    @csrf

                    <label class="block text-sm font-medium text-gray-700">
                        Kode Template HTML
                    </label>

                    <textarea
                        name="template_html"
                        rows="30"
                        class="mt-2 w-full rounded-md border-gray-300 font-mono text-xs leading-relaxed focus:border-blue-500 focus:ring-blue-500"
                    >{{ $content }}</textarea>

                    @error('template_html')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 flex justify-end gap-3">
                        <a
                            href="{{ route('jenis-surat.template', $jenisSurat) }}"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                        >
                            Simpan Template
                        </button>
                    </div>
                </form>
            </div>

            {{-- Daftar Placeholder --}}
            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Placeholder Tersedia
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

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($unknownPlaceholders as $placeholder)
                                <span class="rounded bg-yellow-100 px-2 py-1 font-mono text-xs text-yellow-900">
                                    {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Tips
                    </h3>
                    <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-gray-600">
                        <li>Data per surat gunakan placeholder field.</li>
                        <li>Tanda tangan digital: letakkan placeholder signature di blok pejabat yang menandatangani.</li>
                        <li>Teks kebijakan (menimbang, mengingat, ketentuan) tulis langsung di template agar mudah direvisi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection