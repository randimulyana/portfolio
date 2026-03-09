{{--
    Komponen: x-public.post-card
    Props: $post (App\Models\Post)
    Variant: 'featured' (besar, 2 kolom) atau default (kecil)
--}}
@props(['post', 'featured' => false])

@if ($featured)
{{-- ─── Featured: layout 2 kolom horizontal ────────────────────── --}}
<article class="card-hover group grid md:grid-cols-2 overflow-hidden">
    <div class="aspect-video md:aspect-auto overflow-hidden bg-mist-100">
        <img
            src="{{ $post->thumbnail_url }}"
            alt="{{ $post->title }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        >
    </div>
    <div class="p-6 md:p-8 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-3">
            @if ($post->category)
                <span class="badge-rain">{{ $post->category->name }}</span>
            @endif
            <span class="text-xs text-sage-400">{{ $post->reading_time }} menit baca</span>
        </div>
        <h2 class="font-display text-2xl text-forest-800 leading-snug mb-3 group-hover:text-forest-600 transition-colors">
            <a href="{{ route('blog.show', $post->slug) }}" class="stretched-link">
                {{ $post->title }}
            </a>
        </h2>
        <p class="text-sm text-sage-600 leading-relaxed line-clamp-3 mb-4">
            {{ $post->excerpt_text }}
        </p>
        <div class="flex items-center gap-3 text-xs text-sage-400">
            <time datetime="{{ $post->published_at?->toDateString() }}">
                {{ $post->published_at?->translatedFormat('d M Y') }}
            </time>
            <span>·</span>
            <span>{{ number_format($post->views) }} views</span>
        </div>
    </div>
</article>

@else
{{-- ─── Default card: vertikal ──────────────────────────────────── --}}
<article class="card-hover group flex gap-4 p-4">
    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-mist-100 flex-shrink-0">
        <img
            src="{{ $post->thumbnail_url }}"
            alt="{{ $post->title }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        >
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1.5">
            @if ($post->category)
                <span class="badge-rain text-xs">{{ $post->category->name }}</span>
            @endif
            <span class="text-xs text-sage-400">{{ $post->reading_time }} mnt</span>
        </div>
        <h3 class="font-display text-base text-forest-800 leading-snug mb-1 group-hover:text-forest-600 transition-colors line-clamp-2">
            <a href="{{ route('blog.show', $post->slug) }}" class="stretched-link">
                {{ $post->title }}
            </a>
        </h3>
        <time class="text-xs text-sage-400" datetime="{{ $post->published_at?->toDateString() }}">
            {{ $post->published_at?->diffForHumans() }}
        </time>
    </div>
</article>
@endif
