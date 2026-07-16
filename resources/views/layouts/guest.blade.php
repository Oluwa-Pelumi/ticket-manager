<x-app-layout :show-navbar="false">
    <x-slot name="title">@yield('title', 'Login')</x-slot>

    <div class="container mx-auto px-4 py-10">
        <nav
            class="mb-12 relative flex items-center justify-center border-b border-sky-950/10 py-6 dark:border-[#1e3a5f]">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('logo.svg') }}?v=2" alt="{{ config('app.name') }} logo" class="h-12 w-12">
                <span
                    class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ config('app.name') }}</span>
            </a>

            <div class="absolute right-0 flex items-center">
                <button onclick="toggleTheme()"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-black/5 hover:bg-black/10 border border-gray-300 text-gray-900 dark:bg-white/5 dark:hover:bg-white/10 dark:border-white/10 dark:text-white transition-all backdrop-blur-md hover:scale-110 active:scale-95"
                    aria-label="Toggle Theme">
                    <svg id="theme-icon-dark" class="w-4 h-4 text-sky-400 hidden" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                    </svg>

                    <svg id="theme-icon-light" class="w-4 h-4 text-amber-400 hidden" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
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
