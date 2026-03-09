<x-app-layout title="Proyek — {{ config('app.name') }}">

    <div class="container-main py-12">

        {{-- Page Header --}}
        <div class="mb-10">
            <p class="text-xs uppercase tracking-widest text-sage-400 font-medium mb-1">Portfolio</p>
            <h1 class="font-display text-4xl text-forest-800">Proyek</h1>
            <p class="mt-2 text-sage-600 text-sm">Kumpulan hal yang pernah saya bangun.</p>
        </div>

        {{-- Filter Kategori --}}
        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <a
                    href="{{ route('projects.index') }}"
                    class="badge text-sm py-1.5 px-4 transition-colors
                           {{ is_null($categorySlug) ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300 hover:text-forest-700' }}"
                >
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <a
                        href="{{ route('projects.index', ['category' => $cat->slug]) }}"
                        class="badge text-sm py-1.5 px-4 transition-colors
                               {{ $categorySlug === $cat->slug ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300 hover:text-forest-700' }}"
                    >
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Grid Proyek --}}
        @if ($projects->isEmpty())
            <div class="text-center py-24">
                <p class="text-sage-400">Belum ada proyek di kategori ini.</p>
                <a href="{{ route('projects.index') }}" class="btn-ghost mt-4">Lihat semua</a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($projects as $project)
                    <x-public.project-card :project="$project" />
                @endforeach
            </div>
        @endif
    </div>

</x-app-layout>
