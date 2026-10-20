@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">
                Edit User
            </h2>
            <p class="text-sm text-gray-500">
                Perbarui data user, role, dan status aktif.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('users.update', $user) }}"
        >
            @csrf
            @method('PUT')

            @include('users._form', ['user' => $user])

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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection