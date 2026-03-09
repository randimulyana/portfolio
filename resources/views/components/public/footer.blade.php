<footer class="bg-forest-800 text-mist-200 mt-24">
    <div class="container-main py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-7 h-7 rounded-lg bg-forest-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-5 12 3-6 3-8 3-8z"/>
                        </svg>
                    </span>
                    <span class="font-display text-white text-base">{{ config('app.name') }}</span>
                </div>
                <p class="text-sm text-sage-300 leading-relaxed">
                    Belajar, membangun, dan terus tumbuh.<br>
                    Satu commit setiap hari.
                </p>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-xs font-medium uppercase tracking-widest text-sage-400 mb-3">Navigasi</h4>
                <ul class="space-y-1.5">
                    @foreach ([
                        ['route' => 'home',          'label' => 'Home'],
                        ['route' => 'projects.index', 'label' => 'Proyek'],
                        ['route' => 'blog.index',     'label' => 'Blog'],
                        ['route' => 'about',          'label' => 'About'],
                        ['route' => 'contact',        'label' => 'Contact'],
                    ] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="text-sm text-sage-300 hover:text-white transition-colors">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Social --}}
            <div>
                <h4 class="text-xs font-medium uppercase tracking-widest text-sage-400 mb-3">Connect</h4>
                <div class="flex gap-3">
                    {{-- GitHub --}}
                    <a href="https://github.com" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-forest-700 hover:bg-forest-600 flex items-center justify-center transition-colors"
                       aria-label="GitHub">
                        <svg class="w-4 h-4 text-sage-200" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                        </svg>
                    </a>
                    {{-- LinkedIn --}}
                    <a href="https://linkedin.com" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-forest-700 hover:bg-forest-600 flex items-center justify-center transition-colors"
                       aria-label="LinkedIn">
                        <svg class="w-4 h-4 text-sage-200" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="divider border-forest-700 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-sage-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Built with Laravel {{ app()->version() }}.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-forest-600 hover:text-sage-400 transition-colors">
                Admin
            </a>
        </div>
    </div>
</footer>
