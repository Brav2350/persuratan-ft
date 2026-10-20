@extends('layouts.admin')

@section('title', 'Proses Tanda Tangan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <!-- Header Page -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Proses Tanda Tangan
            </h2>
            <p class="mt-1 text-xs font-semibold text-[#517085]">
                No Tiket: {{ $pengajuan->no_tiket }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            <form
                method="POST"
                action="{{ route('ttd.sign', $pengajuan) }}"
                class="space-y-6"
            >
                @csrf

                {{-- Card Ringkasan Informasi Surat --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Informasi Surat</h3>
                    <dl class="mt-3 space-y-2 text-xs">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Jenis Surat</dt>
                            <dd class="font-semibold text-slate-800">{{ $pengajuan->jenisSurat?->nama }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pemohon</dt>
                            <dd class="font-semibold text-slate-800">{{ $pengajuan->pemohon?->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Nomor Surat</dt>
                            <dd class="font-mono font-semibold text-slate-800">{{ $pengajuan->nomor_surat ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Preview PDF --}}
                <div>
                    <a
                        href="{{ route('ttd.preview', $pengajuan) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview Dokumen PDF
                    </a>
                </div>

                {{-- Pilihan Mode Tanda Tangan --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Mode Tanda Tangan <span class="text-rose-600">*</span>
                    </label>

                    <div class="space-y-3">
                        {{-- Mode Digital --}}
                        <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50/80 transition duration-150">
                            <input
                                type="radio"
                                name="mode_ttd"
                                value="digital"
                                {{ old('mode_ttd', 'digital') === 'digital' ? 'checked' : '' }}
                                {{ ! $hasSignature ? 'disabled' : '' }}
                                class="mt-0.5 text-[#517085] focus:ring-[#517085]"
                            >
                            <div class="flex-1 text-xs">
                                <span class="font-bold text-slate-800">
                                    Tanda Tangan Digital
                                </span>
                                <p class="mt-1 text-slate-500 leading-relaxed">
                                    PDF akan menampilkan gambar tanda tangan Anda beserta QR code verifikasi keabsahan dokumen.
                                </p>
                                @if (! $hasSignature)
                                    <p class="mt-2 font-semibold text-rose-600">
                                        ⚠️ Anda belum mengunggah tanda tangan digital.
                                        <a href="{{ route('signature.create') }}" class="underline hover:text-rose-700" target="_blank">
                                            Upload sekarang
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </label>

                        {{-- Mode Manual --}}
                        <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50/80 transition duration-150">
                            <input
                                type="radio"
                                name="mode_ttd"
                                value="manual"
                                {{ old('mode_ttd') === 'manual' ? 'checked' : '' }}
                                class="mt-0.5 text-[#517085] focus:ring-[#517085]"
                            >
                            <div class="flex-1 text-xs">
                                <span class="font-bold text-slate-800">
                                    Konfirmasi Manual (Tanpa Gambar TTD)
                                </span>
                                <p class="mt-1 text-slate-500 leading-relaxed">
                                    PDF hanya menampilkan QR code verifikasi. Tanda tangan basah dapat dilakukan secara fisik setelah dokumen dicetak.
                                </p>
                            </div>
                        </label>
                    </div>

                    @error('mode_ttd')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Form Input Tanggal TTD --}}
                <div>
                    <label for="tanggal_ttd" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Tanda Tangan <span class="text-rose-600">*</span>
                    </label>

                    <input
                        type="date"
                        id="tanggal_ttd"
                        name="tanggal_ttd"
                        value="{{ old('tanggal_ttd', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
                    >

                    <p class="mt-1.5 text-[11px] text-slate-500">
                        Default: Hari ini. Dapat disesuaikan kebutuhan (maksimal hari ini).
                    </p>

                    @error('tanggal_ttd')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('ttd.antrian') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                    >
                        Proses & Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection