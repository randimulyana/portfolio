{{--
    Komponen: x-admin.sidebar
    Desktop: fixed di kiri (w-64)
    Mobile: drawer overlay via Alpine
--}}
<div
    x-data="{ open: false }"
    @toggle-sidebar.window="open = !open"
>
    {{-- Overlay mobile --}}
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 bg-black/40 z-20 lg:hidden"
        style="display:none"
    ></div>

    {{-- Sidebar --}}
    <aside
        class="fixed top-0 left-0 h-full w-64 bg-forest-800 text-white z-30
               flex flex-col transition-transform duration-300
               lg:translate-x-0"
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-forest-700">
            <span class="w-8 h-8 rounded-lg bg-forest-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-5 12 3-6 3-8 3-8z"/>
                </svg>
            </span>
            <div>
                <div class="text-sm font-semibold text-white font-sans">{{ config('app.name') }}</div>
                <div class="text-xs text-sage-400">Admin Panel</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-forest-400 mb-2">Menu</p>

            @php
            $navItems = [
                [
                    'route' => 'admin.dashboard',
                    'label' => 'Dashboard',
                    'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
                [
                    'route' => 'admin.projects.index',
                    'label' => 'Proyek',
                    'icon'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                ],
                [
                    'route' => 'admin.posts.index',
                    'label' => 'Blog',
                    'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                ],
            ];
            @endphp

            @foreach ($navItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all mb-0.5
                           {{ request()->routeIs($item['route'].'*')
                              ? 'bg-forest-600 text-white shadow-soft'
                              : 'text-sage-300 hover:bg-forest-700 hover:text-white' }}"
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Bottom: lihat website --}}
        <div class="px-3 py-4 border-t border-forest-700">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-sage-400 hover:text-white hover:bg-forest-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Website
            </a>
        </div>
    </aside>
</div>
