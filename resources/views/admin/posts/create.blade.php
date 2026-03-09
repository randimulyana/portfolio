<x-admin-layout title="Tulis Artikel">
    <x-slot name="breadcrumb">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.posts.index') }}" class="text-sage-400 hover:text-forest-700">Blog</a>
            <span class="text-sage-300">/</span>
            <span class="font-medium text-forest-700">Tulis Baru</span>
        </nav>
    </x-slot>

    <div class="mb-6">
        <h1 class="font-display text-2xl text-forest-800">Tulis Artikel Baru</h1>
    </div>

    @include('admin.posts._form', [
        'post'       => null,
        'categories' => $categories,
        'action'     => route('admin.posts.store'),
        'method'     => 'POST',
    ])
</x-admin-layout>
