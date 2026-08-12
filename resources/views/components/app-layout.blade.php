<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) && trim($title) ? trim($title) . ' — ' . config('app.name') : config('app.name') }}</title>
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
            background-color: rgb(239 246 255);
            color: rgb(15 23 42);
        }

        .dark .fauna-shell {
            background-color: #020617;
            color: rgb(241 245 249);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #4c0519 0%, #881337 40%, #500724 70%, #2d0a1e 100%);
        }

        .dark .hero-gradient {
            background: #020617;
        }

        .fauna-panel {
            border-radius: 1.5rem;
            border: 1px solid rgba(8, 47, 73, 0.1);
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .dark .fauna-panel {
            border-color: #1e3a5f;
            background-color: #0f172a;
        }

        .glass-card {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .dark .glass-card {
            background-color: rgba(15, 23, 42, 0.75);
            border-color: #1e3a5f;
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
            color: rgb(8 47 73);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .dark .nav-link:hover,
        .dark .nav-link-active {
            background-color: #1e293b;
            color: rgb(56 189 248);
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
            color: rgb(8 47 73);
            padding-left: 1.25rem;
        }

        .dark .dropdown-link:hover {
            background-color: #1e293b;
            color: rgb(56 189 248);
        }

        .fauna-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid #e36c8e;
            background-color: #e36c8e;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #ffffff;
            transition: all 0.2s;
            text-decoration: none;
        }

        .fauna-btn-primary:hover {
            border-color: #ca4d71;
            background-color: #ca4d71;
            color: #ffffff;
        }

        .fauna-btn-primary:disabled,
        .fauna-btn-primary[disabled] {
            pointer-events: none;
            border-color: #e7b0be;
            background-color: #e7b0be;
            opacity: 0.7;
        }

        .fauna-btn-action {
            border: 1px solid #e36c8e;
            background-color: #e36c8e;
            color: #ffffff;
            transition: all 0.2s;
        }

        .fauna-btn-action:hover {
            border-color: #ca4d71;
            background-color: #ca4d71;
            color: #ffffff;
        }

        .fauna-btn-action:disabled,
        .fauna-btn-action[disabled] {
            cursor: not-allowed;
            border-color: #e7b0be;
            background-color: #e7b0be;
            opacity: 0.5;
        }

        .fauna-btn-action:disabled:hover,
        .fauna-btn-action[disabled]:hover {
            border-color: #e7b0be;
            background-color: #e7b0be;
            color: #ffffff;
        }

        .fauna-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid #1e3a8a;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e3a8a;
            transition: all 0.2s;
            text-decoration: none;
        }

        .dark .fauna-btn-secondary {
            border-color: rgb(71 85 105);
            color: rgb(241 245 249);
        }

        .fauna-btn-secondary:hover {
            background-color: #1e3a8a;
            color: #ffffff;
        }

        .dark .fauna-btn-secondary:hover {
            background-color: rgb(30 41 59);
        }

        .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%, rgba(8, 47, 73, .16) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(56, 189, 248, .14) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(8, 47, 73, .08) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(56, 189, 248, .1) 0px, transparent 50%);
        }

        .dark .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%, rgba(8, 47, 73, .2) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(56, 189, 248, .1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(8, 47, 73, .12) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(56, 189, 248, .08) 0px, transparent 50%);
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
            background: rgb(56 189 248);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(30, 41, 59, .3);
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

