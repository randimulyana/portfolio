<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ($title ?? 'Dashboard') . ' — Admin Panel' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

{{-- Body admin: sidebar fixed di kiri, konten di kanan --}}
<body class="bg-forest-50 text-forest-800 antialiased">

<div class="min-h-screen flex">

    {{-- ─── Sidebar (desktop: fixed, mobile: drawer via Alpine) ── --}}
    <x-admin.sidebar />

    {{-- ─── Main area ──────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        {{-- Top bar --}}
        <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-mist-200 px-6 py-3 flex items-center justify-between gap-4">
            {{-- Mobile menu toggle --}}
            <button
                x-data
                @click="$dispatch('toggle-sidebar')"
                class="lg:hidden btn-ghost p-2"
                aria-label="Buka menu"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb / Page title --}}
            <div class="flex-1">
                {{ $breadcrumb ?? '' }}
            </div>

            {{-- User menu --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 text-sm font-medium text-forest-700 hover:text-forest-900 transition-colors"
                >
                    <span class="w-8 h-8 rounded-full bg-forest-700 text-white flex items-center justify-center text-xs font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                    <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-44 card shadow-card py-1 z-20"
                >
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-forest-600 hover:bg-mist-50 hover:text-forest-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Website
                    </a>
                    <div class="divider my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @endif
        @if (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        {{-- Page content --}}
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>