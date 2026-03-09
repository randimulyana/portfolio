<x-admin-layout title="Tambah Proyek">
    <x-slot name="breadcrumb">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.projects.index') }}" class="text-sage-400 hover:text-forest-700">Proyek</a>
            <span class="text-sage-300">/</span>
            <span class="font-medium text-forest-700">Tambah Baru</span>
        </nav>
    </x-slot>

    <div class="mb-6">
        <h1 class="font-display text-2xl text-forest-800">Tambah Proyek Baru</h1>
    </div>

    @include('admin.projects._form', [
        'project' => null,
        'categories' => $categories,
        'action' => route('admin.projects.store'),
        'method' => 'POST',
    ])
</x-admin-layout>
