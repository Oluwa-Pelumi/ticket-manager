<x-app-layout :show-navbar="false">
    <x-slot name="title">Login</x-slot>

    <div class="container mx-auto px-4 py-10">
        <nav class="mb-12 flex items-center justify-between border-b border-sky-950/10 py-6 dark:border-[#1e3a5f]">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('logo.svg') }}?v=2" alt="{{ config('app.name') }} logo" class="h-8 w-8">
                <span class="text-xl font-semibold tracking-tight">{{ config('app.name') }}</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="fauna-btn-secondary !px-4 !py-2">Home</a>
                <button onclick="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-sky-950/20 text-slate-700 transition hover:border-sky-950 hover:text-sky-950 dark:border-slate-600 dark:text-slate-200" aria-label="Toggle Theme">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                </button>
            </div>
        </nav>

        <div class="mx-auto w-full max-w-md">
            <div class="fauna-panel px-8 py-10 md:px-10 md:py-12">
                @yield('guest-content')
            </div>
        </div>
    </div>
</x-app-layout>