<x-app-layout title="{{ config('app.name') }} — Laravel Developer">

    {{-- ─── HERO ──────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-forest-800 via-forest-700 to-forest-600 text-white">
        {{-- Noise texture overlay --}}
        <div class="absolute inset-0 opacity-[0.04]" style="background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E')"></div>

        {{-- Dekorasi bulat --}}
        <div class="absolute top-[-80px] right-[-80px] w-80 h-80 rounded-full bg-forest-500/30 blur-3xl"></div>
        <div class="absolute bottom-[-60px] left-[-60px] w-64 h-64 rounded-full bg-sage-500/20 blur-3xl"></div>

        <div class="container-main relative py-24 md:py-32">
            <div class="max-w-2xl animate-fade-up">
                {{-- Keterangan kecil --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-medium text-mist-200 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Tersedia untuk kolaborasi
                </div>

                <h1 class="font-display text-4xl md:text-5xl lg:text-6xl leading-[1.1] mb-5 text-white">
                    Membangun web<br>
                    <em class="text-mist-200 not-italic">dengan penuh niat.</em>
                </h1>

                <p class="text-base md:text-lg text-mist-300 leading-relaxed mb-8 max-w-lg">
                    Seorang Laravel developer yang percaya bahwa kode yang baik
                    lahir dari rasa ingin tahu yang tak pernah berhenti.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('projects.index') }}" class="btn-primary">
                        Lihat Proyek
                    </a>
                    <a href="{{ route('blog.index') }}" class="btn-secondary bg-white/10 border-white/20 text-white hover:bg-white/20">
                        Baca Blog
                    </a>
                </div>
            </div>
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 inset-x-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0 60L1440 60L1440 20C1200 60 960 0 720 20C480 40 240 0 0 20L0 60Z" fill="#F5F7F2"/>
            </svg>
        </div>
    </section>

    {{-- ─── FEATURED PROJECTS ──────────────────────────────────────── --}}
    <section class="container-main py-20">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Portfolio</p>
                <h2 class="font-display text-3xl text-forest-800">Proyek Unggulan</h2>
            </div>
            <a href="{{ route('projects.index') }}"
               class="text-sm text-forest-500 hover:text-forest-700 font-medium transition-colors hidden sm:inline-flex items-center gap-1">
                Semua proyek
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if ($featuredProjects->isEmpty())
            <div class="text-center py-16 text-sage-400">
                <p>Belum ada proyek yang ditampilkan.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($featuredProjects as $project)
                    <x-public.project-card :project="$project" class="animate-fade-up" style="animation-delay: {{ $loop->index * 75 }}ms" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- ─── DIVIDER ─────────────────────────────────────────────────── --}}
    <div class="container-main"><div class="divider"></div></div>

    {{-- ─── RECENT POSTS ────────────────────────────────────────────── --}}
    <section class="container-main py-20">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Tulisan</p>
                <h2 class="font-display text-3xl text-forest-800">Dari Blog</h2>
            </div>
            <a href="{{ route('blog.index') }}"
               class="text-sm text-forest-500 hover:text-forest-700 font-medium transition-colors hidden sm:inline-flex items-center gap-1">
                Semua tulisan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if ($recentPosts->isEmpty())
            <div class="text-center py-16 text-sage-400">
                <p>Belum ada artikel.</p>
            </div>
        @else
            {{-- Artikel pertama: featured (besar) --}}
            @if ($recentPosts->count() >= 1)
                <div class="mb-5">
                    <x-public.post-card :post="$recentPosts->first()" :featured="true" />
                </div>
            @endif

            {{-- Sisanya: card kecil --}}
            @if ($recentPosts->count() > 1)
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ($recentPosts->skip(1) as $post)
                        <x-public.post-card :post="$post" />
                    @endforeach
                </div>
            @endif
        @endif
    </section>

    {{-- ─── CTA SECTION ────────────────────────────────────────────── --}}
    <section class="bg-forest-800 text-white">
        <div class="container-main py-16 text-center">
            <h2 class="font-display text-3xl md:text-4xl mb-4">Ada proyek yang ingin dibangun?</h2>
            <p class="text-mist-300 mb-8 max-w-md mx-auto text-sm leading-relaxed">
                Saya terbuka untuk diskusi, kolaborasi, atau sekadar ngobrol soal teknologi.
            </p>
            <a href="{{ route('contact') }}" class="btn-primary">
                Hubungi Saya
            </a>
        </div>
    </section>

</x-app-layout>
