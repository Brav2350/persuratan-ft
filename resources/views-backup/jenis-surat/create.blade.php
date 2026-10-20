@extends('layouts.admin')

@section('title', 'Tambah Jenis Surat')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Tambah Jenis Surat
            </h2>
            <p class="text-sm text-gray-500">
                Buat jenis surat baru beserta field form dinamis.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('jenis-surat.store') }}"
        >
            @csrf

            @include('jenis-surat._form')

            <div class="mt-6 flex justify-end gap-3">
                <a
                    href="{{ route('jenis-surat.index') }}"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection