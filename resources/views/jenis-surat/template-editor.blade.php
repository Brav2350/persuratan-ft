@extends('layouts.admin')

@section('title', 'Editor Template')

@section('content')
    <div class="mx-auto max-w-6xl">
        {{-- Header Page --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                    Editor Template: {{ $jenisSurat->nama }}
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Edit isi surat langsung dari browser. Perubahan tersimpan tanpa mengubah kode aplikasi.
                </p>
            </div>

            <a
                href="{{ route('jenis-surat.template.preview', $jenisSurat) }}"
                target="_blank"
                class="inline-flex items-center justify-center rounded-xl bg-[#517085] px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
            >
                Preview PDF
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Form Code Editor --}}
            <div class="xl:col-span-2">
                <form
                    method="POST"
                    action="{{ route('jenis-surat.template.update', $jenisSurat) }}"
                    class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm"
                >
                    @csrf

                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Kode Template HTML
                    </label>

                    <textarea
                        name="template_html"
                        rows="28"
                        class="w-full rounded-xl border-slate-200 bg-slate-900 text-slate-100 font-mono text-xs p-4 leading-relaxed focus:border-[#517085] focus:ring-[#517085]"
                    >{{ $content }}</textarea>

                    @error('template_html')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="{{ route('jenis-surat.template', $jenisSurat) }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                        >
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                        >
                            Simpan Template
                        </button>
                    </div>
                </form>
            </div>

            {{-- Sidebar Placeholders & Tips --}}
            <div class="space-y-6">
                {{-- Placeholder Sistem --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                        Placeholder Tersedia
                    </h3>

                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($knownPlaceholders as $placeholder)
                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-[11px] text-slate-700 border border-slate-200/60">
                                {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Placeholder Tidak Dikenali --}}
                @if (count($unknownPlaceholders))
                    <div class="rounded-2xl border border-amber-200/80 bg-amber-50/80 p-6 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">
                            Placeholder Tidak Dikenali
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($unknownPlaceholders as $placeholder)
                                <span class="rounded-lg bg-amber-100 px-2.5 py-1 font-mono text-[11px] text-amber-800 border border-amber-200">
                                    {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tips --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tips Penggunaan
                    </h3>
                    <ul class="mt-3 list-inside list-disc space-y-2 text-xs text-slate-500 leading-relaxed">
                        <li>Gunakan placeholder untuk data dinamis per surat.</li>
                        <li>Tanda tangan digital: letakkan placeholder signature di blok pejabat terkait.</li>
                        <li>Teks kebijakan ditulis langsung di dalam template HTML agar mudah disesuaikan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection