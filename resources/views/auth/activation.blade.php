<x-guest-layout>
    @if (session('activation_email'))
        {{-- Langkah 2: masukkan OTP + buat password --}}
        <div class="mb-4 text-sm text-neutral-600">
            Masukkan kode OTP yang dikirim ke
            <span class="font-semibold">{{ session('activation_email') }}</span>
            lalu buat password akun Anda.
        </div>

        <form method="POST" action="{{ route('activation.activate') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="email" value="{{ session('activation_email') }}">

            <div>
                <x-input-label for="code" value="Kode OTP" />
                <x-text-input id="code" name="code" type="text" inputmode="numeric" maxlength="6"
                    class="mt-1 block w-full text-center text-lg tracking-[6px]" required autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Password Baru" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
            </div>

            <x-primary-button class="w-full justify-center">
                Aktifkan Akun
            </x-primary-button>
        </form>
    @else
        {{-- Langkah 1: minta OTP --}}
        <div class="mb-4 text-sm text-neutral-600">
            Halaman ini khusus akun Dosen yang dibuat oleh admin.
            Masukkan email kampus Anda untuk menerima kode aktivasi.
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-md border border-success-border bg-success-background px-4 py-2 text-sm text-success-text">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('activation.otp') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Email Kampus" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center">
                Kirim Kode Aktivasi
            </x-primary-button>
        </form>
    @endif

    <div class="mt-4 text-center">
        <a class="text-sm text-primary-base underline hover:text-primary-hover" href="{{ route('login') }}">
            Kembali ke Login
        </a>
    </div>
</x-guest-layout>