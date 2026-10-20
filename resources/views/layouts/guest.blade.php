
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Persuratan FT') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap"
          rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .campus-background {
            background-image:
                linear-gradient(
                    180deg,
                    rgba(65, 105, 128, 0.18) 0%,
                    rgba(36, 59, 74, 0.68) 52%,
                    rgba(27, 47, 61, 0.96) 100%
                ),
                url('{{ asset('images/tsu.jpg') }}');

            background-size: cover;
            background-position: center;
        }

        .auth-card {
            animation: fade-up 0.65s ease-out both;
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-card {
                animation: none;
            }
        }
    </style>
</head>

<body class="bg-white font-sans antialiased">

    <main class="min-h-screen lg:grid lg:grid-cols-2">

        {{-- PANEL FOTO KAMPUS --}}
        <section
            class="campus-background relative flex min-h-[260px] flex-col
                   justify-between overflow-hidden px-6 py-7
                   sm:min-h-[340px] sm:px-10 sm:py-9
                   lg:min-h-screen lg:px-12 lg:py-11
                   xl:px-16 xl:py-14">

            {{-- LOGO DAN IDENTITAS KAMPUS --}}
            <div class="relative z-10 flex items-center gap-3">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center
                            rounded-xl border border-white/20 bg-white/10
                            p-1.5 backdrop-blur-sm">

                    <img
                        src="{{ asset('images/logo-header-removebg-preview.png') }}"
                        alt="Logo Fakultas Teknik TSU"
                        class="h-full w-full object-contain brightness-0 invert">
                </div>

                <div class="text-white">
                    <p class="text-sm font-bold tracking-wide sm:text-base">
                        TIGA SERANGKAI
                    </p>

                    <p class="text-[10px] tracking-[0.2em] text-white/75 sm:text-xs">
                        UNIVERSITY
                    </p>
                </div>
            </div>

            {{-- INFORMASI UTAMA --}}
            <div class="relative z-10 mt-10 max-w-xl py-5 lg:mt-0 lg:py-0">

                <div class="mb-5 flex items-center gap-3">
                    <span class="h-px w-10 bg-[#B7D0DE]"></span>

                    <span class="text-xs font-semibold uppercase
                                 tracking-[0.22em] text-[#D4E5EE]">
                        Fakultas Teknik
                    </span>
                </div>

                <h1 class="max-w-lg text-3xl font-bold leading-tight
                           tracking-tight text-white
                           sm:text-4xl lg:text-5xl xl:text-6xl">

                    Administrasi
                    <span class="text-[#B7D0DE]">
                        Persuratan
                    </span>
                    yang Lebih Terstruktur.
                </h1>

                <p class="mt-5 max-w-md text-sm leading-7 text-slate-200
                          sm:text-base">
                    Sistem informasi persuratan Fakultas Teknik untuk
                    mendukung pengelolaan surat, proses persetujuan,
                    dan pengarsipan dokumen secara terintegrasi.
                </p>

                {{-- FITUR SISTEM --}}
                <div class="mt-8 flex flex-wrap gap-3">

                    <div class="flex items-center gap-2 rounded-lg
                                border border-white/15 bg-white/10
                                px-3 py-2.5 text-xs text-white
                                backdrop-blur-sm sm:text-sm">

                        <svg class="h-4 w-4 text-[#D4E5EE]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M9 12l2 2 4-4m5-1.5V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2.5c0 5 3.4 8.2 8 10.5 4.6-2.3 8-5.5 8-10.5z"/>
                        </svg>

                        Akses Terlindungi
                    </div>

                    <div class="flex items-center gap-2 rounded-lg
                                border border-white/15 bg-white/10
                                px-3 py-2.5 text-xs text-white
                                backdrop-blur-sm sm:text-sm">

                        <svg class="h-4 w-4 text-[#D4E5EE]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z"/>
                        </svg>

                        Administrasi Digital
                    </div>
                </div>
            </div>

            {{-- FOOTER PANEL FOTO --}}
            <div class="relative z-10 mt-8 border-t border-white/20 pt-5">

                <p class="text-xs leading-5 text-white/70">
                    Sistem Informasi Persuratan
                    <span class="mx-1 text-[#B7D0DE]">/</span>
                    Fakultas Teknik
                </p>

            </div>
        </section>


        {{-- PANEL LOGIN --}}
        <section
            class="flex min-w-0 flex-col justify-center
                   bg-white px-5 py-10
                   sm:px-10 sm:py-12
                   lg:px-12 lg:py-14
                   xl:px-20">

            <div class="auth-card mx-auto w-full max-w-md">

                {{-- HEADER LOGIN --}}
                <div class="mb-8">

                    <div class="mb-7 inline-flex h-12 w-12 items-center
                                justify-center rounded-xl
                                border border-[#DCE6EC] bg-[#F3F6F8]
                                text-[#243B4A]">

                        <svg class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.7"
                                  d="M7 3.75h7l5 5v11.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-15a1 1 0 011-1.5h1z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.7"
                                  d="M14 3.75v5h5M8 13h8M8 17h8"/>
                        </svg>

                    </div>

                    <p class="mb-2 text-xs font-semibold uppercase
                              tracking-[0.2em] text-[#496A7E]">
                        Portal Akademik
                    </p>

                    <h2 class="text-3xl font-bold tracking-tight
                               text-[#243B4A] sm:text-4xl">
                        Selamat datang
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Silakan masuk menggunakan akun Anda untuk
                        mengakses sistem persuratan.
                    </p>

                </div>

                {{-- FORM LOGIN --}}
                <div>
                    {{ $slot }}
                </div>

                {{-- FOOTER LOGIN --}}
                <div class="mt-10 border-t border-[#E0E7EB] pt-5">

                    <p class="text-center text-xs leading-5 text-slate-400">
                        &copy; {{ date('Y') }} Tiga Serangkai University.
                        <br>
                        Fakultas Teknik. Hak cipta dilindungi.
                    </p>

                </div>

            </div>
        </section>

    </main>

</body>
</html>
