<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-56 bg-gray-900 flex flex-col flex-shrink-0">
            {{-- Logo --}}
            <div class="h-14 flex items-center gap-3 px-5 border-b border-gray-700">
                <div
                    class="w-7 h-7 bg-emerald-400 rounded-lg flex items-center justify-center text-gray-900 text-xs font-bold">
                    TL</div>
                <span class="text-white font-bold text-sm">TechOps <span class="text-emerald-400">Log</span></span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-2">Utama</p>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('dashboard') ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('activity.create') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('activity.create') ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="M9 12l2 2 4-4" />
                    </svg>
                    Input Kegiatan
                </a>

                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mt-4 mb-2">Data</p>

                <a href="{{ route('activity.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('activity.index') ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                    Riwayat Kegiatan
                </a>

                <a href="{{ route('kb.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('kb.*') ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M12 2a7 7 0 017 7c0 3.5-2 5.5-2 7H7c0-1.5-2-3.5-2-7a7 7 0 017-7z" />
                        <path d="M9 21h6M12 21v-5" />
                    </svg>
                    Knowledge Base
                </a>

                <a href="{{ route('report.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('report.*') ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Laporan
                </a>
            </nav>

            {{-- User info --}}
            <div class="p-3 border-t border-gray-700">
                <div class="flex items-center gap-2.5 px-2 py-2">
                    <div
                        class="w-7 h-7 rounded-full bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-xs font-bold text-emerald-400">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-400 transition" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            <header class="h-14 bg-white border-b border-gray-200 flex items-center px-6 gap-4 flex-shrink-0">
                <h1 class="flex-1 text-sm font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <span class="text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</span>
                <a href="{{ route('activity.create') }}"
                    class="flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Input Kegiatan
                </a>
            </header>

            {{-- Flash Message --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mx-6 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
