<x-app-layout
    title="About — {{ config('app.name') }}"
    description="Tentang saya — developer Laravel yang gemar belajar dan berbagi."
>

    <div class="container-main py-16">

        {{-- Header --}}
        <div class="max-w-2xl mb-12">
            <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Tentang</p>
            <h1 class="font-display text-4xl text-forest-800 mb-4">Hai, saya <em class="not-italic text-forest-500">{{ config('app.name') }}</em></h1>
            <p class="text-sage-600 text-base leading-relaxed">
                Seorang developer Laravel yang percaya bahwa coding bukan sekadar pekerjaan,
                tapi sebuah perjalanan belajar yang tidak pernah berakhir.
            </p>
        </div>

        <div class="grid lg:grid-cols-[1fr_280px] gap-10 items-start">

            {{-- Konten --}}
            <div class="prose-portfolio">
                <h2>Latar Belakang</h2>
                <p>
                    Saya mulai serius belajar web development dari nol, dengan Laravel sebagai
                    fondasi utama. Portfolio ini sendiri adalah bukti dari proses belajar itu —
                    dibangun dari nol dengan arsitektur yang saya pelajari dan terapkan sendiri.
                </p>

                <h2>Stack Favorit</h2>
                <p>
                    Keseharian saya berkutat dengan Laravel, Livewire, TailwindCSS, dan Alpine.js.
                    Saya percaya bahwa memahami satu stack secara mendalam jauh lebih berharga
                    daripada mengetahui banyak hal secara dangkal.
                </p>

                <h2>Cara Belajar</h2>
                <p>
                    Saya belajar paling efektif dengan cara membangun sesuatu yang nyata.
                    Setiap proyek adalah eksperimen — ada yang berhasil, ada yang gagal,
                    semua mengajarkan sesuatu.
                </p>
            </div>

            {{-- Sidebar Skills --}}
            <aside class="space-y-4">
                <div class="card p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-sage-400 mb-4">Tech Stack</h3>
                    @foreach ([
                        ['label' => 'Laravel 12',   'level' => 80],
                        ['label' => 'Livewire 3',   'level' => 70],
                        ['label' => 'TailwindCSS',  'level' => 85],
                        ['label' => 'Alpine.js',    'level' => 65],
                        ['label' => 'MySQL',        'level' => 70],
                        ['label' => 'Git / GitHub', 'level' => 75],
                    ] as $skill)
                        <div class="mb-3 last:mb-0">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-forest-700">{{ $skill['label'] }}</span>
                                <span class="text-sage-400">{{ $skill['level'] }}%</span>
                            </div>
                            <div class="h-1.5 bg-mist-200 rounded-full overflow-hidden">
                                <div class="h-full bg-forest-400 rounded-full"
                                     style="width: {{ $skill['level'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-sage-400 mb-3">Links</h3>
                    <div class="space-y-2">
                        <a href="https://github.com" target="_blank" rel="noopener"
                           class="flex items-center gap-2 text-sm text-sage-600 hover:text-forest-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                            GitHub
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener"
                           class="flex items-center gap-2 text-sm text-sage-600 hover:text-forest-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="btn-primary w-full justify-center">
                    Hubungi Saya
                </a>
            </aside>
        </div>
    </div>

</x-app-layout>
