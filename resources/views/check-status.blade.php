<x-app-layout>
    <x-slot name="title">Check Ticket Status</x-slot>




    {{-- ── Main content ────────────────────────────────────────────────────── --}}
    <main class="relative z-10 flex-grow py-2 px-4 sm:px-6">
        <div class="max-w-3xl mx-auto">

            {{-- Page header --}}
            <div class="fauna-panel mb-6 sm:mb-10 p-4 sm:p-6 md:p-10 relative overflow-hidden text-left mt-6">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-rose-400 to-transparent opacity-40"></div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-950 flex items-center justify-center shadow-lg border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Search Tickets</h1>
                        <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Historical Inquiries Retrieval</span>
                    </div>
                </div>
            </div>

            <div class="text-center mb-10">
                <p class="mt-4 text-sm md:text-lg text-slate-600 dark:text-slate-400 px-4">
                    Enter the 8 character reference code to view the current status of your ticket.
                </p>
            </div>

            <x-flash-handler />

            {{-- Search form --}}
            <form
                method="POST"
                action="{{ route('search-tickets') }}"
                class="fauna-panel group relative block p-8 rounded-3xl mb-12"
                x-data="{ processing: false }" @submit="processing = true"
            >
                @csrf
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <label class="sr-only" for="reference">Ticket Reference</label>
                        <input
                            id="reference"
                            type="text"
                            name="reference"
                            value="{{ old('reference', $searchedReference ?? '') }}"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border border-rose-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-rose-400 transition-all outline-none font-mono"
                            placeholder="Enter your ticket reference..."
                            required
                            maxlength="8"
                        />
                        @error('reference')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button
                        type="submit"
                        x-bind:disabled="processing"
                        class="fauna-btn-primary disabled:opacity-50 whitespace-nowrap flex items-center justify-center gap-2"
                    >
                        <template x-if="processing">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Searching...' : 'Search Tickets'">Search Tickets</span>
                    </button>
                </div>
            </form>

            {{-- Search results --}}
            @isset($tickets)
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
                        Ticket status for:
                        <span class="font-mono text-rose-950 dark:text-rose-400">{{ $searchedReference }}</span>
                    </h2>

                    @if ($tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach ($tickets as $ticket)
                                @php
                                    $statusClass = match($ticket->status) {
                                        'open'        => 'bg-rose-100 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400 ring-4 ring-rose-500/10',
                                        'in-progress' => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 ring-4 ring-orange-500/10',
                                        default       => 'bg-slate-100 text-slate-600 dark:bg-[#1e293b] dark:text-slate-400',
                                    };
                                @endphp
                                <a
                                    href="{{ route('ticket.show', $ticket->hashid) }}"
                                    class="block p-8 bg-white dark:bg-[#0f172a] rounded-[2.5rem] border border-rose-950/10 dark:border-[#1e3a5f] shadow-sm hover:shadow-2xl hover:shadow-rose-400/10 hover:border-rose-950/50 transition-all group"
                                >
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-6">
                                        <div>
                                            <div class="text-[10px] font-black text-rose-950 dark:text-rose-400 tracking-[0.2em] mb-1 font-mono uppercase">
                                                #{{ $ticket->hashid }}
                                            </div>
                                            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white group-hover:text-rose-950 dark:group-hover:text-rose-400 transition-colors line-clamp-1">
                                                {{ $ticket->category?->name ?? str_replace('_', ' ', $ticket->subject) }}
                                            </h3>
                                        </div>
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest {{ $statusClass }}">
                                            {{ str_replace('-', ' ', $ticket->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-6 leading-relaxed">
                                        {{ $ticket->content }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[10px] md:text-xs text-slate-600">
                                        <div class="flex flex-wrap items-center gap-3 md:gap-4">
                                            <span class="font-black tracking-widest text-slate-400">
                                                Priority: <span class="text-slate-900 dark:text-white">{{ $ticket->priority }}</span>
                                            </span>
                                            <span class="font-black tracking-widest text-slate-400">
                                                Date: <span class="text-slate-900 dark:text-white">{{ $ticket->created_at->toFormattedDateString() }}</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center text-rose-950 dark:text-rose-400 font-black tracking-widest group-hover:translate-x-1 transition-transform self-end sm:self-auto">
                                            Open Ticket
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center bg-white dark:bg-[#0f172a] rounded-3xl border border-rose-950/10 dark:border-[#1e3a5f]">
                            <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No ticket found</h3>
                            <p class="text-slate-600">We couldn't find a ticket associated with that reference code.</p>
                        </div>
                    @endif
                </div>
            @endisset
        </div>
    </main>
</x-app-layout>
