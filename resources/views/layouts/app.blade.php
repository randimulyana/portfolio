<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ─── SEO Meta ─────────────────────────────────────────────── --}}
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Portfolio seorang developer Laravel yang terus belajar dan berkembang.' }}">
    <meta name="robots" content="index, follow">

    {{-- Open Graph --}}
    <meta property="og:title"       content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    @isset($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endisset

    {{-- ─── Assets ──────────────────────────────────────────────── --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ─── Livewire Styles ─────────────────────────────────────── --}}
    @livewireStyles

    {{-- ─── Slot head tambahan (per-halaman) ───────────────────── --}}
    {{ $head ?? '' }}
</head>

<body class="bg-cream text-forest-800 antialiased">

    {{-- Navbar --}}
    <x-public.navbar />

    {{-- Flash message --}}
    @if (session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif

    {{-- Main content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-public.footer />

    @livewireScripts
</body>
</html>