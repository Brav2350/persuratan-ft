@extends('layouts.admin')

@section('title', 'Template Surat')

@section('content')
    <div class="mx-auto max-w-6xl">
        {{-- Header Page --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Template Surat
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                <span class="font-semibold text-[#517085]">{{ $jenisSurat->nama }}</span> — Kode: <span class="font-mono text-slate-700">{{ $jenisSurat->kode }}</span>
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            {{-- Main Section Upload & Settings --}}
            <div class="xl:col-span-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                        Upload Template HTML
                    </h3>

                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                        Template berupa HTML. Gunakan placeholder seperti
                        <span class="font-mono text-slate-700 font-semibold">@{{nama}}</span>,
                        <span class="font-mono text-slate-700 font-semibold">@{{nomor_surat}}</span>,
                        <span class="font-mono text-slate-700 font-semibold">@{{signature}}</span>, atau
                        <span class="font-mono text-slate-700 font-semibold">@{{qr}}</span>.
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
                                class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2"
                            >
                                File Template HTML
                            </label>

                            <input
                                type="file"
                                id="template"
                                name="template"
                                accept=".html,.htm"
                                class="w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-[#517085] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-[#425d70] transition duration-150"
                            >

                            <p class="mt-2 text-[11px] text-slate-400">
                                Format: HTML atau HTM. Maksimal 1 MB.
                            </p>

                            @error('template')
                                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Posisi TTD dan QR --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 pt-2 border-t border-slate-100">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Posisi Tanda Tangan X
                                </label>

                                <input
                                    type="number"
                                    name="ttd_x"
                                    value="{{ old('ttd_x', $jenisSurat->ttd_x) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                                >

                                @error('ttd_x')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Posisi Tanda Tangan Y
                                </label>

                                <input
                                    type="number"
                                    name="ttd_y"
                                    value="{{ old('ttd_y', $jenisSurat->ttd_y) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                                >

                                @error('ttd_y')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Posisi QR X
                                </label>

                                <input
                                    type="number"
                                    name="qr_x"
                                    value="{{ old('qr_x', $jenisSurat->qr_x) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                                >

                                @error('qr_x')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Posisi QR Y
                                </label>

                                <input
                                    type="number"
                                    name="qr_y"
                                    value="{{ old('qr_y', $jenisSurat->qr_y) }}"
                                    min="0"
                                    max="2000"
                                    required
                                    class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                                >

                                @error('qr_y')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="text-xs text-slate-500">
                                Template saat ini: 
                                @if ($jenisSurat->template_path)
                                    <span class="font-bold text-emerald-700">Tersimpan</span>
                                @else
                                    <span class="font-semibold text-slate-600">Default sistem</span>
                                @endif
                            </div>

                            <button
                                type="submit"
                                class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                            >
                                Simpan Template & Posisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar Placeholders & Actions --}}
            <div class="space-y-6">
                {{-- Placeholder Informasi --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                        Placeholder Sistem
                    </h3>

                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($knownPlaceholders as $placeholder)
                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-[11px] text-slate-700 border border-slate-200/60">
                                {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                @if (count($unknownPlaceholders))
                    <div class="rounded-2xl border border-amber-200/80 bg-amber-50/80 p-6 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">
                            Placeholder Tidak Dikenali
                        </h3>

                        <p class="mt-1.5 text-xs text-amber-700">
                            Placeholder berikut ada di template tetapi tidak tersedia saat render:
                        </p>

                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($unknownPlaceholders as $placeholder)
                                <span class="rounded-lg bg-amber-100 px-2.5 py-1 font-mono text-[11px] text-amber-800 border border-amber-200">
                                    {{ chr(123) . chr(123) . $placeholder . chr(125) . chr(125) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Action Card --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">
                        Aksi Template
                    </h3>
                    
                    <div class="space-y-3">
                        <a
                            href="{{ route('jenis-surat.template.edit', $jenisSurat) }}"
                            class="block w-full rounded-xl bg-[#517085] px-4 py-2.5 text-center text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] transition duration-150"
                        >
                            Edit Template (Online)
                        </a>

                        <a
                            href="{{ route('jenis-surat.template.preview', $jenisSurat) }}"
                            target="_blank"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
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
                                    class="w-full rounded-xl bg-rose-50 border border-rose-200 px-4 py-2.5 text-center text-xs font-semibold text-rose-700 hover:bg-rose-100 transition duration-150"
                                >
                                    Hapus Template
                                </button>
                            </form>
                        @endif

                        <a
                            href="{{ route('jenis-surat.index') }}"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-xs font-semibold text-slate-500 hover:bg-slate-50 transition duration-150"
                        >
                            Kembali ke Master Surat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection