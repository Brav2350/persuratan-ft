<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Services\OtpService;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
public function store(Request $request, OtpService $otp): RedirectResponse
    {
        $domain = config('surat.email_domain');

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'nim' => ['required', 'string', 'min:6', 'max:20', Rule::unique('users', 'nim')],
        'email' => [
            'required', 'string', 'lowercase', 'email', 'max:150',
            'regex:/^[^@]+@'.preg_quote($domain, '/').'$/i',
            Rule::unique('users', 'email'),
        ],
        'password' => ['required', 'confirmed', Password::defaults()],
    ], [
        'email.regex' => 'Registrasi hanya menerima email kampus berakhiran @'.$domain.'.',
        'nim.unique' => 'NIM sudah terdaftar.',
        'email.unique' => 'Email sudah terdaftar.',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'nim' => $validated['nim'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'is_active' => true,
        'email_verified_at' => null,
    ]);

    // Role dikunci di kode, tidak pernah diambil dari request
    $user->assignRole('Mahasiswa');

    Auth::login($user);

    $otp->generate($user->email, 'registration');

    return redirect()->route('verification.otp');
    }
}