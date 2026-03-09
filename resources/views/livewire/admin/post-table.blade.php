<div>
    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5 items-start sm:items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-3 flex-1">
            <div class="relative w-full sm:w-64">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sage-400 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="search" placeholder="Cari artikel..."
                       class="form-input pl-9">
            </div>

            {{-- Filter status --}}
            <div class="flex gap-1.5">
                <button wire:click="$set('filterStatus', '')"
                        class="badge text-xs py-1.5 px-3 transition-colors cursor-pointer
                               {{ $filterStatus === '' ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300' }}">
                    Semua
                </button>
                @foreach ($statuses as $s)
                    <button wire:click="$set('filterStatus', '{{ $s->value }}')"
                            class="badge text-xs py-1.5 px-3 transition-colors cursor-pointer
                                   {{ $filterStatus === $s->value ? 'bg-forest-500 text-white' : 'bg-white border border-mist-200 text-sage-600 hover:border-forest-300' }}">
                        {{ $s->label() }}
                    </button>
                @endforeach
            </div>
        </div>

        <a href="{{ route('admin.posts.create') }}" class="btn-primary flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Tulis Artikel
        </a>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm font-sans">
                <thead>
                    <tr class="bg-mist-50 border-b border-mist-200">
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sort('title')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-sage-500 hover:text-forest-700">
                                Judul
                                @if($sortBy === 'title')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500 hidden sm:table-cell">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500 hidden md:table-cell">Views</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500 hidden lg:table-cell">
                            <button wire:click="sort('published_at')" class="flex items-center gap-1 hover:text-forest-700">
                                Publish
                                @if($sortBy === 'published_at')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5 10l5-5 5 5H5z"/></svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-sage-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mist-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-mist-50/50 transition-colors group">
                            <td class="px-4 py-3">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="font-medium text-forest-800 hover:text-forest-600 transition-colors block truncate max-w-[260px]">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-xs text-sage-400">{{ $post->reading_time }} mnt baca</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="text-xs text-sage-500">{{ $post->category?->name ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $post->status->badgeClass() }}">{{ $post->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <span class="text-xs text-sage-500">{{ number_format($post->views) }}</span>
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <span class="text-xs text-sage-400">
                                    {{ $post->published_at ? $post->published_at->diffForHumans() : '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if ($post->isPublished())
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                           class="btn-ghost p-1.5" title="Preview">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost p-1.5" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <div x-data>
                                        <button @click="if(confirm('Hapus artikel ini?')) $wire.delete({{ $post->id }})"
                                                class="btn-danger p-1.5" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center text-sage-400 text-sm">
                                @if ($search)
                                    Tidak ditemukan artikel "<strong>{{ $search }}</strong>"
                                @else
                                    Belum ada artikel. <a href="{{ route('admin.posts.create') }}" class="text-forest-500 hover:underline">Tulis sekarang</a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($posts->hasPages())
            <div class="px-4 py-3 border-t border-mist-100">{{ $posts->links() }}</div>
        @endif
    </div>

    <div wire:loading.delay class="fixed bottom-4 right-4 z-50">
        <div class="bg-forest-800 text-white text-xs px-3 py-2 rounded-lg shadow-card flex items-center gap-2">
            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            Memproses...
        </div>
    </div>
</div>
