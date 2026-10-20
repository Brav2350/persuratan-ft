<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Persuratan') }} — @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">

    @php
        // Dosen & Mahasiswa pakai navbar; sisanya (staff/admin) pakai sidebar
        $isNavbarMode = auth()->user()->hasAnyRole(['Dosen', 'Mahasiswa']);
    @endphp

    @if ($isNavbarMode)
        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- MODE NAVBAR (Dosen & Mahasiswa)                        --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col">

            {{-- NAVBAR --}}
            <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">

                        {{-- KIRI: Logo + Hamburger --}}
                        <div class="flex items-center gap-3">
                            <button
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 lg:hidden"
                                aria-label="Toggle menu"
                            >
                                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('images/logo-header-removebg-preview.png') }}" alt="Logo TSU" class="h-9 w-auto object-contain">
                                <div class="hidden sm:block">
                                    <p class="text-sm font-bold text-slate-900 leading-tight">Persuratan FT</p>
                                    <p class="text-[11px] text-slate-500 leading-tight">Tiga Serangkai University</p>
                                </div>
                            </a>
                        </div>

                        {{-- TENGAH: Menu Desktop --}}
                        <div class="hidden lg:flex lg:items-center lg:gap-1.5">
                            <a
                                href="{{ route('dashboard') }}"
                                class="rounded-xl px-3.5 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#517085] text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
                            >
                                Dashboard
                            </a>

                            <a
                                href="{{ route('pengajuan.index') }}"
                                class="rounded-xl px-3.5 py-2 text-xs font-semibold transition-colors {{ request()->routeIs('pengajuan.*') ? 'bg-[#517085] text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}"
                            >
                                Pengajuan Surat
                            </a>
                        </div>

                        {{-- KANAN: Notifikasi + Profil --}}
                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('notifications.index') }}"
                                class="relative inline-flex items-center justify-center rounded-xl p-2 text-slate-600 hover:bg-slate-100 transition-colors"
                                title="Notifikasi"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                                @if ($unread > 0)
                                    <span class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">
                                        {{ $unread > 99 ? '99+' : $unread }}
                                    </span>
                                @endif
                            </a>

                            <div class="relative" x-data="{ open: false }">
                                <button
                                    @click="open = !open"
                                    @click.outside="open = false"
                                    class="flex items-center gap-2 rounded-xl px-2.5 py-1.5 hover:bg-slate-100 transition-colors"
                                >
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#517085]/10 text-xs font-bold text-[#517085]">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                        <p class="text-[10px] font-medium text-slate-500 leading-tight">{{ auth()->user()->roles->first()?->name ?? 'User' }}</p>
                                    </div>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition
                                    x-cloak
                                    class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200/80 bg-white py-1.5 shadow-xl z-50"
                                >
                                    <div class="border-b border-slate-100 px-4 py-2.5">
                                        <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('signature.create') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        Tanda Tangan Digital
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full px-4 py-2 text-left text-xs font-semibold text-red-600 hover:bg-red-50">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Menu Drawer --}}
                    <div
                        x-show="mobileMenuOpen"
                        x-transition
                        x-cloak
                        class="border-t border-slate-200 py-2 lg:hidden space-y-1"
                    >
                        <a
                            href="{{ route('dashboard') }}"
                            class="block rounded-xl px-3.5 py-2 text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-[#517085] text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                        >
                            Dashboard
                        </a>
                        <a
                            href="{{ route('pengajuan.index') }}"
                            class="block rounded-xl px-3.5 py-2 text-xs font-semibold {{ request()->routeIs('pengajuan.*') ? 'bg-[#517085] text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                        >
                            Pengajuan Surat
                        </a>
                    </div>
                </div>
            </nav>

            {{-- MAIN CONTENT (Navbar Mode) --}}
            <main class="flex-1">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-800 shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            {{-- FOOTER --}}
            <footer class="border-t border-slate-200 bg-white py-4">
                <div class="mx-auto max-w-7xl px-4 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} Fakultas Teknik - Universitas Tiga Serangkai. All rights reserved.
                </div>
            </footer>
        </div>

    @else
        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- MODE SIDEBAR (Admin Fakultas, BAAK, Penandatangan, SA)  --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">

            {{-- SIDEBAR SLATE BLUE --}}
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-30 w-64 transform bg-[#517085] text-white transition-transform duration-200 lg:translate-x-0 shadow-lg flex flex-col justify-between"
            >
                <div>
                    <!-- Header Logo Sidebar -->
                    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
                        <img src="{{ asset('images/logo-header-removebg-preview.png') }}" alt="Logo TSU" 
                             class="h-9 w-auto object-contain brightness-0 invert opacity-90 drop-shadow-sm">
                        <div>
                            <p class="text-sm font-bold leading-tight">Persuratan FT</p>
                            <p class="text-[11px] text-white/70 leading-tight">Tiga Serangkai Univ</p>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="mt-4 space-y-1 px-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            Dashboard
                        </a>

                        @role('Admin Fakultas|Dosen|Mahasiswa|Super Admin')
                            <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('pengajuan.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Pengajuan Surat
                            </a>
                        @endrole

                        @role('BAAK|Super Admin')
                            <a href="{{ route('baak.antrian') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('baak.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Nomor & Verifikasi
                            </a>
                        @endrole

                        @role('Penandatangan|Super Admin')
                            <a href="{{ route('ttd.antrian') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('ttd.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Tanda Tangan
                            </a>
                        @endrole

                        @role('Super Admin|Admin Fakultas|BAAK')
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('laporan.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Laporan
                            </a>
                        @endrole

                        @role('Super Admin')
                            <p class="px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/50">Master Data</p>
                            <a href="{{ route('jenis-surat.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('jenis-surat.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Jenis Surat
                            </a>
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                Manajemen User
                            </a>
                        @endrole
                    </nav>
                </div>

                <!-- Footer Sidebar -->
                <div class="p-4 border-t border-white/10 text-center">
                    <p class="text-[10px] text-white/60">&copy; {{ date('Y') }} FT TSU</p>
                </div>
            </aside>

            {{-- MAIN AREA --}}
            <div class="flex flex-1 flex-col lg:pl-64">
                {{-- TOP BAR --}}
                <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200/80 bg-white px-4 sm:px-6 shadow-sm">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden rounded-lg p-2 text-slate-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="ml-auto flex items-center gap-3">
                        <a href="{{ route('notifications.index') }}" class="relative rounded-xl p-2 text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                            @if ($unread > 0)
                                <span class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">
                                    {{ $unread > 99 ? '99+' : $unread }}
                                </span>
                            @endif
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-slate-100 transition-colors">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#517085]/10 text-xs font-bold text-[#517085]">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden text-xs font-semibold text-slate-800 md:inline">{{ auth()->user()->name }}</span>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200/80 bg-white py-1.5 shadow-xl z-50">
                                <div class="border-b border-slate-100 px-4 py-2.5">
                                    <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('signature.create') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Tanda Tangan Digital</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-xs font-semibold text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- CONTENT --}}
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-800 shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @endif

</body>
</html>