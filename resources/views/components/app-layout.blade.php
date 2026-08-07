<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) && trim($title) ? trim($title) . ' — ' . config('app.name') : config('app.name') }}
    </title>
    <link rel="icon" href="{{ asset('favicon.svg') }}?v=3" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=3" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}?v=3" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=3">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    {{-- Dark mode: apply theme before paint to avoid flash --}}
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme') || 'light';
                if (theme === 'dark') document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>

    {{-- Vite: Tailwind CSS + App JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom component styles --}}
    <style>
        html,
        body {
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }

        .fauna-shell {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: rgb(255 247 237);
            color: rgb(15 23 42);
        }

        .dark .fauna-shell {
            background-color: #0b1715;
            color: rgb(241 245 249);
        }

        .fauna-panel {
            border-radius: 1.5rem;
            border: 1px solid rgba(6, 78, 59, 0.1);
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #022c22 0%, #064e3b 40%, #047857 70%, #065f46 100%);
        }

        .dark .hero-gradient {
            background: linear-gradient(135deg, #011913 0%, #062e24 50%, #021a14 100%);
        }

        .dark .fauna-panel {
            border-color: #1d3a34;
            background-color: #102824;
        }

        .glass-card {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .dark .glass-card {
            background-color: rgba(16, 40, 36, 0.75);
            border-color: #1d3a34;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.875rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 800;
            color: rgb(71 85 105);
            transition: all 0.2s;
            text-decoration: none;
        }

        .dark .nav-link {
            color: rgb(148 163 184);
        }

        .nav-link:hover,
        .nav-link-active {
            background-color: #ffffff;
            color: rgb(19 78 74);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .dark .nav-link:hover,
        .dark .nav-link-active {
            background-color: #18342f;
            color: rgb(163 230 53);
        }

        .dropdown-link {
            display: block;
            padding: 0.625rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: rgb(51 65 85);
            transition: all 0.15s;
            text-decoration: none;
        }

        .dark .dropdown-link {
            color: rgb(203 213 225);
        }

        .dropdown-link:hover {
            background-color: rgb(241 245 249);
            color: rgb(19 78 74);
            padding-left: 1.25rem;
        }

        .dark .dropdown-link:hover {
            background-color: #18342f;
            color: rgb(163 230 53);
        }

        .fauna-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid #064e3b;
            background-color: #064e3b;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #ffffff;
            transition: all 0.2s;
            text-decoration: none;
        }

        .fauna-btn-primary:hover {
            border-color: #10b981;
            background-color: #10b981;
            color: #064e3b;
        }

        .fauna-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid #064e3b;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #064e3b;
            transition: all 0.2s;
            text-decoration: none;
        }

        .dark .fauna-btn-secondary {
            border-color: rgb(71 85 105);
            color: rgb(241 245 249);
        }

        .fauna-btn-secondary:hover {
            background-color: #064e3b;
            color: #ffffff;
        }

        .dark .fauna-btn-secondary:hover {
            background-color: rgb(30 41 59);
        }

        .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%, rgba(2, 44, 34, .16) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(163, 230, 53, .14) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(2, 44, 34, .08) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(163, 230, 53, .1) 0px, transparent 50%);
        }

        .dark .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%, rgba(2, 44, 34, .2) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(132, 204, 22, .1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(2, 44, 34, .12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(132, 204, 22, .08) 0px, transparent 50%);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, .5);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(203 213 225);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgb(163 230 53);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(24, 52, 47, .3);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(51 65 85);
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgb(203 213 225) transparent;
        }

        .dark .custom-scrollbar {
            scrollbar-color: rgb(51 65 85) transparent;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased fauna-shell">

    @include('components.flash-handler')

    {{-- ── Global navbar ── --}}
    @if ($showNavbar ?? true)
        <nav class="relative z-50 border-b border-emerald-900/10 bg-white shadow-md dark:border-[#1d3a34] dark:bg-[#102824]"
            x-data="{ showingMobileMenu: false }">
            <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                <div class="flex h-16 md:h-20 justify-between items-center">
                    <div class="flex items-center gap-2.5 sm:gap-4 md:gap-6 lg:gap-8 min-w-0">
                        @php
                            $appName = rtrim(config('app.name'), ' .');
                            $nameParts = explode(',', $appName, 2);
                            $mainName = trim($nameParts[0]);
                            $subName = isset($nameParts[1]) ? trim($nameParts[1]) : '';
                        @endphp

                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0 min-w-0">
                            <img src="{{ asset('logo.svg') }}?v=3" alt="{{ $appName }} logo"
                                class="w-9 h-9 sm:w-10 sm:h-10 md:w-11 md:h-11 transition-transform group-hover:scale-105 shrink-0">
                            <div class="flex flex-col leading-none min-w-0">
                                <span
                                    class="text-xs sm:text-sm md:text-base lg:text-lg font-black tracking-tight text-rose-400 dark:text-rose-400 truncate">
                                    {{ $mainName }}
                                </span>
                                @if ($subName)
                                    <span
                                        class="text-[9px] sm:text-[10px] lg:text-xs font-bold tracking-wider uppercase text-slate-600 dark:text-slate-400 truncate mt-0.5">
                                        {{ $subName }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div
                            class="hidden lg:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#0b1f1c]/70 rounded-2xl border border-rose-950/10 dark:border-[#1d3a34] backdrop-blur-md">
                            <a href="{{ route('home') }}"
                                class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            @else
                                <a href="{{ route('submit-ticket') }}"
                                    class="nav-link {{ request()->routeIs('submit-ticket') ? 'nav-link-active' : '' }}">Submit
                                    Ticket</a>
                            @endauth
                            <a href="{{ route('check-status') }}"
                                class="nav-link {{ request()->routeIs('check-status', 'search-tickets') ? 'nav-link-active' : '' }}">Check
                                Status</a>
                            @auth
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.users') }}"
                                        class="nav-link {{ request()->routeIs('admin.users') ? 'nav-link-active' : '' }}">Users<span
                                            class="hidden lg:inline ml-1">Management</span></a>
                                @endif
                            @endauth
                        </div>
                    </div>

                <div class="hidden md:flex md:items-center gap-4">
                    @auth
                        @if (auth()->user()->isAdmin() || auth()->user()->isSupport())
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="relative w-10 h-10 rounded-2xl flex items-center justify-center border border-emerald-900/10 bg-white text-slate-600 transition-all hover:text-teal-900 dark:border-[#1d3a34] dark:bg-[#102824] dark:text-slate-400 group"
                                    title="{{ count($due_tickets) }} orders due for processing">
                                    <svg class="w-5 h-5 {{ count($due_tickets) > 0 ? 'animate-bounce text-lime-500' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if (count($due_tickets) > 0)
                                        <span
                                            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white shadow-lg ring-2 ring-white dark:ring-[#102824]">
                                            {{ count($due_tickets) }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open" @click.outside="open = false" x-cloak
                                    class="absolute right-0 top-full mt-2 w-80 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#102824]/95 backdrop-blur-xl border border-emerald-900/10 dark:border-[#1d3a34] py-1 z-50 shadow-xl">
                                    <div class="p-4 border-b border-slate-100 dark:border-[#1d3a34]">
                                        <h3
                                            class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">
                                            Due for Processing
                                        </h3>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                            Copy the ticket reference and paste in the searchbox to attend to tickets
                                            order
                                        </p>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                        @if (count($due_tickets) > 0)
                                            @foreach ($due_tickets as $ticket)
                                                <div
                                                    class="block p-4 hover:bg-emerald-50/50 dark:hover:bg-[#18342f] transition-colors border-b border-slate-100 dark:border-[#1d3a34] last:border-0 text-left">
                                                    <div class="flex justify-between items-start mb-1 gap-2">
                                                        <a href="{{ route('dashboard') }}"
                                                            class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 hover:text-teal-900 dark:hover:text-lime-400">
                                                            {{ ucfirst($ticket->subject) }}
                                                        </a>
                                                        <span
                                                            class="text-[9px] font-black uppercase bg-lime-500/10 text-lime-600 dark:text-lime-400 px-1.5 py-0.5 rounded shrink-0">
                                                            {{ $ticket->period }}
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-slate-500 dark:text-slate-400 mb-2 truncate">
                                                        {{ Str::limit($ticket->content, 32) }}
                                                    </div>
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[9px] font-mono font-black text-slate-400">
                                                                {{ $ticket->hashid }}
                                                            </span>
                                                            <button type="button"
                                                                onclick="copyToClipboard('{{ $ticket->hashid }}', this)"
                                                                class="p-1 rounded bg-slate-100 dark:bg-[#18342f] text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 transition-all"
                                                                title="Copy Reference">
                                                                <svg class="w-3 h-3 copy-icon" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">
                                                            {{ \Carbon\Carbon::parse($ticket->last_activation)->format('m/d/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="p-8 text-center">
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    No orders due for processing.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    @if (count($due_tickets) > 0)
                                        <div class="p-3 bg-slate-50 dark:bg-[#0b1715]/50 text-center">
                                            <a href="{{ route('dashboard') }}"
                                                class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-widest hover:underline">
                                                View all in Dashboard
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endauth

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="flex items-center gap-2.5 p-1.5 pr-3 md:pr-4 rounded-2xl glass-card border border-emerald-900/10 dark:border-[#1d3a34]/50 hover:border-lime-500/50 transition-all">
                                <div
                                    class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white text-[10px] font-black shadow-lg shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span
                                    class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate max-w-[80px] lg:max-w-[140px]">{{ auth()->user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#102824]/90 backdrop-blur-xl border border-emerald-900/10 dark:border-[#1d3a34] py-1 z-50 shadow-xl">
                                <a href="{{ route('profile.edit') }}" class="dropdown-link">Profile Settings</a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.categories.index') }}" class="dropdown-link">Manage
                                        Categories</a>
                                    <a href="{{ route('admin.faqs.index') }}" class="dropdown-link">Manage FAQs</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-link w-full text-start">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                                class="nav-link bg-slate-200/40 dark:bg-[#18342f] text-slate-700 dark:text-slate-200 hover:text-teal-900 border border-emerald-900/10 dark:border-[#1d3a34]">Login</a>
                            <a href="{{ route('register') }}"
                                class="nav-link bg-teal-900 text-white hover:bg-[#10b981] hover:text-[#064e3b] shadow-md">Register</a>
                        </div>
                    @endauth

                    <button onclick="toggleTheme()"
                        class="inline-flex h-9 w-9 md:h-10 md:w-10 shrink-0 items-center justify-center rounded-full glass-card border border-emerald-900/10 dark:border-[#1d3a34]/50 hover:border-emerald-500/50 transition-all"
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

                {{-- Mobile menu button --}}
                <div class="flex md:hidden items-center gap-2">
                    @auth
                        @if (auth()->user()->isAdmin() || auth()->user()->isSupport())
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="relative w-9 h-9 rounded-xl flex items-center justify-center border border-emerald-900/10 dark:border-[#1d3a34] bg-white dark:bg-[#102824] text-slate-600 dark:text-slate-400 group"
                                    title="{{ count($due_tickets) }} orders due for processing">
                                    <svg class="w-4 h-4 {{ count($due_tickets) > 0 ? 'animate-bounce text-lime-500' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if (count($due_tickets) > 0)
                                        <span
                                            class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[8px] font-black text-white shadow-lg ring-2 ring-white dark:ring-[#102824]">
                                            {{ count($due_tickets) }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open" @click.outside="open = false" x-cloak
                                    class="fixed inset-x-4 top-24 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#102824]/95 backdrop-blur-xl border border-emerald-900/10 dark:border-[#1d3a34] py-1 z-50 shadow-xl max-w-sm mx-auto">
                                    <div class="p-4 border-b border-slate-100 dark:border-[#1d3a34] text-left">
                                        <h3
                                            class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">
                                            Due for Processing
                                        </h3>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                            Copy the ticket reference and paste in the searchbox to attend to tickets
                                            order
                                        </p>
                                    </div>
                                    <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                        @if (count($due_tickets) > 0)
                                            @foreach ($due_tickets as $ticket)
                                                <div
                                                    class="block p-4 hover:bg-emerald-50/50 dark:hover:bg-[#18342f] transition-colors border-b border-slate-100 dark:border-[#1d3a34] last:border-0 text-left">
                                                    <div class="flex justify-between items-start mb-1 gap-2">
                                                        <a href="{{ route('dashboard') }}"
                                                            class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 hover:text-teal-900 dark:hover:text-lime-400">
                                                            {{ ucfirst($ticket->subject) }}
                                                        </a>
                                                        <span
                                                            class="text-[9px] font-black uppercase bg-lime-500/10 text-lime-600 dark:text-lime-400 px-1.5 py-0.5 rounded shrink-0">
                                                            {{ $ticket->period }}
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-slate-500 dark:text-slate-400 mb-2 truncate">
                                                        {{ Str::limit($ticket->content, 32) }}
                                                    </div>
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[9px] font-mono font-black text-slate-400">
                                                                {{ $ticket->hashid }}
                                                            </span>
                                                            <button type="button"
                                                                onclick="copyToClipboard('{{ $ticket->hashid }}', this)"
                                                                class="p-1 rounded bg-slate-100 dark:bg-[#18342f] text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 transition-all"
                                                                title="Copy Reference">
                                                                <svg class="w-3 h-3 copy-icon" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">
                                                            {{ \Carbon\Carbon::parse($ticket->last_activation)->format('m/d/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="p-6 text-center">
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    No orders due for processing.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    @if (count($due_tickets) > 0)
                                        <div class="p-3 bg-slate-50 dark:bg-[#0b1715]/50 text-center">
                                            <a href="{{ route('dashboard') }}"
                                                class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-widest hover:underline">
                                                View all in Dashboard
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endauth

                    <button onclick="toggleTheme()"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full glass-card border border-emerald-900/10 dark:border-[#1d3a34]/50 hover:border-emerald-500/50 transition-all"
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

                    <button @click="showingMobileMenu = !showingMobileMenu"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-emerald-900/10 dark:border-[#1d3a34] text-slate-600 dark:text-slate-400">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': showingMobileMenu, 'inline-flex': !showingMobileMenu }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !showingMobileMenu, 'inline-flex': showingMobileMenu }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                    </button>
                </div>
            </div>
            </div>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="showingMobileMenu" x-cloak
                class="md:hidden border-t border-emerald-900/10 dark:border-[#1d3a34] bg-white dark:bg-[#102824] px-4 py-4 space-y-3 shadow-2xl">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('home') ? 'bg-emerald-500/10 text-emerald-600 dark:text-lime-400' : '' }}">Home</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-500/10 text-emerald-600 dark:text-lime-400' : '' }}">Dashboard</a>
                @else
                    <a href="{{ route('submit-ticket') }}"
                        class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('submit-ticket') ? 'bg-emerald-500/10 text-emerald-600 dark:text-lime-400' : '' }}">Submit
                        Ticket</a>
                @endauth
                <a href="{{ route('check-status') }}"
                    class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('check-status', 'search-tickets') ? 'bg-emerald-500/10 text-emerald-600 dark:text-lime-400' : '' }}">Check
                    Status</a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('admin.users') ? 'bg-emerald-500/10 text-emerald-600 dark:text-lime-400' : '' }}">Users
                            Management</a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage
                            Categories</a>
                        <a href="{{ route('admin.faqs.index') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage
                            FAQs</a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Profile
                        Settings</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-3 py-2 rounded-xl text-sm font-bold text-rose-500">Sign
                            Out</button>
                    </form>
                @else
                    <div class="pt-2 border-t border-emerald-900/10 dark:border-[#1d3a34] flex gap-2">
                        <a href="{{ route('login') }}"
                            class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold bg-slate-100 dark:bg-[#18342f] text-slate-700 dark:text-slate-200">Login</a>
                        <a href="{{ route('register') }}"
                            class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold bg-teal-900 text-white hover:bg-[#10b981] hover:text-[#064e3b] shadow-md">Register</a>
                    </div>
                @endauth
            </div>
        </nav>
    @endif

    {{-- ── Page header slot (used by views that set <x-slot name="header">) ── --}}
    @if (isset($header))
        <header class="relative z-10 py-3 sm:py-4 md:py-8">
            <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                <div class="fauna-panel relative overflow-hidden p-4 sm:p-6 md:p-10">
                    <div
                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40">
                    </div>
                    {{ $header }}
                </div>
            </div>
        </header>
    @endif

    {{-- ── Slot content ── --}}
    {{-- For direct component use (e.g. dashboard), $slot is the page content.        --}}
    {{-- For @extends('layouts.authenticated'), $slot is the full layout HTML from    --}}
    {{-- authenticated.blade.php which already includes its own header/main wrappers. --}}
    <div class="flex-1">
        {{ $slot ?? '' }}
    </div>

    @include('components.footer')

    {{-- ── Global Confirm Modal ──────────────────────────────────────────────── --}}
    {{-- Listens for: $dispatch('confirm', { type, title, message, confirmText, onConfirm }) --}}
    {{-- Used by: categories delete, faqs delete, dashboard bulk actions, etc.              --}}
    <div id="global-confirm-modal" x-data="{
        open: false,
        deleting: false,
        type: 'danger',
        title: '',
        message: '',
        confirmText: 'Confirm',
        successMessage: '',
        onConfirm: null,
        form: null,

        init() {
            window.addEventListener('confirm', (e) => {
                this.type = e.detail?.type ?? 'danger';
                this.title = e.detail?.title ?? 'Are you sure?';
                this.message = e.detail?.message ?? '';
                this.confirmText = e.detail?.confirmText ?? 'Confirm';
                this.successMessage = e.detail?.successMessage ?? 'Done.';
                this.onConfirm = e.detail?.onConfirm ?? null;
                this.form = e.detail?.form ?? null;
                this.deleting = false;
                this.open = true;
            });
        },

       async confirm() {
            // Form-based delete: use fetch so we can control the UX
            if (this.form) {
                this.deleting = true;
                const formEl = this.form;
                const data = new FormData(formEl);
                try {
                    await fetch(formEl.action, { method: 'POST', body: data });
                } catch (_) {}
                this.open = false;
                this.deleting = false;
                await new Promise(r => setTimeout(r, 250)); // let modal close animate
                window.showToast(this.successMessage || 'Deleted successfully.', 'success');
                // Reload the list area (full page reload preserves server state)
                window.location.reload();
                return;
            }
            // Fallback: call onConfirm callback directly
            if (typeof this.onConfirm === 'function') this.onConfirm();
            this.open = false;
        },

        cancel() {
            if (this.deleting) return; // block cancel while processing
            this.open = false;
        }
    }" x-show="open" x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @keydown.escape.window="cancel()">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancel()"></div>

        {{-- Panel --}}
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-[#102824] rounded-3xl shadow-2xl border border-slate-100 dark:border-[#1d3a34] overflow-hidden p-8"
            @click.stop x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            {{-- Icon --}}
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                    :class="{
                        'bg-rose-500/10 border border-rose-500/20': type === 'danger',
                        'bg-amber-500/10 border border-amber-500/20': type === 'warning',
                        'bg-blue-500/10 border border-blue-500/20': type !== 'danger' && type !== 'warning',
                    }">
                    {{-- Spinner while deleting, icon otherwise --}}
                    <template x-if="deleting">
                        <svg class="w-6 h-6 animate-spin"
                            :class="{
                                'text-rose-500': type === 'danger',
                                'text-amber-500': type === 'warning',
                                'text-rose-500': type !== 'danger' && type !== 'warning',
                            }"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </template>
                    <template x-if="!deleting">
                        <svg class="w-6 h-6"
                            :class="{
                                'text-rose-500': type === 'danger',
                                'text-amber-500': type === 'warning',
                                'text-rose-500': type !== 'danger' && type !== 'warning',
                            }"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight" x-text="title"></h3>
            </div>

            {{-- Message --}}
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 leading-relaxed"
                x-text="deleting ? 'Please wait while the item is being removed...' : message"></p>

            {{-- Actions --}}
            <div class="flex gap-3 justify-end">
                <button @click="cancel()" x-bind:disabled="deleting"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-[#18342f] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all border border-transparent disabled:opacity-40 disabled:cursor-not-allowed">
                    Cancel
                </button>
                <button @click="confirm()" x-bind:disabled="deleting"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:scale-100 flex items-center gap-2"
                    :class="{
                        'bg-rose-600 hover:bg-rose-700': type === 'danger',
                        'bg-amber-500 hover:bg-amber-600': type === 'warning',
                        'bg-blue-600 hover:bg-blue-700': type !== 'danger' && type !== 'warning',
                    }">
                    <template x-if="deleting">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </template>
                    <span
                        x-text="deleting ? confirmText.replace(/^(Delete|Remove)(.*)?$/, (_, v, rest) => (v === 'Delete' ? 'Deleting' : 'Removing') + (rest || '') + '...') : confirmText"></span>
                    </button>
            </div>
        </div>
    </div>
    {{-- ──────────────────────────────────────────────────────────────────────── --}}

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon-dark').forEach(el => el.classList.toggle('hidden', !isDark));
            document.querySelectorAll('.theme-icon-light').forEach(el => el.classList.toggle('hidden', isDark));
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);

        window.showToast = function(message, type = 'success') {
            let container = document.getElementById('global-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'global-toast-container';
                container.className = 'fixed top-8 right-8 z-[100] flex flex-col gap-3 pointer-events-none';
                container.style.cssText = 'min-width:320px;max-width:420px';
                document.body.appendChild(container);
            }

            const configs = {
                success: {
                    color: '#3b82f6',
                    label: 'Success',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'
                },
                error: {
                    color: '#f43f5e',
                    label: 'Error',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'
                },
                warning: {
                    color: '#f59e0b',
                    label: 'Warning',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>'
                },
                info: {
                    color: '#3b82f6',
                    label: 'Info',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>'
                },
            };
            const cfg = configs[type] || configs.success;

            const isDark = document.documentElement.classList.contains('dark');
            const bg = isDark ? 'rgba(15,23,42,0.97)' : '#ffffff';
            const borderMid = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.07)';
            const textColor = isDark ? '#f1f5f9' : '#1e293b';

            const toast = document.createElement('div');
            toast.className =
                'pointer-events-auto flex items-start gap-3 px-4 py-3.5 rounded-2xl shadow-xl transition-all duration-300 transform -translate-y-4 opacity-0';
            toast.style.cssText =
                `background:${bg};border:1px solid ${borderMid};border-left:4px solid ${cfg.color};box-shadow:0 8px 32px rgba(0,0,0,0.12)`;

            toast.innerHTML = `
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="${cfg.color}">${cfg.icon}</svg>
                <div style="display:flex;flex-direction:column;gap:2px;flex:1">
                    <span style="font-size:11px;font-weight:900;letter-spacing:0.12em;text-transform:uppercase;color:${cfg.color}">${cfg.label}</span>
                    <p style="font-size:14px;font-weight:500;color:${textColor};line-height:1.4">${message}</p>
                </div>
                <button onclick="this.closest('.pointer-events-auto').remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none mt-0.5 shrink-0" style="background:none;border:none;padding:0;cursor:pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => toast.classList.remove('-translate-y-4', 'opacity-0'), 10);

            setTimeout(() => {
                toast.classList.add('-translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };

        function copyToClipboard(text, btn) {
            const onSuccess = () => {
                const icon = btn.querySelector('.copy-icon') || btn;
                const originalHtml = icon.outerHTML;
                const isW3 = icon.classList.contains('w-3');
                const sizeClass = isW3 ? 'w-3 h-3' : 'w-4 h-4';
                icon.outerHTML =
                    `<svg class="${sizeClass} text-emerald-500 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                setTimeout(() => {
                    const currentIcon = btn.querySelector('.copy-icon') || btn;
                    currentIcon.outerHTML = originalHtml;
                }, 2000);
            };

            const fallback = () => {
                const ta = Object.assign(document.createElement('textarea'), {
                    value: text,
                    style: 'position:fixed;left:-9999px'
                });
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    onSuccess();
                } catch (e) {
                    console.error("Copy fallback failed", e);
                }
                ta.remove();
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(onSuccess).catch(fallback);
            } else {
                fallback();
            }
        }
    </script>
    {{ $scripts ?? '' }}
    @stack('scripts')
</body>

</html>
