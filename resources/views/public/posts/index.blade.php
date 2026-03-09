<x-app-layout title="Blog — {{ config('app.name') }}">

    <div class="container-main py-12">

        {{-- Header --}}
        <div class="mb-10">
            <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Tulisan</p>
            <h1 class="font-display text-4xl text-forest-800">Blog</h1>
            <p class="mt-2 text-sage-600 text-sm">Catatan belajar, tutorial, dan refleksi.</p>
        </div>

        {{-- Filter Tag --}}
        @php $allTags = \Spatie\Tags\Tag::withType('post')->orderBy('name')->get(); @endphp
        @if ($allTags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('blog.index') }}"
                   class="badge text-sm py-1.5 px-4 transition-colors
                          {{ is_null($tagSlug) ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300' }}">
                    Semua
                </a>
                @foreach ($allTags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                       class="badge text-sm py-1.5 px-4 transition-colors
                              {{ $tagSlug === $tag->slug ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300' }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Artikel --}}
        @if ($posts->isEmpty())
            <div class="text-center py-24">
                <p class="text-sage-400">Belum ada artikel.</p>
            </div>
        @else
            {{-- Artikel pertama: featured besar --}}
            @if ($posts->currentPage() === 1)
                <div class="mb-6">
                    <x-public.post-card :post="$posts->first()" :featured="true" />
                </div>
                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    @foreach ($posts->skip(1) as $post)
                        <x-public.post-card :post="$post" />
                    @endforeach
                </div>
            @else
                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    @foreach ($posts as $post)
                        <x-public.post-card :post="$post" />
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
