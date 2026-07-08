<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) && trim($title) ? trim($title) . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    {{-- Dark mode: apply theme before paint to avoid flash --}}
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                if (theme === 'dark') document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom component styles --}}
    <style>
        html, body {
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

        .fauna-panel {
            border-radius: 1.5rem;
            border: 1px solid rgba(30, 58, 138, 0.1);
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
            background-color: rgba(16, 40, 36, 0.75);
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
        .dark .nav-link { color: rgb(148 163 184); }
        .nav-link:hover, .nav-link-active {
            background-color: #ffffff;
            color: rgb(30 58 138);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        .dark .nav-link:hover, .dark .nav-link-active {
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
        .dark .dropdown-link { color: rgb(203 213 225); }
        .dropdown-link:hover {
            background-color: rgb(241 245 249);
            color: rgb(30 58 138);
            padding-left: 1.25rem;
        }
        .dark .dropdown-link:hover {
            background-color: #1e293b;
            color: rgb(56 189 248);
        }

        .fauna-btn-primary {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 9999px; border: 1px solid #1e3a8a; background-color: #1e3a8a;
            padding: 0.75rem 1.5rem; font-size: 0.875rem; font-weight: 500;
            color: #ffffff; transition: all 0.2s; text-decoration: none;
        }
        .fauna-btn-primary:hover { border-color: #3b82f6; background-color: #3b82f6; color: #1e3a8a; }

        .fauna-btn-secondary {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 9999px; border: 1px solid #1e3a8a;
            padding: 0.75rem 1.5rem; font-size: 0.875rem; font-weight: 500;
            color: #1e3a8a; transition: all 0.2s; text-decoration: none;
        }
        .dark .fauna-btn-secondary { border-color: rgb(71 85 105); color: rgb(241 245 249); }
        .fauna-btn-secondary:hover { background-color: #1e3a8a; color: #ffffff; }
        .dark .fauna-btn-secondary:hover { background-color: rgb(30 41 59); }

        .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%,   rgba(30, 58, 138,.16)   0px, transparent 50%),
                radial-gradient(at 100% 0%,  rgba(56, 189, 248,.14) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138,.08)   0px, transparent 50%),
                radial-gradient(at 0% 100%,  rgba(56, 189, 248,.1)  0px, transparent 50%);
        }
        .dark .mesh-gradient {
            background-image:
                radial-gradient(at 0% 0%,   rgba(30, 58, 138,.2)   0px, transparent 50%),
                radial-gradient(at 100% 0%,  rgba(56, 189, 248,.1)  0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138,.12)  0px, transparent 50%),
                radial-gradient(at 0% 100%,  rgba(56, 189, 248,.08) 0px, transparent 50%);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(241,245,249,.5); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgb(56 189 248); }
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(24,52,47,.3); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgb(51 65 85); }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: rgb(203 213 225) transparent; }
        .dark .custom-scrollbar { scrollbar-color: rgb(51 65 85) transparent; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased fauna-shell">

    @include('components.flash-handler')

    {{-- ── Global navbar ── --}}
    @if($showNavbar ?? true)
    <nav class="relative z-50 border-b border-blue-900/10 bg-white shadow-md dark:border-[#1e3a5f] dark:bg-[#0f172a]" x-data="{ showingMobileMenu: false }">
        <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
            <div class="flex h-20 justify-between items-center">
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }} logo" class="w-9 h-9">
                        <span class="hidden sm:block text-xl font-black tracking-tight text-slate-900 dark:text-white">
                            {{ config('app.name') }}<span class="text-sky-400">.</span>
                        </span>
                    </a>

                    <div class="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#0f172a]/70 rounded-2xl border border-blue-900/10 dark:border-[#1e3a5f] backdrop-blur-md">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                        @else
                            <a href="{{ route('submit-ticket') }}" class="nav-link {{ request()->routeIs('submit-ticket') ? 'nav-link-active' : '' }}">Submit Ticket</a>
                        @endauth
                        <a href="{{ route('check-status') }}" class="nav-link {{ request()->routeIs('check-status', 'search-tickets') ? 'nav-link-active' : '' }}">Check Status</a>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'nav-link-active' : '' }}">Users Management</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center gap-4">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isSupport())
                        <div class="relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                type="button"
                                class="relative w-10 h-10 rounded-2xl flex items-center justify-center border border-blue-900/10/50 bg-white text-slate-600 transition-all hover:text-blue-900 dark:border-[#1e3a5f] dark:bg-[#0f172a] dark:text-slate-400 group"
                                title="{{ count($due_tickets) }} orders due for processing"
                            >
                                <svg
                                    class="w-5 h-5 {{ count($due_tickets) > 0 ? 'animate-bounce text-sky-400' : '' }}"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    />
                                </svg>
                                @if(count($due_tickets) > 0)
                                    <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white shadow-lg ring-2 ring-white dark:ring-[#0f172a]">
                                        {{ count($due_tickets) }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                class="absolute right-0 mt-2 w-80 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl border border-blue-900/10 dark:border-[#1e3a5f] py-1 z-50 shadow-xl">
                                <div class="p-4 border-b border-slate-100 dark:border-[#1e3a5f]">
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">
                                        Due for Processing
                                    </h3>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                        Copy the ticket reference and paste in the searchbox to attend to tickets order
                                    </p>
                                </div>
                                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                    @if(count($due_tickets) > 0)
                                        @foreach($due_tickets as $ticket)
                                            <div class="block p-4 hover:bg-blue-50/50 dark:hover:bg-[#1e293b] transition-colors border-b border-slate-100 dark:border-[#1e3a5f] last:border-0 text-left">
                                                <div class="flex justify-between items-start mb-1 gap-2">
                                                    <a
                                                        href="{{ route('dashboard') }}"
                                                        class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 hover:text-blue-900 dark:hover:text-sky-400"
                                                    >
                                                        {{ ucfirst($ticket->subject) }}
                                                    </a>
                                                    <span class="text-[9px] font-black uppercase bg-sky-400/10 text-sky-500 dark:text-sky-400 px-1.5 py-0.5 rounded shrink-0">
                                                        {{ $ticket->period }}
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 mb-2 truncate">
                                                    {{ Str::limit($ticket->content, 32) }}
                                                </div>
                                                <div class="flex items-center justify-between gap-4">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[9px] font-mono font-black text-slate-400">
                                                            {{ $ticket->hashid }}
                                                        </span>
                                                        <button
                                                            type="button"
                                                            onclick="copyToClipboard('{{ $ticket->hashid }}', this)"
                                                            class="p-1 rounded bg-slate-100 dark:bg-[#1e293b] text-slate-400 hover:text-blue-900 dark:hover:text-sky-400 transition-all"
                                                            title="Copy Reference"
                                                        >
                                                            <svg class="w-3 h-3 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
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
                                @if(count($due_tickets) > 0)
                                    <div class="p-3 bg-slate-50 dark:bg-[#020617]/50 text-center">
                                        <a
                                            href="{{ route('dashboard') }}"
                                            class="text-[10px] font-black text-blue-900 dark:text-sky-400 uppercase tracking-widest hover:underline"
                                        >
                                            View all in Dashboard
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endauth

                    <button onclick="toggleTheme()" class="w-10 h-10 rounded-2xl flex items-center justify-center border border-blue-900/10 bg-white text-slate-600 hover:text-blue-900 dark:border-[#1e3a5f] dark:bg-[#0f172a] dark:text-slate-400" aria-label="Toggle theme">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                    </button>

                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center gap-3 p-1.5 pr-4 rounded-2xl glass-card border border-blue-900/10 dark:border-[#1e3a5f]/50 hover:border-sky-400/50 transition-all">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center text-white text-[10px] font-black shadow-lg">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 rounded-2xl overflow-hidden bg-white/80 dark:bg-[#0f172a]/90 backdrop-blur-xl border border-blue-900/10 dark:border-[#1e3a5f] py-1 z-50 shadow-xl">
                            <a href="{{ route('profile.edit') }}" class="dropdown-link">Profile Settings</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.categories.index') }}" class="dropdown-link">Manage Categories</a>
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
                        <a href="{{ route('login') }}" class="nav-link bg-slate-200/40 dark:bg-[#1e293b] text-slate-700 dark:text-slate-200 hover:text-blue-900 border border-blue-900/10 dark:border-[#1e3a5f]">Login</a>
                        <a href="{{ route('register') }}" class="nav-link bg-blue-900 text-white hover:bg-[#3b82f6] hover:text-[#1e3a8a] shadow-md">Register</a>
                    </div>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <div class="flex sm:hidden items-center gap-2">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isSupport())
                        <div class="relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                type="button"
                                class="relative w-9 h-9 rounded-xl flex items-center justify-center border border-blue-900/10 dark:border-[#1e3a5f] bg-white dark:bg-[#0f172a] text-slate-600 dark:text-slate-400 group"
                                title="{{ count($due_tickets) }} orders due for processing"
                            >
                                <svg
                                    class="w-4 h-4 {{ count($due_tickets) > 0 ? 'animate-bounce text-sky-400' : '' }}"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    />
                                </svg>
                                @if(count($due_tickets) > 0)
                                    <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[8px] font-black text-white shadow-lg ring-2 ring-white dark:ring-[#0f172a]">
                                        {{ count($due_tickets) }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                class="fixed inset-x-4 top-24 rounded-2xl overflow-hidden bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl border border-blue-900/10 dark:border-[#1e3a5f] py-1 z-50 shadow-xl max-w-sm mx-auto">
                                <div class="p-4 border-b border-slate-100 dark:border-[#1e3a5f] text-left">
                                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">
                                        Due for Processing
                                    </h3>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                        Copy the ticket reference and paste in the searchbox to attend to tickets order
                                    </p>
                                </div>
                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    @if(count($due_tickets) > 0)
                                        @foreach($due_tickets as $ticket)
                                            <div class="block p-4 hover:bg-blue-50/50 dark:hover:bg-[#1e293b] transition-colors border-b border-slate-100 dark:border-[#1e3a5f] last:border-0 text-left">
                                                <div class="flex justify-between items-start mb-1 gap-2">
                                                    <a
                                                        href="{{ route('dashboard') }}"
                                                        class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 hover:text-blue-900 dark:hover:text-sky-400"
                                                    >
                                                        {{ ucfirst($ticket->subject) }}
                                                    </a>
                                                    <span class="text-[9px] font-black uppercase bg-sky-400/10 text-sky-500 dark:text-sky-400 px-1.5 py-0.5 rounded shrink-0">
                                                        {{ $ticket->period }}
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 mb-2 truncate">
                                                    {{ Str::limit($ticket->content, 32) }}
                                                </div>
                                                <div class="flex items-center justify-between gap-4">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[9px] font-mono font-black text-slate-400">
                                                            {{ $ticket->hashid }}
                                                        </span>
                                                        <button
                                                            type="button"
                                                            onclick="copyToClipboard('{{ $ticket->hashid }}', this)"
                                                            class="p-1 rounded bg-slate-100 dark:bg-[#1e293b] text-slate-400 hover:text-blue-900 dark:hover:text-sky-400 transition-all"
                                                            title="Copy Reference"
                                                        >
                                                            <svg class="w-3 h-3 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
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
                                @if(count($due_tickets) > 0)
                                    <div class="p-3 bg-slate-50 dark:bg-[#020617]/50 text-center">
                                        <a
                                            href="{{ route('dashboard') }}"
                                            class="text-[10px] font-black text-blue-900 dark:text-sky-400 uppercase tracking-widest hover:underline"
                                        >
                                            View all in Dashboard
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endauth

                    <button onclick="toggleTheme()" class="w-9 h-9 rounded-xl flex items-center justify-center border border-blue-900/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                    </button>

                    <button @click="showingMobileMenu = !showingMobileMenu" class="w-9 h-9 rounded-xl flex items-center justify-center border border-blue-900/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingMobileMenu, 'inline-flex': !showingMobileMenu }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !showingMobileMenu, 'inline-flex': showingMobileMenu }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div x-show="showingMobileMenu" x-cloak class="sm:hidden border-t border-blue-900/10 dark:border-[#1e3a5f] bg-white dark:bg-[#0f172a] px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('home') ? 'bg-blue-500/10 text-blue-600 dark:text-sky-400' : '' }}">Home</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('dashboard') ? 'bg-blue-500/10 text-blue-600 dark:text-sky-400' : '' }}">Dashboard</a>
            @else
                <a href="{{ route('submit-ticket') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('submit-ticket') ? 'bg-blue-500/10 text-blue-600 dark:text-sky-400' : '' }}">Submit Ticket</a>
            @endauth
            <a href="{{ route('check-status') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('check-status', 'search-tickets') ? 'bg-blue-500/10 text-blue-600 dark:text-sky-400' : '' }}">Check Status</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 {{ request()->routeIs('admin.users') ? 'bg-blue-500/10 text-blue-600 dark:text-sky-400' : '' }}">Users Management</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage Categories</a>
                    <a href="{{ route('admin.faqs.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Manage FAQs</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200">Profile Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-xl text-sm font-bold text-rose-500">Sign Out</button>
                </form>
            @else
                <div class="pt-4 border-t border-blue-900/10 dark:border-[#1e3a5f] flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold bg-slate-100 dark:bg-[#1e293b] text-slate-700 dark:text-slate-200">Login</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold bg-blue-900 text-white hover:bg-[#3b82f6] hover:text-[#1e3a8a] shadow-md">Register</a>
                </div>
            @endauth
        </div>
    </nav>
    @endif

    {{-- ── Page header slot (used by views that set <x-slot name="header">) ── --}}
    @if (isset($header))
    <header class="relative z-10 py-3 md:py-10">
        <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
            <div class="fauna-panel relative overflow-hidden p-4 sm:p-6 md:p-10">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-400 to-transparent opacity-40"></div>
                {{ $header }}
            </div>
        </div>
    </header>
    @endif

    {{-- ── Slot content ── --}}
    {{-- For direct component use (e.g. dashboard), $slot is the page content.        --}}
    {{-- For @extends('layouts.authenticated'), $slot is the full layout HTML from    --}}
    {{-- authenticated.blade.php which already includes its own header/main wrappers. --}}
    {{ $slot ?? '' }}

    @include('components.footer')

    {{-- ── Global Confirm Modal ──────────────────────────────────────────────── --}}
    {{-- Listens for: $dispatch('confirm', { type, title, message, confirmText, onConfirm }) --}}
    {{-- Used by: categories delete, faqs delete, dashboard bulk actions, etc.              --}}
    <div
        id="global-confirm-modal"
        x-data="{
            open: false,
            type: 'danger',
            title: '',
            message: '',
            confirmText: 'Confirm',
            onConfirm: null,

            init() {
                window.addEventListener('confirm', (e) => {
                    this.type        = e.detail?.type        ?? 'danger';
                    this.title       = e.detail?.title       ?? 'Are you sure?';
                    this.message     = e.detail?.message     ?? '';
                    this.confirmText = e.detail?.confirmText ?? 'Confirm';
                    this.onConfirm   = e.detail?.onConfirm   ?? null;
                    this.open        = true;
                });
            },

            confirm() {
                if (typeof this.onConfirm === 'function') this.onConfirm();
                this.open = false;
            },

            cancel() {
                this.open = false;
            }
        }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @keydown.escape.window="cancel()"
    >
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancel()"></div>

        {{-- Panel --}}
        <div
            class="relative z-10 w-full max-w-md bg-white dark:bg-[#0f172a] rounded-3xl shadow-2xl border border-slate-100 dark:border-[#1e3a5f] overflow-hidden p-8"
            @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Icon --}}
            <div class="flex items-center gap-4 mb-5">
                <div
                    class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                    :class="{
                        'bg-rose-500/10 border border-rose-500/20': type === 'danger',
                        'bg-amber-500/10 border border-amber-500/20': type === 'warning',
                        'bg-blue-500/10 border border-blue-500/20': type !== 'danger' && type !== 'warning',
                    }"
                >
                    <svg class="w-6 h-6"
                        :class="{
                            'text-rose-500': type === 'danger',
                            'text-amber-500': type === 'warning',
                            'text-blue-500': type !== 'danger' && type !== 'warning',
                        }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight" x-text="title"></h3>
            </div>

            {{-- Message --}}
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 leading-relaxed" x-text="message"></p>

            {{-- Actions --}}
            <div class="flex gap-3 justify-end">
                <button
                    @click="cancel()"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-[#1e293b] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all border border-transparent"
                >
                    Cancel
                </button>
                <button
                    @click="confirm()"
                    class="px-5 py-2.5 rounded-2xl text-xs font-black tracking-widest text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]"
                    :class="{
                        'bg-rose-600 hover:bg-rose-700': type === 'danger',
                        'bg-amber-500 hover:bg-amber-600': type === 'warning',
                        'bg-blue-600 hover:bg-blue-700': type !== 'danger' && type !== 'warning',
                    }"
                    x-text="confirmText"
                ></button>
            </div>
        </div>
    </div>
    {{-- ──────────────────────────────────────────────────────────────────────── --}}

    @stack('scripts')

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (e) {}
        }

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
                success: { color: '#3b82f6', label: 'Success', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>' },
                error:   { color: '#f43f5e', label: 'Error',   icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>' },
                warning: { color: '#f59e0b', label: 'Warning', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>' },
                info:    { color: '#3b82f6', label: 'Info',    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>' },
            };
            const cfg = configs[type] || configs.success;

            const isDark = document.documentElement.classList.contains('dark');
            const bg        = isDark ? 'rgba(15,23,42,0.97)'    : '#ffffff';
            const borderMid = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.07)';
            const textColor = isDark ? '#f1f5f9'                : '#1e293b';

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto flex items-start gap-3 px-4 py-3.5 rounded-2xl shadow-xl transition-all duration-300 transform -translate-y-4 opacity-0';
            toast.style.cssText = `background:${bg};border:1px solid ${borderMid};border-left:4px solid ${cfg.color};box-shadow:0 8px 32px rgba(0,0,0,0.12)`;

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
                icon.outerHTML = `<svg class="${sizeClass} text-blue-500 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
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