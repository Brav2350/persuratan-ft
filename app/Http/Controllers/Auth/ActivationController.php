<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ActivationController extends Controller
{
    public function show()
    {
        return view('auth.activation');
    }

    public function requestOtp(Request $request, OtpService $otp)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($validated['email']);

        $user = User::where('email', $email)->whereNull('password')->first();

        if ($user) {
            $otp->generate($email, 'activation');
        }

        // Pesan generik untuk mencegah enumerasi akun
        return back()->with([
            'status' => 'Jika akun menunggu aktivasi terdaftar, kode OTP telah dikirim.',
            'activation_email' => $user ? $email : null,
        ]);
    }

    public function activate(Request $request, OtpService $otp)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = strtolower($validated['email']);

        $user = User::where('email', $email)->whereNull('password')->first();

        if (! $user || ! $otp->verify($email, 'activation', $validated['code'])) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP salah atau kedaluwarsa.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ])->save();

        return redirect()->route('login')->with('success', 'Akun aktif. Silakan login.');
    }
}