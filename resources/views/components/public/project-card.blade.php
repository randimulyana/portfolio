{{--
    Komponen: x-public.project-card
    Props: $project (App\Models\Project)
--}}
@props(['project'])

<article class="card-hover group flex flex-col overflow-hidden">

    {{-- Thumbnail --}}
    <div class="aspect-video overflow-hidden bg-mist-100 relative">
        <img
            src="{{ $project->thumbnail_url }}"
            alt="{{ $project->title }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        >
        {{-- Status badge untuk admin preview --}}
        @if (! $project->isPublished())
            <span class="absolute top-3 left-3 badge badge-amber">Draft</span>
        @endif
    </div>

    {{-- Konten --}}
    <div class="p-5 flex flex-col flex-1">

        {{-- Tags tech stack --}}
        @if ($project->tech_stack)
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                    <span class="badge-forest text-xs">{{ $tech }}</span>
                @endforeach
                @if (count($project->tech_stack) > 4)
                    <span class="badge bg-mist-50 text-sage-500 text-xs">+{{ count($project->tech_stack) - 4 }}</span>
                @endif
            </div>
        @endif

        {{-- Judul --}}
        <h3 class="font-display text-lg text-forest-800 leading-snug mb-2 group-hover:text-forest-600 transition-colors">
            <a href="{{ route('projects.show', $project->slug) }}" class="stretched-link">
                {{ $project->title }}
            </a>
        </h3>

        {{-- Deskripsi singkat --}}
        <p class="text-sm text-sage-600 leading-relaxed line-clamp-2 flex-1">
            {{ $project->short_description }}
        </p>

        {{-- Footer card: link --}}
        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-mist-100">
            @if ($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener"
                   class="btn-primary text-xs py-1.5 px-3 relative z-10"
                   @click.stop>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Live
                </a>
            @endif
            @if ($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
                   class="btn-secondary text-xs py-1.5 px-3 relative z-10"
                   @click.stop>
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    GitHub
                </a>
            @endif
            <span class="ml-auto text-xs text-sage-400 group-hover:text-forest-500 transition-colors font-medium">
                Lihat detail →
            </span>
        </div>
    </div>
</article>
