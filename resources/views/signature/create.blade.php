@extends('layouts.admin')

@section('title', 'Tanda Tangan Digital')

@section('content')
    <div class="mx-auto max-w-lg">
        {{-- Header Page --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Tanda Tangan Digital
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Unggah gambar tanda tangan Anda. Disarankan PNG transparan.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
            @if (auth()->user()->signature_path)
                <div class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 p-4 text-xs font-medium text-emerald-800 leading-relaxed shadow-sm">
                    Tanda tangan digital sudah tersimpan. Jika Anda mengunggah file baru, file lama akan diganti.
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('signature.store') }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                <div>
                    <label
                        for="signature"
                        class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2"
                    >
                        File Tanda Tangan
                    </label>

                    <input
                        type="file"
                        id="signature"
                        name="signature"
                        accept=".png,.jpg,.jpeg"
                        required
                        class="w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-[#517085] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-[#425d70] transition duration-150"
                    >

                    <p class="mt-2 text-[11px] text-slate-400">
                        Format: PNG, JPG, atau JPEG. Maksimal 1 MB.
                    </p>

                    @error('signature')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full sm:w-auto rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                    >
                        Simpan Tanda Tangan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection