<<<<<<< HEAD
﻿@php
// Static data â€” steps explaining the ticket lifecycle
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
'Our team sees your ticket on the dashboard, filters by status or priority, and updates progressâ€”including assigning someone when needed.',
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
=======
@php
    // Static data — steps explaining the ticket lifecycle
    $ticketingSteps = [
        [
            'step' => 1,
            'title' => 'Submit',
            'description' =>
                'Create a ticket with the right category, your contact details, and what happened. Attach images if they help our team understand faster.',
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
            'title' => 'Closed',
            'description' =>
                'When your request is complete, the ticket is marked closed. You can still open past tickets from your dashboard or by status search with your email.',
        ],
    ];
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
@endphp

<x-app-layout :show-navbar="false">

    <x-slot name="title">Support System</x-slot>


    <div class="fauna-shell min-h-screen">
<<<<<<< HEAD
        {{-- Hero section â€” navigation and primary CTAs --}}
        <section class="relative overflow-hidden hero-gradient">
            {{-- Gorgeous blurred background elements --}}
            <div class="absolute inset-0 z-0">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-300/20 dark:bg-emerald-900/10 blur-[120px]"></div>
                <div class="absolute top-[20%] -right-[10%] w-[40%] h-[60%] rounded-full bg-pink-400/15 dark:bg-emerald-900/10 blur-[120px]"></div>
                <div class="absolute -bottom-[20%] left-[20%] w-[60%] h-[40%] rounded-full bg-emerald-200/15 dark:bg-emerald-900/10 blur-[120px]"></div>
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
                                <span class="text-xs sm:text-sm md:text-base lg:text-lg font-black tracking-tight text-white drop-shadow-md truncate">
                                    {{ $mainName }}
                                </span>
                                @if($subName)
                                    <span class="text-[9px] sm:text-[10px] lg:text-xs font-bold tracking-wider uppercase text-emerald-300 drop-shadow-md truncate mt-0.5">
                                        {{ $subName }}
                                    </span>
                                @endif
                            </div>
                        </a>
=======
        {{-- Hero section — navigation and primary CTAs --}}
        <section class="relative overflow-hidden bg-teal-900 dark:bg-[#102824]">
            <div class="container mx-auto px-4">
                {{-- Nav: always visible — shows Login+Register for guests, Dashboard for auth users --}}
                <nav class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-3 text-white">
                            <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }} logo" class="h-8 w-8">
                            <span class="text-xl font-semibold tracking-tight">{{ config('app.name') }}</span>
                        </div>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)

                        <div class="flex items-center gap-2 sm:gap-3">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Register
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Dashboard
                                </a>
                            @endauth
<<<<<<< HEAD
                            <button onclick="toggleTheme()" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 hover:bg-white/10 border border-white/10 text-white transition-all backdrop-blur-md hover:scale-110 active:scale-95" aria-label="Toggle Theme">
                                <svg class="theme-icon-dark w-4 h-4 text-emerald-400 hidden" fill="currentColor"
