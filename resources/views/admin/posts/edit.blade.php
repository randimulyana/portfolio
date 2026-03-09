<x-admin-layout :title="'Edit: ' . $post->title">
    <x-slot name="breadcrumb">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.posts.index') }}" class="text-sage-400 hover:text-forest-700">Blog</a>
            <span class="text-sage-300">/</span>
            <span class="font-medium text-forest-700 truncate max-w-[240px]">{{ $post->title }}</span>
        </nav>
    </x-slot>

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="font-display text-2xl text-forest-800">Edit Artikel</h1>
            @if ($post->published_at)
                <p class="text-xs text-sage-400 mt-1">
                    Dipublish {{ $post->published_at->translatedFormat('d M Y, H:i') }}
                    · {{ number_format($post->views) }} views
                    · {{ $post->reading_time }} menit baca
                </p>
            @endif
        </div>
        @if ($post->isPublished())
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
               class="btn-ghost text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat di website
            </a>
        @endif
    </div>

    @include('admin.posts._form', [
        'post'       => $post,
        'categories' => $categories,
        'action'     => route('admin.posts.update', $post),
        'method'     => 'PUT',
    ])
</x-admin-layout>
