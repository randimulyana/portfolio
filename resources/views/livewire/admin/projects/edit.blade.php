<x-admin-layout :title="'Edit: ' . $project->title">
    <x-slot name="breadcrumb">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.projects.index') }}" class="text-sage-400 hover:text-forest-700">Proyek</a>
            <span class="text-sage-300">/</span>
            <span class="font-medium text-forest-700 truncate max-w-[200px]">{{ $project->title }}</span>
        </nav>
    </x-slot>

    <div class="flex items-start justify-between mb-6">
        <h1 class="font-display text-2xl text-forest-800">Edit Proyek</h1>
        @if ($project->isPublished())
            <a href="{{ route('projects.show', $project->slug) }}" target="_blank"
               class="btn-ghost text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat di website
            </a>
        @endif
    </div>

    @include('admin.projects._form', [
        'project' => $project,
        'categories' => $categories,
        'action' => route('admin.projects.update', $project),
        'method' => 'PUT',
    ])
</x-admin-layout>
