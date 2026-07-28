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
                        <div class="inline-flex items-center gap-3 text-white">
                            <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }} logo" class="h-12 w-12 drop-shadow-lg">
                            <span class="text-xl font-black tracking-tight text-white drop-shadow-md">{{ config('app.name') }}</span>
                        </div>

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

                <div class="pb-24 sm:pb-32 pt-16 sm:pt-24 text-center">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-white/80 text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] backdrop-blur-md mb-8">
                        Streamlined Institutional Requests
                    </div>

                    <h1 class="mx-auto mb-8 max-w-4xl text-5xl sm:text-6xl md:text-8xl font-black tracking-tighter text-white drop-shadow-2xl leading-[1.1]" style="text-shadow: 0 2px 20px rgba(0,0,0,0.4);">
                        Your Dedicated <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-pink-100 to-amber-200">Support System</span>
                    </h1>

                    <p class="mx-auto mb-12 max-w-2xl text-lg sm:text-xl text-white/90 font-medium leading-relaxed" style="text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
                        Providing a seamless process for students to request transcripts, certificates, letters of recommendation, and other official institutional documents.
                    </p>

                    <div class="flex flex-col items-center justify-center gap-4 sm:flex-row sm:gap-6">
                        <a href="{{ route('submit-ticket') }}"
                             class="group px-8 py-4 bg-white/10 text-white border border-white/20 rounded-full font-black text-sm tracking-widest hover:bg-white/20 hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-lg flex items-center justify-center gap-3 w-full sm:w-auto">
                            <span class="relative z-10">Create Ticket</span>
                        </a>

                        @if (auth()->user())
                        <a href="{{ route('dashboard') }}"
                            class="group px-8 py-4 bg-white/10 text-white border border-white/20 rounded-full font-black text-sm tracking-widest hover:bg-white/20 hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-lg flex items-center justify-center gap-3 w-full sm:w-auto">
                            <span>Dashboard</span>
                        </a>
                        @endif

                        <a href="{{ route('check-status') }}"
                            class="group px-8 py-4 bg-white/10 text-white border border-white/20 rounded-full font-black text-sm tracking-widest hover:bg-white/20 hover:scale-105 active:scale-95 transition-all backdrop-blur-md shadow-lg flex items-center justify-center gap-3 w-full sm:w-auto">
                            <span>Check Ticket Status</span>
                        </a>
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
