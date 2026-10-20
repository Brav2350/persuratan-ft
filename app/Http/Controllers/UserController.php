<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Services\OtpService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()
            ->orderBy('name')
            ->pluck('name', 'name');

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
{
    $validated = $this->validateUser($request);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'nip' => $validated['nip'] ?? null,
        'nim' => $validated['nim'] ?? null,
        'is_active' => $request->boolean('is_active', true),
        // Password kosong = akun pending aktivasi via OTP
        'password' => filled($validated['password'] ?? null) ? Hash::make($validated['password']) : null,
        'email_verified_at' => filled($validated['password'] ?? null) ? now() : null,
    ]);

    $user->assignRole($validated['role']);

    if (blank($validated['password'] ?? null)) {
        app(OtpService::class)->generate($user->email, 'activation');

        return redirect()->route('users.index')
            ->with('success', 'User dibuat. OTP aktivasi telah dikirim ke email user.');
    }

    return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
}


    public function edit(User $user)
    {
        $roles = Role::query()
            ->orderBy('name')
            ->pluck('name', 'name');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
{
    $validated = $this->validateUser($request, $user);

    // Cegah Super Admin menurunkan role dirinya sendiri
    if ($user->id === $request->user()->id && $validated['role'] !== 'Super Admin') {
        return back()->withErrors(['role' => 'Anda tidak dapat mengubah role diri sendiri.']);
    }

    $user->fill([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'nip' => $validated['nip'] ?? null,
        'nim' => $validated['nim'] ?? null,
        'is_active' => $request->boolean('is_active', true),
    ]);

    if (filled($validated['password'] ?? null)) {
        $user->password = Hash::make($validated['password']);
        $user->email_verified_at = $user->email_verified_at ?? now();
    }

    $user->save();
    $user->syncRoles([$validated['role']]);

    return redirect()->route('users.index')->with('success', 'User diperbarui.');
}

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->pengajuanSurat()->exists() || $user->penandatangananSurat()->exists()) {
            return back()->with('error', 'User tidak dapat dihapus karena memiliki riwayat pengajuan.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
    protected function validateUser(Request $request, ?User $user = null): array
{
    $domain = config('surat.email_domain');

    return $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => [
            'required', 'string', 'lowercase', 'email', 'max:150',
            'regex:/^[^@]+@'.preg_quote($domain, '/').'$/i',
            Rule::unique('users', 'email')->ignore($user?->id),
        ],
        'nip' => ['nullable', 'string', 'max:30'],
        'nim' => ['nullable', 'string', 'max:20', Rule::unique('users', 'nim')->ignore($user?->id)],
        'role' => ['required', Rule::in(['Super Admin', 'Admin Fakultas', 'BAAK', 'Penandatangan', 'Dosen', 'Mahasiswa'])],
        'password' => ['nullable', 'confirmed', Password::defaults()],
        'is_active' => ['nullable', 'boolean'],
    ], [
        'email.regex' => 'Email wajib berakhiran @'.$domain.'.',
    ]);
}
public function resendOtp(User $user, OtpService $otp)
{
    abort_if($user->password !== null, 422, 'Akun sudah aktif, OTP tidak diperlukan.');

    $otp->generate($user->email, 'activation');

    return back()->with('success', 'OTP aktivasi dikirim ulang ke '.$user->email);
}
}