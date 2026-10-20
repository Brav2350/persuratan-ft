<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OtpVerificationController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user()->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request, OtpService $otp)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        if (! $otp->verify($request->user()->email, 'registration', $request->code)) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP salah atau kedaluwarsa.',
            ]);
        }

        $request->user()->forceFill(['email_verified_at' => now()])->save();

        return redirect()->route('dashboard')->with('success', 'Email terverifikasi. Selamat datang!');
    }

    public function resend(Request $request, OtpService $otp)
    {
        $otp->generate($request->user()->email, 'registration');

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}