<body class="font-sans antialiased fauna-shell flex flex-col min-h-screen">

    @include('components.flash-handler')

    {{-- ── Global navbar ── --}}
    @if ($showNavbar ?? true)
        <nav class="relative z-50 border-b border-rose-950/10 bg-white shadow-md dark:border-[#1e3a5f] dark:bg-[#0f172a]"
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
                                <span class="text-xs sm:text-sm md:text-base lg:text-lg font-black tracking-tight text-fuchsia-600 dark:text-fuchsia-400 truncate">
                                    {{ $mainName }}
                                </span>
                                @if ($subName)
                                    <span class="text-[9px] sm:text-[10px] lg:text-xs font-bold tracking-wider uppercase text-slate-900 dark:text-white truncate mt-0.5">
                                        {{ $subName }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div
                            class="hidden md:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#0f172a]/70 rounded-2xl border border-rose-950/10 dark:border-[#1e3a5f] backdrop-blur-md">
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

                    <div class="hidden md:flex md:items-center gap-3 lg:gap-4 shrink-0">
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="flex items-center gap-2.5 p-1.5 pr-3 md:pr-4 rounded-2xl glass-card border border-rose-950/10 dark:border-[#1e3a5f]/50 hover:border-rose-400/50 transition-all">
                                    <div
                                        class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gradient-to-br from-rose-950 to-rose-800 flex items-center justify-center text-white text-[10px] font-black shadow-lg shrink-0">
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
                                    class="absolute right-0 mt-2 w-48 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl border border-rose-950/10 dark:border-[#1e3a5f] py-1 z-50 shadow-xl">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-link">Profile Settings</a>
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.categories.index') }}" class="dropdown-link">Manage
                                            Categories</a>
                                        <a href="{{ route('admin.programmes.index') }}" class="dropdown-link">Manage
                                            Programmes</a>
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
                                    class="nav-link bg-slate-200/40 dark:bg-[#1e293b] text-slate-700 dark:text-slate-200 hover:text-rose-950 border border-rose-950/10 dark:border-[#1e3a5f]">Login</a>
                                <a href="{{ route('register') }}"
                                    class="nav-link bg-slate-200/40 dark:bg-[#1e293b] text-slate-700 dark:text-slate-200 hover:text-rose-950 border border-rose-950/10 dark:border-[#1e3a5f]">Register</a>
                            </div>
                        @endauth

                        <button onclick="toggleTheme()"
                            class="inline-flex h-9 w-9 md:h-10 md:w-10 shrink-0 items-center justify-center rounded-full glass-card border border-rose-950/10 dark:border-[#1e3a5f]/50 hover:border-rose-400/50 transition-all"
                            aria-label="Toggle Theme">
                            <svg class="theme-icon-dark w-4 h-4 text-rose-400 hidden" fill="currentColor"
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
                        <button onclick="toggleTheme()"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full glass-card border border-rose-950/10 dark:border-[#1e3a5f]/50 hover:border-rose-400/50 transition-all"
                            aria-label="Toggle Theme">
                            <svg class="theme-icon-dark w-4 h-4 text-rose-400 hidden" fill="currentColor"
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
                            class="w-9 h-9 rounded-xl flex items-center justify-center border border-rose-950/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400">
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
                class="md:hidden border-t border-rose-950/10 dark:border-[#1e3a5f] bg-white dark:bg-[#0f172a] px-4 py-4 space-y-3 shadow-2xl">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('home') ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : '' }}">Home</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('dashboard') ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : '' }}">Dashboard</a>
                @else
                    <a href="{{ route('submit-ticket') }}"
                        class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('submit-ticket') ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : '' }}">Submit
                        Ticket</a>
                @endauth
                <a href="{{ route('check-status') }}"
                    class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('check-status', 'search-tickets') ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : '' }}">Check
                    Status</a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('admin.users') ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : '' }}">Users
                            Management</a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage
                            Categories</a>
                        <a href="{{ route('admin.programmes.index') }}"
                            class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage
                            Programmes</a>
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
                            class="w-full text-left px-3 py-2 rounded-xl text-sm font-bold text-rose-500 hover:bg-rose-500/10">Sign
                            Out</button>
                    </form>
                @else
                    <div class="pt-2 border-t border-rose-950/10 dark:border-[#1e3a5f] flex gap-2">
                        <a href="{{ route('login') }}"
                            class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold bg-slate-100 dark:bg-[#1e293b] text-slate-700 dark:text-slate-200">Login</a>
                        <a href="{{ route('register') }}"
                            class="fauna-btn-primary flex-1 text-center !py-2.5 !rounded-xl !text-sm !font-bold shadow-md">Register</a>
                    </div>
                @endauth
            </div>
        </nav>
    @endif

    {{-- ── Page header slot (used by views that set <x-slot name="header">) ── --}}
    @if (isset($header))
        <header class="relative z-10 py-3 sm:py-4 md:py-8">
            <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                <div class="fauna-panel relative overflow-hidden p-4 sm:p-6 md:p-8">
                    <div
                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-rose-400 to-transparent opacity-40">
                    </div>
                    {{ $header }}
                </div>
            </div>
        </header>
    @endif

    {{-- ── Slot content ── --}}
    {{-- Slot content rendered inside main body wrapper --}}
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
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-[#0f172a] rounded-3xl shadow-2xl border border-slate-100 dark:border-[#1e3a5f] overflow-hidden p-8"
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
                        'bg-rose-500/10 border border-rose-500/20': type !== 'danger' && type !== 'warning',
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
                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight"
                    x-text="deleting ? title.replace(/^(Delete|Remove)/, (m) => m === 'Delete' ? 'Deleting' : 'Removing') + '...' : title">
                </h3>
            </div>

            {{-- Message --}}
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 leading-relaxed"
                x-text="deleting ? 'Please wait while the item is being removed…' : message"></p>

            {{-- Actions --}}
            <div class="flex gap-3 justify-end">
                <button @click="cancel()" x-bind:disabled="deleting"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-[#1e293b] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all border border-transparent disabled:opacity-40 disabled:cursor-not-allowed">
                    Cancel
                </button>
                <button @click="confirm()" x-bind:disabled="deleting"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:scale-100 flex items-center gap-2"
                    :class="{
                        'bg-rose-600 hover:bg-rose-700': type === 'danger',
                        'bg-amber-500 hover:bg-amber-600': type === 'warning',
                        'bg-rose-600 hover:bg-rose-800': type !== 'danger' && type !== 'warning',
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
                        x-text="deleting ? confirmText.replace(/^(Delete|Remove)(.*)?$/, (_, v, rest) => (v === 'Delete' ? 'Deleting' : 'Removing') + (rest || '') + '…') : confirmText"></span>
                </button>
            </div>
        </div>
    </div>
    {{-- ──────────────────────────────────────────────────────────────────────── --}}

    @stack('scripts')

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
                    `<svg class="${sizeClass} text-rose-500 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
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
</body>

</html>
