@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Manajemen User
            </h2>
            <p class="text-sm text-gray-500">
                Kelola pengguna dan role sistem.
            </p>
        </div>

        <a
            href="{{ route('users.create') }}"
            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
        >
            Tambah User
        </a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">NIP</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $user->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $user->nip ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $user->roles->pluck('name')->implode(', ') }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($user->is_active)
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                    >
                                        Edit
                                    </a>

                                    @if (! auth()->user()->is($user))
                                        <form
                                            method="POST"
                                            action="{{ route('users.destroy', $user) }}"
                                            onsubmit="return confirm('Hapus user ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-red-600 hover:text-red-700"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection