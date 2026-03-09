{{--
    Custom Pagination View
    Simpan di: resources/views/components/ui/pagination.blade.php

    CATATAN: Untuk menggunakan view custom ini, daftarkan di AppServiceProvider:
        Paginator::defaultView('components.ui.pagination');

    Atau gunakan langsung di blade:
        {{ $posts->links('components.ui.pagination') }}
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman"
         class="flex items-center justify-between gap-4">

        {{-- Info --}}
        <p class="text-sm text-sage-400 hidden sm:block">
            Menampilkan
            <span class="font-medium text-forest-700">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-medium text-forest-700">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-medium text-forest-700">{{ $paginator->total() }}</span>
            data
        </p>

        {{-- Links --}}
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-lg text-sage-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-sage-500
                          hover:bg-mist-100 hover:text-forest-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="w-9 h-9 flex items-center justify-center text-sage-400 text-sm">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg
                                         bg-forest-500 text-white text-sm font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-9 h-9 flex items-center justify-center rounded-lg text-sm
                                      text-sage-600 hover:bg-mist-100 hover:text-forest-700 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-sage-500
                          hover:bg-mist-100 hover:text-forest-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="w-9 h-9 flex items-center justify-center rounded-lg text-sage-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
