@php
    $user = $user ?? null;
@endphp

<div class="space-y-6">
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">
            Data User
        </h3>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Nama <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user?->name) }}"
                    required
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Email <span class="text-red-500">*</span>
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user?->email) }}"
                    required
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    NIP
                </label>

                <input
                    type="text"
                    name="nip"
                    value="{{ old('nip', $user?->nip) }}"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('nip')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Role <span class="text-red-500">*</span>
                </label>

                <select
                    name="role"
                    required
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">Pilih Role</option>

                    @foreach ($roles as $role)
                        <option
                            value="{{ $role }}"
                            @selected(old('role', $user?->roles?->first()?->name) === $role)
                        >
                            {{ $role }}
                        </option>
                    @endforeach
                </select>

                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">
            Password
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            @if ($user)
                Kosongkan jika password tidak ingin diubah.
            @else
                Password minimal 8 karakter.
            @endif
        </p>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="mt-2 w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">
            Status User
        </h3>

        <label class="mt-4 flex items-center gap-3">
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
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >

            <span class="text-sm text-gray-700">
                User aktif dan dapat login
            </span>
        </label>

        @error('is_active')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>