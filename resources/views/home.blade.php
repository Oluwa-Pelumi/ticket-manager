@php
// Static data — steps explaining the ticket lifecycle
$ticketingSteps = [
[
'step' => 1,
'title' => 'Submit',
'description' =>
'Create a ticket with the right category, your contact details, and what happened. Attach files if they help our team understand faster.',
],
[
'step' => 2,
'title' => 'Review',
'description' =>
'Our team sees your ticket on the dashboard, filters by status or priority, and updates progress—including assigning someone when needed.',
],
[
'step' => 3,
'title' => 'Reply in thread',
'description' =>
'Conversation stays on the ticket page: add comments or files anytime it is open. Check the same ticket for staff replies.',
],
[
'step' => 4,
'title' => 'Resolved',
'description' =>
'When your request is complete, the ticket is marked closed. You can still open past tickets from your dashboard or by status search with your email.',
],
];
@endphp

<x-app-layout :show-navbar="false">

    <x-slot name="title">Support System</x-slot>


    <div class="fauna-shell min-h-screen">
        {{-- Hero section — navigation and primary CTAs --}}
        <section class="relative overflow-hidden hero-gradient">
            {{-- Gorgeous blurred background elements --}}
            <div class="absolute inset-0 z-0">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-rose-300/20 dark:bg-rose-900/10 blur-[120px]"></div>
                <div class="absolute top-[20%] -right-[10%] w-[40%] h-[60%] rounded-full bg-pink-400/15 dark:bg-rose-900/10 blur-[120px]"></div>
                <div class="absolute -bottom-[20%] left-[20%] w-[60%] h-[40%] rounded-full bg-rose-200/15 dark:bg-rose-900/10 blur-[120px]"></div>
                <div class="absolute inset-0 bg-black/20 dark:bg-black/40 backdrop-blur-[1px]"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                {{-- Nav: always visible --}}
                <nav class="py-6">
                    <div class="flex items-center justify-between">
                        @php
                            $appName = rtrim(config('app.name'), ' .');
                            $nameParts = explode(',', $appName, 2);
                            $mainName = trim($nameParts[0]);
                            $subName = isset($nameParts[1]) ? trim($nameParts[1]) : '';
                        @endphp
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 sm:gap-3 text-white group shrink-0 min-w-0">
                            <img src="{{ asset('logo.svg') }}?v=3" alt="{{ $appName }} logo" class="w-10 h-10 sm:w-12 sm:h-12 drop-shadow-lg transition-transform group-hover:scale-105 shrink-0">
                            <div class="flex flex-col leading-none min-w-0">
                                <span class="text-xs sm:text-sm md:text-base lg:text-lg font-black tracking-tight text-fuchsia-300 drop-shadow-md truncate">
                                    {{ $mainName }}
                                </span>
                                @if($subName)
                                    <span class="text-[9px] sm:text-[10px] lg:text-xs font-bold tracking-wider uppercase text-white drop-shadow-md truncate mt-0.5">
                                        {{ $subName }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div class="flex items-center gap-3 sm:gap-4">
                            @guest
                            <a href="{{ route('login') }}" class="block sm:hidden px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs sm:text-sm font-bold backdrop-blur-md transition-all hover:scale-105 active:scale-95 shadow-lg">Login</a>
                            <a href="{{ route('login') }}" class="hidden sm:block px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs sm:text-sm font-bold backdrop-blur-md transition-all hover:scale-105 active:scale-95 shadow-lg">Login</a>
                            <a href="{{ route('register') }}" class="hidden sm:block px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs sm:text-sm font-bold backdrop-blur-md transition-all hover:scale-105 active:scale-95 shadow-lg">Register</a>
                            @else
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs sm:text-sm font-bold backdrop-blur-md transition-all hover:scale-105 active:scale-95 shadow-lg">Dashboard</a>
                            @endauth
                            <button onclick="toggleTheme()" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 hover:bg-white/10 border border-white/10 text-white transition-all backdrop-blur-md hover:scale-110 active:scale-95" aria-label="Toggle Theme">
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
                    </div>
                </nav>

                <div class="pb-20 sm:pb-28 pt-12 sm:pt-20">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                        {{-- Left Column: Text & CTAs --}}
                        <div class="lg:col-span-7 text-center lg:text-left">
                            {{-- Badge --}}
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-white/90 text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] backdrop-blur-md mb-6 shadow-sm">
                                Streamlined Institutional Requests
                            </div>

                            <h1 class="mb-6 max-w-2xl text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tighter text-white drop-shadow-2xl leading-[1.1]" style="text-shadow: 0 2px 20px rgba(0,0,0,0.4);">
                                Your Dedicated <br />
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-amber-200">Support System</span>
                            </h1>

                            <p class="mb-8 max-w-xl text-base sm:text-lg lg:text-xl text-white/90 font-medium leading-relaxed" style="text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
                                Providing a seamless process for students to request academic related documents and other official institutional documents with real-time tracking.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                                <a href="{{ route('submit-ticket') }}"
                                     class="group px-7 py-3.5 bg-white/15 text-white border border-white/25 rounded-full font-black text-xs sm:text-sm tracking-widest uppercase hover:bg-white/25 hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-xl flex items-center justify-center gap-2.5 w-full sm:w-auto">
                                    <svg class="w-4 h-4 text-pink-200 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Create Ticket</span>
                                </a>

                                @if (auth()->user())
                                <a href="{{ route('dashboard') }}"
                                    class="group px-7 py-3.5 bg-white/15 text-white border border-white/25 rounded-full font-black text-xs sm:text-sm tracking-widest uppercase hover:bg-white/25 hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-xl flex items-center justify-center gap-2.5 w-full sm:w-auto">
                                    <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                                @endif

                                <a href="{{ route('check-status') }}"
                                    class="group px-7 py-3.5 bg-white/10 text-white/90 border border-white/15 rounded-full font-bold text-xs sm:text-sm tracking-widest uppercase hover:bg-white/20 hover:text-white hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-lg flex items-center justify-center gap-2.5 w-full sm:w-auto">
                                    <svg class="w-4 h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span>Check Status</span>
                                </a>
                            </div>

                            {{-- Trust / Highlight badges --}}
                            <div class="pt-6 border-t border-white/10 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs sm:text-sm font-semibold text-white/80">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span>Instant Tracking</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-400/40 flex items-center justify-center text-rose-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <span>Secure Requests</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-amber-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span>Fast Resolution</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Static Support Illustration --}}
                        <div class="lg:col-span-5 relative flex justify-center items-center mt-6 lg:mt-0">
                            {{-- Multi-layered ambient radial lighting (Hidden in dark mode) --}}
                            <div class="absolute -inset-6 bg-gradient-to-tr from-rose-500/30 via-pink-400/20 to-amber-300/30 rounded-full blur-3xl pointer-events-none dark:hidden"></div>

                            {{-- Static Illustration (Seamlessly blended, static layout) --}}
                            <div class="relative z-10 w-full max-w-lg">
                                <img src="{{ asset('hero-illustration-1.png') }}?v=2"
                                     alt="Support System Illustration"
                                     class="w-full h-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)] dark:drop-shadow-none" />

                                {{-- Micro Badge 1 (Top Right) --}}
                                <div class="absolute -top-2 -right-2 sm:-right-4 px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white shadow-2xl backdrop-blur-xl flex items-center gap-2.5 text-xs sm:text-sm font-bold">
                                    <span class="text-base">✨</span>
                                    <span>24/7 Support Desk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        {{-- Admin/support ticket stats --}}
        @if (auth()->user()?->role === 'admin' || auth()->user()?->role === 'support')
        <section class="py-14">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 gap-8 text-center md:grid-cols-4">
                    @foreach ([['label' => 'Total Tickets', 'value' => $stats['totalTickets'] ?? 0], ['label' => 'Open Tickets', 'value' => $stats['openTickets'] ?? 0], ['label' => 'In Progress', 'value' => $stats['inProgressTickets'] ?? 0], ['label' => 'Resolved Tickets', 'value' => $stats['resolvedTickets'] ?? 0]] as $stat)
                    <div class="fauna-panel p-8 dark:bg-[#0f172a] dark:border-[#1e3a5f]">
                        <h3 class="text-3xl font-semibold">{{ $stat['value'] }}</h3>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- How ticketing works — step-by-step guide --}}
        <section class="py-20 bg-slate-50/50 dark:bg-[#020617] relative overflow-hidden">
            {{-- Decorative background elements --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full pointer-events-none">
                <div class="absolute top-20 right-10 w-96 h-96 bg-rose-400/5 dark:bg-rose-400/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-72 h-72 bg-indigo-400/5 dark:bg-indigo-400/10 rounded-full blur-3xl"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-black tracking-widest text-rose-600 dark:text-rose-400 uppercase mb-3 block">Process overview</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-6">How ticketing works</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 font-medium">
                        From your first message to a closed ticket—here is what happens.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto mb-16 relative">
                    {{-- Connecting line for desktop --}}
                    <div class="hidden lg:block absolute top-[2.5rem] left-[12.5%] right-[12.5%] h-0.5 bg-gradient-to-r from-rose-100 via-rose-200 to-rose-100 dark:from-rose-900/50 dark:via-rose-800/50 dark:to-rose-900/50 z-0"></div>

                    @foreach ($ticketingSteps as $index => $item)
                    <div class="group relative z-10">
                        {{-- Step card --}}
                        <div class="h-full p-8 rounded-[2rem] bg-white dark:bg-[#0f172a] border border-rose-100 dark:border-[#1e3a5f] shadow-xl shadow-rose-900/5 dark:shadow-none hover:-translate-y-2 hover:shadow-2xl hover:shadow-rose-900/10 dark:hover:border-rose-400/30 transition-all duration-300">

                            {{-- Step number badge --}}
                            <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/30 flex items-center justify-center text-2xl font-black text-rose-600 dark:text-rose-400 mb-8 group-hover:scale-110 group-hover:bg-rose-100 dark:group-hover:bg-rose-900/40 transition-all duration-300">
                                {{ $item['step'] }}
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                {{ $item['description'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center justify-center gap-4 pt-8">
                    <a href="{{ route('submit-ticket') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-rose-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-rose-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                       Create ticket
                    </a>

                    <a href="{{ route('check-status') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-rose-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-rose-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                        Check status
                    </a>
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-rose-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-rose-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                        Dashboard
                    </a>
                    @endauth
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="mx-auto mb-10 max-w-5xl text-4xl font-semibold">
                    Need to request a transcript, certificate, or other official documents?
                </h2>
            </div>
        </section>

        {{-- FAQ section --}}
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div class="mb-12 text-center">
                    <h2 class="text-5xl font-semibold">FAQ</h2>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Here you will find answers to frequently asked
                        questions.</p>
                </div>
                <div class="mx-auto max-w-4xl space-y-4">
                    @if (!empty($faqs) && count($faqs) > 0)
                    @foreach ($faqs as $item)
                    <details class="fauna-panel p-6 dark:bg-[#0f172a] dark:border-[#1e3a5f]">
                        <summary class="cursor-pointer font-medium">{{ $item['question'] ?? $item->question }}
                        </summary>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">
                            {{ $item['answer'] ?? $item->answer }}
                        </p>
                    </details>
                    @endforeach
                    @else
                    <div class="text-center p-8 fauna-panel dark:bg-[#0f172a] dark:border-[#1e3a5f]">
                        <p class="text-slate-600 dark:text-slate-400">No frequently asked questions are available at
                            this time.</p>
                    </div>
                    @endif
                </div>
            </div>
        </section>


        <section class="bg-rose-50/50 py-16 dark:bg-[#020617]">
            <div class="container mx-auto px-4">
                @if (session('success') || session('error') || session('status'))
                <x-flash-handler />
                @endif

                <div class="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-4">

                    <div class="grid grid-cols-2 gap-8 md:grid-cols-3 lg:col-span-2">

                    </div>
                </div>
            </div>
        </section>
    </div>

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
    </script>
</x-app-layout>
