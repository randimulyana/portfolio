<x-app-layout
    :title="$post->title . ' — ' . config('app.name')"
    :description="$post->excerpt_text"
    :ogImage="$post->thumbnail_url"
>

    <article class="container-main py-12">
        <div class="max-w-3xl mx-auto">

            {{-- Back --}}
            <div class="mb-8">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-sage-500 hover:text-forest-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Blog
                </a>
            </div>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if ($post->category)
                    <span class="badge-rain">{{ $post->category->name }}</span>
                @endif
                <span class="text-xs text-sage-400">{{ $post->reading_time }} menit baca</span>
                <time class="text-xs text-sage-400" datetime="{{ $post->published_at?->toDateString() }}">
                    {{ $post->published_at?->translatedFormat('d F Y') }}
                </time>
                <span class="text-xs text-sage-400">·</span>
                <span class="text-xs text-sage-400">{{ number_format($post->views) }} views</span>
            </div>

            {{-- Judul --}}
            <h1 class="font-display text-3xl md:text-4xl lg:text-5xl text-forest-800 leading-[1.15] mb-6">
                {{ $post->title }}
            </h1>

            {{-- Thumbnail --}}
            <div class="aspect-video rounded-xl2 overflow-hidden bg-mist-100 mb-10 shadow-card">
                <img
                    src="{{ $post->thumbnail_url }}"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover"
                >
            </div>

            {{-- Konten --}}
            <div class="prose-portfolio">
                {!! $post->content !!}
            </div>

            {{-- Tags --}}
            @if ($post->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mt-10 pt-8 border-t border-mist-200">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                           class="badge-forest hover:bg-forest-200 transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </article>

    {{-- ─── Related Articles ───────────────────────────────────────── --}}
    @if ($related->isNotEmpty())
        <div class="bg-mist-50 border-t border-mist-200 py-12">
            <div class="container-main">
                <h2 class="font-display text-2xl text-forest-800 mb-6">Artikel Terkait</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ($related as $item)
                        <x-public.post-card :post="$item" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
