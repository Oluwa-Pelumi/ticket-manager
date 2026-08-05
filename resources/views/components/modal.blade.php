@props([
    'name'      => 'modal',
    'focusable' => false,
    'maxWidth'  => '2xl',
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm'  => 'sm:max-w-sm',
    'md'  => 'sm:max-w-md',
    'lg'  => 'sm:max-w-lg',
    'xl'  => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    default => 'sm:max-w-2xl',
};
@endphp

{{-- Backdrop + modal wrapper — outer x-show is supplied by the caller --}}
<div
    {{ $attributes->only(['x-show', '@close', 'x-transition', 'x-cloak']) }}
    x-cloak
    class="fixed inset-0 z-[999] overflow-y-auto"
    aria-modal="true"
    role="dialog"
    @keydown.escape.window="$dispatch('close')"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        @click="$dispatch('close')"
    ></div>

    {{-- Panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            class="relative w-full {{ $maxWidthClass }} bg-white dark:bg-[#102824] rounded-3xl shadow-2xl border border-slate-100 dark:border-[#1d3a34] overflow-hidden transform transition-all"
            @click.stop
        >
            {{ $slot }}
        </div>
    </div>
</div>
