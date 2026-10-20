<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Persuratan FT') }} - @yield('title', 'Dashboard')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">
    @php
        $user = auth()->user();
    @endphp

    <div class="min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 bg-gray-900 text-gray-100 md:block">
            <div class="flex h-16 items-center border-b border-gray-800 px-6">
                <div>
                    <p class="text-sm font-semibold leading-tight">Persuratan FT</p>
                    <p class="text-xs text-gray-400">Fakultas Teknik</p>
                </div>
            </div>

            <nav class="mt-4 space-y-1 px-3 text-sm">
                {{-- Dashboard (Semua Role) --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    Dashboard
                </a>

                {{-- Pengajuan Surat (Admin Fakultas & Super Admin) --}}
                @if ($user && $user->hasAnyRole(['Admin Fakultas', 'Super Admin']))
                    <a href="{{ route('pengajuan.index') }}" 
                       class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('pengajuan.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Pengajuan Surat
                    </a>
                @endif

                {{-- Nomor & Verifikasi (BAAK & Super Admin) --}}
                @if ($user && $user->hasAnyRole(['BAAK', 'Super Admin']))
                    <a href="{{ route('baak.antrian') }}" 
                       class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('baak.*', 'verifikasi.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Nomor & Verifikasi
                    </a>
                @endif

                {{-- Tanda Tangan (Penandatangan & Super Admin) --}}
                @if ($user && $user->hasAnyRole(['Penandatangan', 'Super Admin']))
                    <a href="{{ route('ttd.antrian') }}" 
                       class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('ttd.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Tanda Tangan
                    </a>
                @endif

                {{-- Laporan (Admin Fakultas, BAAK, Super Admin) --}}
                @if ($user && $user->hasAnyRole(['Admin Fakultas', 'BAAK', 'Super Admin']))
                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center rounded-md px-3 py-2 {{ request()->routeIs('laporan.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Laporan
                    </a>
                @endif

                {{-- Master Data (Hanya Super Admin) --}}
                @if ($user && $user->hasRole('Super Admin'))
                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Master Data</p>

                        <a href="{{ route('jenis-surat.index') }}" 
                           class="mt-1 flex items-center rounded-md px-3 py-2 {{ request()->routeIs('jenis-surat.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            Jenis Surat
                        </a>

                        <a href="{{ route('users.index') }}" 
                           class="mt-1 flex items-center rounded-md px-3 py-2 {{ request()->routeIs('users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            Pengguna
                        </a>
                    </div>
                @endif
            </nav>
        </aside>

        {{-- Main content --}}
        <div class="flex min-h-screen flex-col md:pl-64">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                    <div>
                        <h1 class="text-sm font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('notifications.index') }}" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                            Notifikasi ({{ $user ? $user->unreadNotifications->count() : 0 }})
                        </a>

                        <a href="#" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ $user?->name ?? 'Guest' }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6">
                @include('partials.flash')
                @yield('content')
            </main>

            <footer class="border-t border-gray-200 bg-white px-6 py-4 text-xs text-gray-500">
                Sistem Persuratan Fakultas Teknik - {{ now()->year }}
            </footer>
        </div>
    </div>
</body>
</html>