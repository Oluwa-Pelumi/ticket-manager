{{--
    Guest layout component.
    Used by auth views that do: <x-guest-layout>
    Renders through <x-app-layout> but without the @auth navbar (guests are not authenticated).
--}}
<x-app-layout :show-navbar="false">
    <x-slot name="title">{{ $title ?? config('app.name') }}</x-slot>

    <div class="container mx-auto px-4 py-10">
        {{-- Guest nav bar --}}
        <nav class="mb-12 flex items-center justify-center border-b border-emerald-900/10 py-6 dark:border-[#1d3a34]">
            @php
                $appName = rtrim(config('app.name'), ' .');
                $nameParts = explode(',', $appName, 2);
                $mainName = trim($nameParts[0]);
                $subName = isset($nameParts[1]) ? trim($nameParts[1]) : '';
            @endphp

            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2.5 sm:gap-3 group shrink-0 min-w-0 max-w-[70vw] sm:max-w-none">
                <img src="{{ asset('logo.svg') }}?v=4" alt="{{ $appName }} logo"
                    class="w-10 h-10 sm:w-12 sm:h-12 transition-transform group-hover:scale-105 shrink-0">
                <div class="flex flex-col leading-none min-w-0 text-left">
                    <span class="text-xs sm:text-sm md:text-base lg:text-lg font-black tracking-tight text-fuchsia-300 drop-shadow-md truncate">
                        {{ $mainName }}
                    </span>
                    @if ($subName)
                        <span
                            class="text-[10px] sm:text-xs font-bold tracking-wider uppercase text-state-200 dark:text-state-400 truncate mt-0.5">
                            {{ $subName }}
                        </span>
                    @endif
                </div>
            </a>

            <div class="absolute right-0 flex items-center">
                <button onclick="toggleTheme()"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full glass-card border border-emerald-900/20 text-slate-700 hover:border-teal-900 hover:text-teal-900 dark:border-slate-600 dark:text-slate-200 transition-all"
                    aria-label="Toggle Theme">
                    <svg class="theme-icon-dark w-4 h-4 text-emerald-400 hidden" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                    </svg>

                    <svg class="theme-icon-light w-4 h-4 text-amber-400 hidden" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
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
