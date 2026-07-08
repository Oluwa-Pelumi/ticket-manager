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
        <section class="relative overflow-hidden bg-sky-950 dark:bg-[#0f172a]">
            <div class="container mx-auto px-4">
                {{-- Nav: always visible — shows Login+Register for guests, Dashboard for auth users --}}
                <nav class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="inline-flex items-center gap-3 text-white">
                            <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }} logo" class="h-8 w-8">
                            <span class="text-xl font-semibold tracking-tight">{{ config('app.name') }}</span>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-3">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Register
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                    Dashboard
                                </a>
                            @endauth
                            <button onclick="toggleTheme()"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 text-white transition hover:border-[#3b82f6] hover:text-[#3b82f6] dark:border-[#3b82f6]/40"
                                aria-label="Toggle Theme">
                                <svg id="theme-icon-dark" class="w-5 h-5 text-[#3b82f6] hidden" fill="currentColor"
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
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Create Ticket
                        </a>

                        <a href="{{ auth()->user() ? route('dashboard') : route('check-status') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
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
        <section class="p-4 bg-white dark:bg-[#020617]">
            <div class="rounded-3xl bg-sky-900 px-6 py-16 dark:bg-[#0f172a] dark:border dark:border-[#1e3a5f]">
                <div class="container mx-auto px-4">
                    <h2 class="mb-4 text-4xl font-semibold text-white">How ticketing works</h2>
                    <p class="mb-12 max-w-2xl text-white/80">
                        From your first message to a closed ticket—here is what happens in {{ config('app.name') }}.
                    </p>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        @foreach ($ticketingSteps as $item)
                            <div class="rounded-2xl bg-white p-8 dark:bg-[#1e293b] dark:border dark:border-[#28524a]">
                                <span
                                    class="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-950 text-sm font-semibold text-white dark:bg-sky-400 dark:text-[#0f172a]">
                                    {{ $item['step'] }}
                                </span>
                                <h3 class="text-2xl font-medium text-sky-950 dark:text-white">{{ $item['title'] }}
                                </h3>
                                <p class="mt-3 text-slate-600 dark:text-slate-300">{{ $item['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div
                        class="mt-12 flex flex-wrap items-center justify-center gap-4 border-t border-white/20 pt-10 dark:border-[#28524a]">
                        <a href="{{ route('submit-ticket') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Create ticket
                        </a>

                        <a href="{{ route('check-status') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                            Check status
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
                                Dashboard
                            </a>
                        @endauth
                    </div>
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
                            <details class="fauna-panel p-6 dark:bg-[#0f172a] dark:border-[#1e3a5f]">
                                <summary class="cursor-pointer font-medium">{{ $item['question'] ?? $item->question }}
                                </summary>
                                <p class="mt-3 text-slate-600 dark:text-slate-400">
                                    {{ $item['answer'] ?? $item->answer }}</p>
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


        <section class="bg-sky-50/50 py-16 dark:bg-[#020617]">
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
                    <div class="rounded-2xl bg-sky-950 p-6 dark:bg-[#0f172a] dark:border dark:border-[#1e3a5f]">
                        <h4 class="mb-3 text-xl font-medium text-white">Open a new support request</h4>
                        <p class="mb-6 text-white/80">Use the ticket system to report issues, request updates, or ask
                            for help.</p>
                        <a href="{{ route('submit-ticket') }}"
                            class="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-sky-950 dark:!border-[#3b82f6]/50 dark:hover:!bg-[#3b82f6] dark:hover:!text-[#1e3a8a] !px-3 !py-2 sm:!px-6 sm:!py-3 !text-xs sm:!text-sm">
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
