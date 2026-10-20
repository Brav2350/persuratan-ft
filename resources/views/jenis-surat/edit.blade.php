@extends('layouts.admin')

@section('title', 'Edit Jenis Surat')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                Edit Jenis Surat
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Perbarui data jenis surat dan field form dinamis.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('jenis-surat.update', $jenisSurat) }}"
        >
            @csrf
            @method('PUT')

            @include('jenis-surat._form', ['jenisSurat' => $jenisSurat])

            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-200/80">
                <a
                    href="{{ route('jenis-surat.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition duration-150"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-[#517085] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection