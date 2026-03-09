{{-- x-ui.alert — Props: type (success|error|info), message --}}
@props(['type' => 'success', 'message'])

@php
$styles = [
    'success' => 'bg-emerald-50 border-emerald-300 text-emerald-800',
    'error'   => 'bg-red-50 border-red-300 text-red-800',
    'info'    => 'bg-rain-50 border-rain-300 text-rain-800',
];
$icons = [
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'error'   => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    'info'    => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 mt-3"
>
    <div class="flex items-center justify-between gap-3 rounded-xl border px-4 py-3 text-sm font-sans {{ $styles[$type] ?? $styles['info'] }}">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$type] ?? $icons['info'] }}"/>
            </svg>
            {{ $message }}
        </div>
        <button @click="show = false" class="opacity-60 hover:opacity-100 transition-opacity ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
