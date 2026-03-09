<x-admin-layout title="Dashboard">
    <x-slot name="breadcrumb">
        <span class="text-sm font-medium text-forest-700">Dashboard</span>
    </x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['label' => 'Total Proyek',    'value' => $stats['total_projects'],     'sub' => $stats['published_projects'].' published',  'color' => 'text-forest-600', 'bg' => 'bg-forest-50'],
            ['label' => 'Total Artikel',   'value' => $stats['total_posts'],        'sub' => $stats['published_posts'].' published',      'color' => 'text-rain-600',   'bg' => 'bg-rain-50'],
        ] as $stat)
            <div class="card p-5">
                <p class="text-xs text-sage-400 font-medium mb-1">{{ $stat['label'] }}</p>
                <p class="font-display text-3xl {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs text-sage-400 mt-1">{{ $stat['sub'] }}</p>
            </div>
        @endforeach

        <div class="card p-5 col-span-2 lg:col-span-1 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-forest-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <a href="{{ route('admin.projects.create') }}" class="text-sm font-semibold text-forest-700 hover:text-forest-900">
                    Proyek Baru
                </a>
                <p class="text-xs text-sage-400">Tambah ke portfolio</p>
            </div>
        </div>

        <div class="card p-5 col-span-2 lg:col-span-1 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rain-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-rain-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <a href="{{ route('admin.posts.create') }}" class="text-sm font-semibold text-rain-700 hover:text-rain-900">
                    Tulis Artikel
                </a>
                <p class="text-xs text-sage-400">Buat post baru</p>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent Projects --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-mist-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-forest-800">Proyek Terbaru</h3>
                <a href="{{ route('admin.projects.index') }}" class="text-xs text-forest-500 hover:text-forest-700">Lihat semua</a>
            </div>
            <div class="divide-y divide-mist-100">
                @forelse ($recentProjects as $project)
                    <div class="px-5 py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <a href="{{ route('admin.projects.edit', $project) }}"
                               class="text-sm font-medium text-forest-700 hover:text-forest-900 truncate block">
                                {{ $project->title }}
                            </a>
                            <p class="text-xs text-sage-400">{{ $project->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="badge {{ $project->status->badgeClass() }} flex-shrink-0">
                            {{ $project->status->label() }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-sage-400 text-center">Belum ada proyek.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Posts --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-mist-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-forest-800">Artikel Terbaru</h3>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-forest-500 hover:text-forest-700">Lihat semua</a>
            </div>
            <div class="divide-y divide-mist-100">
                @forelse ($recentPosts as $post)
                    <div class="px-5 py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="text-sm font-medium text-forest-700 hover:text-forest-900 truncate block">
                                {{ $post->title }}
                            </a>
                            <p class="text-xs text-sage-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="badge {{ $post->status->badgeClass() }} flex-shrink-0">
                            {{ $post->status->label() }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-sage-400 text-center">Belum ada artikel.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-admin-layout>
