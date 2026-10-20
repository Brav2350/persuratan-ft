@php
    $user = $user ?? null;
    $roles = ['Super Admin', 'Admin Fakultas', 'BAAK', 'Penandatangan', 'Dosen', 'Mahasiswa'];
@endphp

<div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        {{-- Nama --}}
        <div class="md:col-span-2">
            <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Nama Lengkap <span class="text-rose-600">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user?->name) }}"
                required
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
            @error('name')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Email <span class="text-rose-600">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user?->email) }}"
                required
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
            <p class="mt-1 text-[11px] text-slate-400">
                Wajib berakhiran @{{ config('surat.email_domain') }}
            </p>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Role --}}
        <div>
            <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Role <span class="text-rose-600">*</span>
            </label>
            <select
                id="role"
                name="role"
                required
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
                @foreach ($roles as $role)
                    <option
                        value="{{ $role }}"
                        @selected(old('role', $user?->roles->first()?->name) === $role)
                    >
                        {{ $role }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- NIP --}}
        <div>
            <label for="nip" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                NIP / NUPTK (Staff/Dosen)
            </label>
            <input
                type="text"
                id="nip"
                name="nip"
                value="{{ old('nip', $user?->nip) }}"
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
            @error('nip')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- NIM --}}
        <div>
            <label for="nim" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                NIM (Mahasiswa)
            </label>
            <input
                type="text"
                id="nim"
                name="nim"
                value="{{ old('nim', $user?->nim) }}"
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
            @error('nim')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Password
            </label>
            <input
                type="password"
                id="password"
                name="password"
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
            <p class="mt-1 text-[11px] text-slate-400">
                Kosongkan untuk mengirim OTP aktivasi ke email user (disarankan untuk Dosen).
            </p>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Konfirmasi Password
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="w-full rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-800 shadow-sm focus:border-[#517085] focus:ring-[#517085]"
            >
        </div>

        {{-- Status Aktif --}}
        <div class="md:col-span-2 pt-2">
            <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                <input
                    type="hidden"
                    name="is_active"
                    value="0"
                >
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $user?->is_active ?? true))
                    class="rounded border-slate-300 text-[#517085] focus:ring-[#517085]"
                >
                Akun Aktif
            </label>
        </div>
    </div>
</div>