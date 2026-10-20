@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
    <div>
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                    Manajemen User
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Kelola pengguna sistem dan role mereka.
                </p>
            </div>

            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[#517085] px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#425d70] active:bg-[#354c5c] transition duration-150"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 p-4 text-xs font-bold text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel User --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/80 text-left font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">NIP/NIM</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @foreach ($user->roles as $role)
                                        <span class="inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-[#517085] border border-slate-200/60">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-600">
                                    {{ $user->nip ?? $user->nim ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->is_active)
                                        <span class="inline-block rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500">
                                            Nonaktif
                                        </span>
                                    @endif

                                    @if ($user->email_verified_at === null && $user->password !== null)
                                        <span class="ml-1 inline-block rounded-lg bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                                            Belum Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- ★ BADGE MENUNGGU AKTIVASI (untuk user pending, password null) ★ --}}
                                        @if ($user->password === null)
                                            <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                                                Menunggu Aktivasi
                                            </span>
                                            <form method="POST" action="{{ route('users.resend-otp', $user) }}" class="inline">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-[#517085] underline hover:text-[#425d70]"
                                                >
                                                    Kirim Ulang OTP
                                                </button>
                                            </form>
                                        @endif

                                        <a
                                            href="{{ route('users.edit', $user) }}"
                                            class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition duration-150"
                                        >
                                            Edit
                                        </a>

                                        {{-- Cegah penghapusan diri sendiri dan Super Admin terakhir --}}
                                        @if ($user->id !== auth()->id())
                                            <form
                                                method="POST"
                                                action="{{ route('users.destroy', $user) }}"
                                                onsubmit="return confirm('Yakin hapus user {{ $user->name }}?')"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition duration-150"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-700">Belum ada user</p>
                                        <p class="mt-1 text-xs text-slate-400">Klik "Tambah User" untuk membuat user pertama.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection