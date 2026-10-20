@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Tambah User
            </h2>
            <p class="text-sm text-gray-500">
                Buat pengguna baru dan tetapkan role.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('users.store') }}"
        >
            @csrf

            @include('users._form')

            <div class="mt-6 flex justify-end gap-3">
                <a
                    href="{{ route('users.index') }}"
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