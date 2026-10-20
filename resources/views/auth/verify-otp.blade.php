<x-guest-layout>
    <div class="mb-4 text-sm text-neutral-600">
        Masukkan 6 digit kode OTP yang kami kirim ke
        <span class="font-semibold">{{ auth()->user()->email }}</span>.
        Kode berlaku {{ config('surat.otp_expires_minutes') }} menit.
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-success-border bg-success-background px-4 py-2 text-sm text-success-text">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.otp.verify') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="code" value="Kode OTP" />
            <x-text-input
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                maxlength="6"
                class="mt-1 block w-full text-center text-lg tracking-[6px]"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Verifikasi
        </x-primary-button>
    </form>

    <form method="POST" action="{{ route('verification.otp.resend') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-primary-base underline hover:text-primary-hover">
            Kirim ulang kode OTP
        </button>
    </form>
</x-guest-layout>