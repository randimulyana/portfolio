<x-admin-layout title="Proyek">
    <x-slot name="breadcrumb">
        <span class="text-sm font-medium text-forest-700">Proyek</span>
    </x-slot>

    {{-- Livewire ProjectTable menangani search, sort, toggle, delete --}}
    <livewire:admin.project-table />

</x-admin-layout>
