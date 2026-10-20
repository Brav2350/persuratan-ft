<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nim" value="NIM" />
            <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full" :value="old('nim')" required />
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email Kampus" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
            <p class="mt-1 text-xs text-neutral-500">
                Wajib berakhiran @{{ config('surat.email_domain') }}
            </p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <a class="text-sm text-primary-base underline hover:text-primary-hover" href="{{ route('login') }}">
                Sudah punya akun?
            </a>
            <a class="text-sm text-primary-base underline hover:text-primary-hover" href="{{ route('activation.form') }}">
                Aktivasi Akun Dosen
            </a>
        </div>

        <x-primary-button class="w-full justify-center">
            Daftar & Kirim Kode OTP
        </x-primary-button>
    </form>
</x-guest-layout>