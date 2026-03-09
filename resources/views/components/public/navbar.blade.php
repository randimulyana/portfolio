{{--
    Komponen: x-public.navbar
    Responsive: desktop nav + mobile drawer via Alpine.js
--}}
<header
    x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 20"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
    :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-soft' : 'bg-transparent'"
>
    <div class="container-main">
        <nav class="flex items-center justify-between h-16">

            {{-- ─── Logo ───────────────────────────────────────────── --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                {{-- Ikon daun kecil --}}
                <span class="w-8 h-8 rounded-lg bg-forest-500 flex items-center justify-center shadow-soft group-hover:bg-forest-600 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-5 12 3-6 3-8 3-8z"/>
                    </svg>
                </span>
                <span class="font-display text-lg text-forest-800 tracking-tight group-hover:text-forest-600 transition-colors">
                    {{ config('app.name') }}
                </span>
            </a>

            {{-- ─── Desktop Links ──────────────────────────────────── --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach ([
                    ['route' => 'home',         'label' => 'Home'],
                    ['route' => 'projects.index','label' => 'Proyek'],
                    ['route' => 'blog.index',    'label' => 'Blog'],
                    ['route' => 'about',         'label' => 'About'],
                ] as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors duration-150
                               {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')
                                  ? 'text-forest-700 bg-mist-100'
                                  : 'text-forest-600 hover:text-forest-800 hover:bg-mist-100' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- ─── CTA + Mobile toggle ───────────────────────────── --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('contact') }}" class="hidden md:inline-flex btn-primary text-xs py-2 px-4">
                    Hire Me
                </a>

                {{-- Hamburger --}}
                <button
                    @click="open = !open"
                    class="md:hidden btn-ghost p-2"
                    :aria-expanded="open"
                    aria-label="Menu"
                >
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    {{-- ─── Mobile Drawer ──────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white/95 backdrop-blur-md border-t border-mist-200 shadow-card"
        style="display: none"
        @click.outside="open = false"
    >
        <div class="container-main py-4 flex flex-col gap-1">
            @foreach ([
                ['route' => 'home',         'label' => 'Home'],
                ['route' => 'projects.index','label' => 'Proyek'],
                ['route' => 'blog.index',    'label' => 'Blog'],
                ['route' => 'about',         'label' => 'About'],
                ['route' => 'contact',       'label' => 'Contact / Hire Me'],
            ] as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @click="open = false"
                    class="px-4 py-2.5 text-sm font-medium rounded-xl transition-colors
                           {{ request()->routeIs($item['route'].'*')
                              ? 'text-forest-700 bg-mist-100'
                              : 'text-forest-600 hover:bg-mist-100' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</header>

{{-- Spacer untuk konten di bawah navbar fixed --}}
<div class="h-16"></div>
