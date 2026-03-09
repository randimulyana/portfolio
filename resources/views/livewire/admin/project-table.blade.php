<div>
    {{-- ─── Toolbar ───────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5 items-start sm:items-center justify-between">

        {{-- Search --}}
        <div class="relative w-full sm:w-72">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sage-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Cari proyek..."
                class="form-input pl-9"
            >
        </div>

        <a href="{{ route('admin.projects.create') }}" class="btn-primary flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Proyek
        </a>
    </div>

    {{-- ─── Table ──────────────────────────────────────────────────── --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm font-sans">
                <thead>
                    <tr class="bg-mist-50 border-b border-mist-200">
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sort('title')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-sage-500 hover:text-forest-700">
                                Judul
                                @if ($sortBy === 'title')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500 hidden sm:table-cell">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-sage-500 hidden md:table-cell">Featured</th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sort('created_at')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-sage-500 hover:text-forest-700 hidden lg:flex">
                                Dibuat
                                @if ($sortBy === 'created_at')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-sage-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mist-100">
                    @forelse ($projects as $project)
                        <tr class="hover:bg-mist-50/50 transition-colors group">
                            {{-- Thumbnail + Judul --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $project->thumbnail_url }}" alt=""
                                         class="w-10 h-10 rounded-lg object-cover bg-mist-100 flex-shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.projects.edit', $project) }}"
                                           class="font-medium text-forest-800 hover:text-forest-600 transition-colors truncate block max-w-[200px]">
                                            {{ $project->title }}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="text-sage-500 text-xs">{{ $project->category?->name ?? '—' }}</span>
                            </td>

                            {{-- Status toggle --}}
                            <td class="px-4 py-3">
                                <button
                                    wire:click="toggleStatus({{ $project->id }})"
                                    wire:loading.attr="disabled"
                                    class="badge {{ $project->status->badgeClass() }} cursor-pointer hover:opacity-80 transition-opacity"
                                    title="Klik untuk toggle"
                                >
                                    {{ $project->status->label() }}
                                </button>
                            </td>

                            {{-- Featured toggle --}}
                            <td class="px-4 py-3 hidden md:table-cell">
                                <button
                                    wire:click="toggleFeatured({{ $project->id }})"
                                    class="text-lg transition-all hover:scale-110"
                                    title="Toggle featured"
                                >
                                    {{ $project->is_featured ? '⭐' : '☆' }}
                                </button>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <span class="text-xs text-sage-400">{{ $project->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{-- Preview --}}
                                    @if ($project->isPublished())
                                        <a href="{{ route('projects.show', $project->slug) }}" target="_blank"
                                           class="btn-ghost p-1.5" title="Preview">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.projects.edit', $project) }}"
                                       class="btn-ghost p-1.5" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete dengan konfirmasi Alpine --}}
                                    <div x-data>
                                        <button
                                            @click="
                                                if (confirm('Hapus proyek ini? Data tidak bisa dikembalikan.'))
                                                    $wire.delete({{ $project->id }})
                                            "
                                            class="btn-danger p-1.5" title="Hapus"
                                        >
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
                                    Tidak ada proyek yang cocok dengan "<strong>{{ $search }}</strong>"
                                @else
                                    Belum ada proyek. <a href="{{ route('admin.projects.create') }}" class="text-forest-500 hover:underline">Buat sekarang</a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($projects->hasPages())
            <div class="px-4 py-3 border-t border-mist-100">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    {{-- Loading indicator global --}}
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