=======
                            <button onclick="toggleTheme()"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 text-white transition hover:border-[#10b981] hover:text-[#10b981] dark:border-[#10b981]/40"
                                aria-label="Toggle Theme">
                                <svg id="theme-icon-dark" class="w-5 h-5 text-[#10b981] hidden" fill="currentColor"
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                                </svg>
                                <svg id="theme-icon-light" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </nav>

                <div class="pb-16 sm:pb-20 pt-12 sm:pt-16 text-center">
                    <h1
                        class="mx-auto mb-6 sm:mb-8 max-w-3xl text-4xl sm:text-5xl md:text-7xl font-medium tracking-tight text-white">
                        Your Dedicated Support System
                    </h1>
                    <p class="mx-auto mb-8 sm:mb-10 max-w-2xl text-base sm:text-lg text-white/80 px-2">
                        Providing seamless assistance for prescriptions, refills, and all your medication concerns with
                        professional care.
                    </p>
                    <div class="flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('submit-ticket') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Create Ticket
                        </a>

                        <a href="{{ auth()->user() ? route('dashboard') : route('check-status') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            View Ticket
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
                        @foreach ([['label' => 'Total Tickets', 'value' => $stats['totalTickets'] ?? 0], ['label' => 'Open Tickets', 'value' => $stats['openTickets'] ?? 0], ['label' => 'In Progress', 'value' => $stats['inProgressTickets'] ?? 0], ['label' => 'closed Tickets', 'value' => $stats['closedTickets'] ?? 0]] as $stat)
                            <div class="fauna-panel p-8 dark:bg-[#102824] dark:border-[#1d3a34]">
                                <h3 class="text-3xl font-semibold">{{ $stat['value'] }}</h3>
                                <p class="mt-2 text-slate-600 dark:text-slate-400">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

<<<<<<< HEAD
        {{-- How ticketing works â€” step-by-step guide --}}
        <section class="py-20 bg-slate-50/50 dark:bg-[#020617] relative overflow-hidden">
            {{-- Decorative background elements --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full pointer-events-none">
                <div class="absolute top-20 right-10 w-96 h-96 bg-emerald-400/5 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-72 h-72 bg-indigo-400/5 dark:bg-indigo-400/10 rounded-full blur-3xl"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-sm font-black tracking-widest text-emerald-600 dark:text-emerald-400 uppercase mb-3 block">Process overview</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-6">How ticketing works</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 font-medium">
                        From your first message to a closed ticketâ€”here is what happens.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto mb-16 relative">
                    {{-- Connecting line for desktop --}}
                    <div class="hidden lg:block absolute top-[2.5rem] left-[12.5%] right-[12.5%] h-0.5 bg-gradient-to-r from-emerald-100 via-emerald-200 to-emerald-100 dark:from-emerald-900/50 dark:via-emerald-800/50 dark:to-emerald-900/50 z-0"></div>

                    @foreach ($ticketingSteps as $index => $item)
                    <div class="group relative z-10">
                        {{-- Step card --}}
                        <div class="h-full p-8 rounded-[2rem] bg-white dark:bg-[#0f172a] border border-emerald-100 dark:border-[#1e3a5f] shadow-xl shadow-emerald-900/5 dark:shadow-none hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-900/10 dark:hover:border-emerald-400/30 transition-all duration-300">

                            {{-- Step number badge --}}
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 flex items-center justify-center text-2xl font-black text-emerald-600 dark:text-emerald-400 mb-8 group-hover:scale-110 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 transition-all duration-300">
                                {{ $item['step'] }}
=======
        {{-- How ticketing works — step-by-step guide --}}
        <section class="p-4 bg-white dark:bg-[#0b1715]">
            <div class="rounded-3xl bg-emerald-700 px-6 py-16 dark:bg-[#102824] dark:border dark:border-[#1d3a34]">
                <div class="container mx-auto px-4">
                    <h2 class="mb-4 text-4xl font-semibold text-white">How ticketing works</h2>
                    <p class="mb-12 max-w-2xl text-white/80">
                        From your first message to a closed ticket—here is what happens in {{ config('app.name') }}.
                    </p>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        @foreach ($ticketingSteps as $item)
                            <div class="rounded-2xl bg-white p-8 dark:bg-[#18342f] dark:border dark:border-[#28524a]">
                                <span
                                    class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-teal-900 text-sm font-semibold text-white dark:bg-lime-500 dark:text-[#102824]">
                                    {{ $item['step'] }}
                                </span>
                                <h3 class="text-2xl font-medium text-teal-900 dark:text-white">{{ $item['title'] }}
                                </h3>
                                <p class="mt-3 text-slate-600 dark:text-slate-300">{{ $item['description'] }}</p>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            </div>
                        @endforeach
                    </div>
                    <div
                        class="mt-12 flex flex-wrap items-center justify-center gap-4 border-t border-white/20 pt-10 dark:border-[#28524a]">
                        <a href="{{ route('submit-ticket') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Create ticket
                        </a>

<<<<<<< HEAD
                <div class="flex flex-wrap items-center justify-center gap-4 pt-8">
                    <a href="{{ route('submit-ticket') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-emerald-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-emerald-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                       Create ticket
                    </a>

                    <a href="{{ route('check-status') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-emerald-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-emerald-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                        Check status
                    </a>
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-8 py-4 bg-white dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 border border-emerald-400 dark:border-[#1e3a5f] rounded-[2rem] font-black text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-[#0f172a] hover:text-emerald-950 dark:hover:text-white transition-all inline-flex items-center gap-3">
                        Dashboard
                    </a>
                    @endauth
=======
                        <a href="{{ route('check-status') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Check status
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                Dashboard
                            </a>
                        @endauth
                    </div>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="mx-auto mb-10 max-w-5xl text-4xl font-semibold">
                    Need help with a prescription, refill, or medication concern?
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
                            <details class="fauna-panel p-6 dark:bg-[#102824] dark:border-[#1d3a34]">
                                <summary class="cursor-pointer font-medium">{{ $item['question'] ?? $item->question }}
                                </summary>
                                <p class="mt-3 text-slate-600 dark:text-slate-400">
                                    {{ $item['answer'] ?? $item->answer }}</p>
                            </details>
                        @endforeach
                    @else
                        <div class="text-center p-8 fauna-panel dark:bg-[#102824] dark:border-[#1d3a34]">
                            <p class="text-slate-600 dark:text-slate-400">No frequently asked questions are available at
                                this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>


<<<<<<< HEAD
        <section class="bg-emerald-50/50 py-16 dark:bg-[#020617]">
=======
        <section class="bg-orange-50 py-16 dark:bg-[#0b1715]">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
            <div class="container mx-auto px-4">
                @if (session('success') || session('error') || session('status'))
                    <x-flash-handler />
                @endif

                <div class="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-4">
                    <div>
                        <div class="mb-4 inline-flex items-center gap-2">
                            <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }} logo" class="h-6 w-6">
                            <span
                                class="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">{{ config('app.name') }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-8 md:grid-cols-3 lg:col-span-2">

                    </div>
                    <div class="rounded-2xl bg-teal-900 p-6 dark:bg-[#102824] dark:border dark:border-[#1d3a34]">
                        <h4 class="mb-3 text-xl font-medium text-white">Open a new support request</h4>
                        <p class="mb-6 text-white/80">Use the ticket system to report issues, request updates, or ask
                            for help.</p>
                        <a href="{{ route('submit-ticket') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-[#10b981]/50 dark:hover:!bg-[#10b981] dark:hover:!text-[#064e3b] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Create Ticket</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @guest
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
            const darkIcon = document.getElementById('theme-icon-dark');
            const lightIcon = document.getElementById('theme-icon-light');
            if (darkIcon) darkIcon.classList.toggle('hidden', !isDark);
            if (lightIcon) lightIcon.classList.toggle('hidden', isDark);
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
    @endguest
</x-app-layout>
