{{--
    Guest layout component.
    Used by auth views that do: <x-guest-layout>
    Renders through <x-app-layout> but without the @auth navbar (guests are not authenticated).
--}}
<x-app-layout :show-navbar="false">
    <x-slot name="title">{{ $title ?? config('app.name') }}</x-slot>

    <div class="container mx-auto px-4 py-10">
        {{-- Guest nav bar --}}
        <nav class="mb-12 flex items-center justify-between border-b border-emerald-900/10 py-6 dark:border-[#1d3a34]">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <x-application-logo class="h-8 w-8 text-teal-900 dark:text-lime-400" />
                <span class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ config('app.name') }}</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="fauna-btn-secondary !px-4 !py-2">Home</a>
                <button onclick="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-emerald-900/20 text-slate-700 transition hover:border-teal-900 hover:text-teal-900 dark:border-slate-600 dark:text-slate-200" aria-label="Toggle Theme">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                </button>
            </div>
        </nav>

        <div class="mx-auto w-full max-w-md">
            <div class="fauna-panel px-8 py-10 md:px-10 md:py-12">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
