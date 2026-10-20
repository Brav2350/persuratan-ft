<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate(string $email, string $purpose): void
    {
        // Batalkan OTP lama yang belum dipakai
        EmailOtp::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'email' => strtolower($email),
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes((int) config('surat.otp_expires_minutes', 10)),
        ]);

        Mail::to($email)->send(new OtpMail($code, $purpose));
    }

    public function verify(string $email, string $purpose, string $code): bool
    {
        $otp = EmailOtp::where('email', strtolower($email))
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (! $otp || $otp->isExpired()) {
            return false;
        }

        if ($otp->attempts >= (int) config('surat.otp_max_attempts', 5)) {
            $otp->update(['consumed_at' => now()]); // bakar OTP setelah percobaan habis

            return false;
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            return false;
        }

        $otp->update(['consumed_at' => now()]);

        return true;
    }
}