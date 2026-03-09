<x-app-layout
    :title="$project->title . ' — ' . config('app.name')"
    :description="$project->short_description"
>

    <div class="container-main py-12">

        {{-- Back + Breadcrumb --}}
        <div class="mb-8">
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-sage-500 hover:text-forest-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Proyek
            </a>
        </div>

        <div class="grid lg:grid-cols-[1fr_280px] gap-10 items-start">

            {{-- ─── Konten Utama ──────────────────────────────────── --}}
            <div>
                {{-- Hero image --}}
                <div class="aspect-video rounded-xl2 overflow-hidden bg-mist-100 mb-8 shadow-card">
                    <img
                        src="{{ $project->thumbnail_url }}"
                        alt="{{ $project->title }}"
                        class="w-full h-full object-cover"
                    >
                </div>

                {{-- Tags + kategori --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if ($project->category)
                        <span class="badge-rain">{{ $project->category->name }}</span>
                    @endif
                    @if ($project->tech_stack)
                        @foreach ($project->tech_stack as $tech)
                            <span class="badge-forest">{{ $tech }}</span>
                        @endforeach
                    @endif
                </div>

                {{-- Judul --}}
                <h1 class="font-display text-3xl md:text-4xl text-forest-800 leading-tight mb-4">
                    {{ $project->title }}
                </h1>

                {{-- Deskripsi panjang --}}
                @if ($project->long_description)
                    <div class="prose-portfolio">
                        {!! $project->long_description !!}
                    </div>
                @else
                    <p class="text-sage-600 leading-relaxed">{{ $project->short_description }}</p>
                @endif

                {{-- Screenshots --}}
                @if ($project->getMedia('screenshots')->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="font-display text-xl text-forest-800 mb-4">Screenshots</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach ($project->getMedia('screenshots') as $screenshot)
                                <img
                                    src="{{ $screenshot->getUrl('thumb') }}"
                                    alt="Screenshot {{ $loop->iteration }}"
                                    loading="lazy"
                                    class="rounded-xl border border-mist-200 shadow-soft hover:shadow-card transition-shadow"
                                >
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─── Sidebar Info ───────────────────────────────────── --}}
            <aside class="lg:sticky lg:top-24 space-y-4">

                {{-- Links --}}
                <div class="card p-5 space-y-3">
                    @if ($project->live_url)
                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener"
                           class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Lihat Demo Live
                        </a>
                    @endif
                    @if ($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
                           class="btn-secondary w-full justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                            GitHub Repo
                        </a>
                    @endif
                </div>

                {{-- Tags Spatie --}}
                @if ($project->tags->isNotEmpty())
                    <div class="card p-5">
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-sage-400 mb-3">Tags</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($project->tags as $tag)
                                <span class="badge-forest">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>

</x-app-layout>
