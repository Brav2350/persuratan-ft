
<x-guest-layout>

    {{-- STATUS SESSION --}}
    <x-auth-session-status
        class="mb-5 rounded-lg text-sm"
        :status="session('status')"
    />

    <form
        method="POST"
        action="{{ route('login') }}"
        x-data="{ showPassword: false, loading: false }"
        @submit="loading = true"
    >
        @csrf

        {{-- EMAIL --}}
        <div>
            <x-input-label
                for="email"
                value="Alamat Email"
                class="mb-2 text-sm font-semibold text-[#344B59]"
            />

            <div class="relative">

                <div class="pointer-events-none absolute inset-y-0 left-0
                            flex items-center pl-3.5 text-[#89A9BC]">
                    <svg class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@universitas.ac.id"
                    class="block w-full rounded-xl border-[#DCE5EB]
                           bg-[#F5F8FA] py-3 pl-11 pr-4 text-sm
                           text-[#243B4A] placeholder:text-slate-400
                           transition duration-200
                           focus:border-[#698FA5] focus:bg-white
                           focus:ring-2 focus:ring-[#89A9BC]/20"
                />

            </div>

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2 text-xs"
            />
        </div>


        {{-- PASSWORD --}}
        <div class="mt-5">

            <div class="mb-2 flex items-center justify-between">

                <x-input-label
                    for="password"
                    value="Password"
                    class="text-sm font-semibold text-[#344B59]"
                />

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-xs font-medium text-[#496A7E]
                               transition hover:text-[#243B4A]
                               hover:underline"
                    >
                        Lupa password?
                    </a>
                @endif

            </div>

            <div class="relative">

                <div class="pointer-events-none absolute inset-y-0 left-0
                            flex items-center pl-3.5 text-[#89A9BC]">

                    <svg class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M7 10V7a5 5 0 0110 0v3m-11 0h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2zm5 4v3"/>
                    </svg>

                </div>

                <x-text-input
                    id="password"
                    ::type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="block w-full rounded-xl border-[#DCE5EB]
                           bg-[#F5F8FA] py-3 pl-11 pr-12 text-sm
                           text-[#243B4A] placeholder:text-slate-400
                           transition duration-200
                           focus:border-[#698FA5] focus:bg-white
                           focus:ring-2 focus:ring-[#89A9BC]/20"
                />

                {{-- TOGGLE PASSWORD --}}
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    :aria-label="showPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'"
                    class="absolute inset-y-0 right-0 flex items-center
                           px-3.5 text-slate-400 transition
                           hover:text-[#243B4A]
                           focus:outline-none focus:text-[#243B4A]"
                >

                    {{-- IKON MATA --}}
                    <svg
                        x-show="!showPassword"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M2.5 12s3.4-7 9.5-7 9.5 7 9.5 7-3.4 7-9.5 7-9.5-7-9.5-7z"/>
                        <circle cx="12" cy="12" r="3"
                                stroke-width="1.7"/>
                    </svg>

                    {{-- IKON MATA TERTUTUP --}}
                    <svg
                        x-show="showPassword"
                        x-cloak
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.2A10.7 10.7 0 0112 5c6.1 0 9.5 7 9.5 7a15 15 0 01-3.1 3.8M6.2 6.2C3.8 7.9 2.5 12 2.5 12s3.4 7 9.5 7a10 10 0 004-.8"/>
                    </svg>

                </button>

            </div>

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2 text-xs"
            />

        </div>


        {{-- INGAT SAYA --}}
        <div class="mt-5">

            <label
                for="remember_me"
                class="inline-flex cursor-pointer select-none
                       items-center gap-2.5"
            >

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    value="1"
                    @checked(old('remember'))
                    class="h-4 w-4 rounded border-slate-300
                           text-[#243B4A] shadow-sm
                           focus:ring-2 focus:ring-[#89A9BC]/30"
                />

                <span class="text-sm text-slate-600">
                    Ingat saya
                </span>

            </label>

        </div>


        {{-- TOMBOL LOGIN --}}
        <div class="mt-7">

            <button
                type="submit"
                :disabled="loading"
                class="group flex w-full items-center justify-center
                       gap-2 rounded-xl bg-[#243B4A] px-5 py-3.5
                       text-sm font-semibold text-white
                       shadow-lg shadow-[#243B4A]/10
                       transition duration-200
                       hover:-translate-y-0.5 hover:bg-[#304F63]
                       hover:shadow-xl hover:shadow-[#243B4A]/15
                       active:translate-y-0
                       disabled:cursor-wait disabled:opacity-70"
            >

                <span
                    x-text="loading ? 'Memproses...' : 'Masuk ke Sistem'"
                >
                    Masuk ke Sistem
                </span>

                {{-- IKON PANAH --}}
                <svg
                    x-show="!loading"
                    class="h-4 w-4 transition-transform
                           group-hover:translate-x-1"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M5 12h14m-6-6l6 6-6 6"/>
                </svg>

                {{-- IKON LOADING --}}
                <svg
                    x-show="loading"
                    x-cloak
                    class="h-4 w-4 animate-spin"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="3"
                        class="opacity-25"
                    />

                    <path
                        fill="currentColor"
                        class="opacity-75"
                        d="M21 12a9 9 0 00-9-9v3a6 6 0 016 6h3z"
                    />
                </svg>

            </button>

        </div>


        {{-- INFORMASI KEAMANAN --}}
        <div class="mt-6 flex items-start gap-3 rounded-xl
                    border border-[#DFE8ED] bg-[#F5F8FA] p-3.5">

            <svg
                class="mt-0.5 h-4 w-4 shrink-0 text-[#496A7E]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.7"
                      d="M12 3l8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6l8-3z"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.7"
                      d="M9 12l2 2 4-4"/>
            </svg>

            <p class="text-xs leading-5 text-slate-500">
                Gunakan akun yang telah terdaftar. Jaga kerahasiaan
                informasi login Anda untuk menjaga keamanan dokumen
                dan administrasi persuratan.
            </p>

        </div>

    </form>

</x-guest-layout>
