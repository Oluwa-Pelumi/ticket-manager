<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4" x-data="{ selectedCount: 0 }"
            @selection-changed.window="selectedCount = $event.detail">
            <div
<<<<<<< HEAD
                class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-700 flex items-center justify-center shadow-lg border border-white/20 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
=======
                class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard</h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Overview & Management</span>
            </div>
            <div x-show="selectedCount > 0" x-cloak
<<<<<<< HEAD
                class="ml-auto flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-emerald-50 dark:bg-[#1e293b] rounded-xl border border-emerald-950/10 dark:border-[#1e3a5f] text-slate-700 dark:text-slate-200 text-[10px] font-bold tracking-widest shrink-0">
                <span class="mr-1" x-text="selectedCount"></span>selected tickets
                <template x-if="totalSelected > selectedCount">
                    <span class="ml-1 text-slate-400" x-text="'(' + totalSelected + ' total)'"></span>
                </template>
=======
                class="ml-auto flex items-center px-4 py-2 rounded-xl bg-teal-900 text-white text-[10px] font-black tracking-widest shadow-lg">
                Selected: <span x-text="selectedCount"></span> tickets
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Dashboard</x-slot>


    {{--
    =====================================================================
    Alpine component â€” all client-side state lives here.
    Data flows: PHP renders JSON into x-data, Alpine drives the UI.
    =====================================================================
    --}}
    <div class="max-w-[98%] xl:max-w-[1700px] mx-auto py-2 px-2 sm:px-4 lg:px-6" x-data="dashboard()"
        x-init="init()">

        {{-- Page header + bulk actions --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">Ticket Management</h1>
                <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 mt-1">
                    @if (auth()->user()->role === 'support')
                        Manage and resolve tickets.
                    @elseif (auth()->user()->role === 'user')
                        Track and manage your submitted tickets.
                    @else
                        Manage Tickets.
                    @endif
                </p>
            </div>
            <div class="flex w-full md:w-auto items-center justify-between md:justify-end gap-4">

                {{-- Bulk toolbar (admin/support only) --}}
                @if (in_array(auth()->user()->role, ['admin', 'support']))
<<<<<<< HEAD
                    <div x-show="effectiveSelectedIds.length > 0" x-cloak
                        class="flex items-center space-x-2 p-1.5 md:p-2 bg-slate-100 dark:bg-[#0f172a] rounded-2xl border border-emerald-950/10 dark:border-[#1e3a5f]">
=======
                    <div x-show="selectedIds.length > 0" x-cloak
                        class="flex items-center space-x-2 p-1.5 md:p-2 bg-slate-100 dark:bg-[#102824] rounded-2xl border border-emerald-900/10 dark:border-[#1d3a34]">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                        <span class="hidden sm:inline text-xs font-bold text-slate-600 dark:text-slate-400 px-2"
                            x-text="selectedIds.length + ' Selected'"></span>

                        <select
                            @change="
                                selectedIds.length
                                    ? (bulkStatusChange($event.target.value), $event.target.value = '')
                                    : ($event.target.value = '')
                            "
<<<<<<< HEAD
                            :class="{ 'opacity-40 cursor-not-allowed': !effectiveSelectedIds.length }"
                            class="text-[10px] md:text-xs font-black bg-white dark:bg-[#1e293b] text-slate-600 dark:text-slate-300 border-none rounded-xl focus:ring-2 focus:ring-emerald-400 py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10">
=======
                            :class="{ 'opacity-40 cursor-not-allowed': !selectedIds.length }"
                            class="text-[10px] md:text-xs font-black bg-white dark:bg-[#18342f] text-slate-600 dark:text-slate-300 border-none rounded-xl focus:ring-2 focus:ring-lime-500 py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            <option value="" disabled selected>Change Status of all selected</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>

                        <button @click="bulkDelete()"
                            class="p-1.5 md:p-2 text-emerald-500 hover:bg-emerald-500 hover:text-white rounded-xl transition-all"
                            title="Delete Selected">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (auth()->user()->role === 'user')
                    <a href="{{ route('submit-ticket') }}"
                        class="w-full md:w-auto text-center px-6 py-3 bg-teal-900 text-white rounded-2xl font-black text-xs tracking-widest shadow-xl hover:bg-[#10b981] hover:text-[#064e3b] hover:scale-105 active:scale-95 transition-all">
                        Submit a New Ticket
                    </a>
                @endif
            </div>
        </div>

        <x-flash-handler />

        {{-- Filter bar --}}
        <div
<<<<<<< HEAD
            class="flex flex-wrap items-center gap-3 md:gap-4 mb-6 p-4 rounded-2xl bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-emerald-950/10 dark:border-[#1e3a5f]">
=======
            class="flex flex-wrap items-center gap-3 md:gap-4 mb-6 p-4 rounded-2xl bg-white/50 dark:bg-[#102824]/70 backdrop-blur-md border border-emerald-900/10/50 dark:border-[#1d3a34]">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Filters:</span>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3 w-full sm:w-auto">
                <select x-model="filters.status" @change="currentPage = 1"
                    class="bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-lime-500 outline-none transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="in-progress">In-Progress</option>
                    <option value="closed">Closed</option>
                </select>

                <select x-model="filters.priority" @change="currentPage = 1"
                    class="bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-medium text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-lime-500 outline-none transition-all cursor-pointer">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            {{-- Search --}}
            <div class="relative group w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
<<<<<<< HEAD
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-950 dark:group-focus-within:text-emerald-400 transition-colors"
=======
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-teal-900 dark:group-focus-within:text-lime-400 transition-colors"
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model.debounce.300ms="filters.search" @input="currentPage = 1"
                    placeholder="Search by ID, subject, or user..."
                    class="block w-full pl-12 pr-4 py-3 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-lime-500 focus:border-transparent transition-all shadow-sm" />
            </div>

            <button x-show="filters.status || filters.priority || filters.search" @click="clearFilters()"
<<<<<<< HEAD
                    class="flex-shrink-0 text-[10px] md:text-xs font-bold text-emerald-500 hover:text-emerald-600 px-3 py-2 rounded-xl hover:bg-emerald-500/10 transition-all flex items-center justify-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear All
                </button>
=======
                class="col-span-2 sm:col-span-1 text-[10px] md:text-xs font-bold text-rose-500 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-rose-500/10 transition-all flex items-center justify-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear All
            </button>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)

            <div class="flex items-center gap-4 w-full sm:w-auto sm:ml-auto">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Rows:</span>
                    <select x-model.number="rowsPerPage" @change="currentPage = 1"
<<<<<<< HEAD
                        class="text-[10px] font-black bg-slate-100 dark:bg-[#1e293b] text-slate-600 dark:text-slate-300 border-none rounded-lg focus:ring-2 focus:ring-emerald-400 py-1 pl-2 pr-8 transition-all cursor-pointer">
=======
                        class="text-[10px] font-black bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-300 border-none rounded-lg focus:ring-2 focus:ring-lime-500 py-1 pl-2 pr-8 transition-all cursor-pointer">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="text-[10px] font-black tracking-[0.2em] text-slate-400"
                    x-text="'Showing ' + ((currentPage - 1) * rowsPerPage + 1) + ' â€“ ' + Math.min(currentPage * rowsPerPage, sortedTickets.length) + ' of ' + sortedTickets.length">
                </div>
            </div>
        </div>

        {{-- Mobile notice --}}
        <div x-show="showMobileNotice" x-cloak
            class="lg:hidden mb-6 p-4 rounded-2xl bg-amber-500/10 dark:bg-amber-500/5 border border-amber-500/20 dark:border-amber-500/10 text-amber-800 dark:text-amber-300">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs font-black tracking-wider uppercase">Notice</span>
                        <span class="text-[11px] font-medium leading-relaxed">Some columns are hidden on mobile. Switch
                            to desktop mode or a wider screen to see the full table.</span>
                    </div>
                </div>
                <button @click="showMobileNotice = false"
                    class="p-1 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- â”€â”€ Tickets table â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <div
<<<<<<< HEAD
            class="relative group rounded-2xl fauna-panel transition-all duration-500 hover:shadow-emerald-400/10 overflow-hidden max-w-full">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full table-auto text-left border-collapse">
=======
            class="relative group overflow-hidden rounded-2xl fauna-panel transition-all duration-500 hover:shadow-lime-500/10">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left min-w-[800px] lg:min-w-full border-collapse">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                    <thead>
                        <tr class="border-b border-emerald-900/10 dark:border-[#1d3a34]">
                            {{-- Select-all --}}
                            <th class="w-12 md:w-16 px-4 md:px-6 py-4">
                                @if (in_array(auth()->user()->role, ['admin', 'support']))
                                    <label class="flex items-center px-1 cursor-pointer select-none group">
                                        <input type="checkbox" class="hidden"
                                            :checked="allTickets.length > 0 && selectedIds.length === allTickets.length"
                                            @change="toggleSelectAll()" />
                                        <span
                                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                            :class="(allTickets.length > 0 && selectedIds.length === allTickets.length) ?
                                            'bg-[#064e3b] border-[#064e3b]' :
                                            'border-slate-300 dark:border-[#1d3a34] bg-white dark:bg-[#18342f]'">
                                            <svg x-show="allTickets.length > 0 && selectedIds.length === allTickets.length"
                                                class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    </label>
                                @endif
                            </th>
                            <th class="w-20 md:w-24 px-4 md:px-6 py-4">
                                <button @click="requestSort('id')"
                                    class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                    Reference <span x-html="getSortIcon('id')" class="ml-1"></span>
                                </button>
                            </th>
                            <th class="w-28 md:w-36 px-4 md:px-6 py-4">
                                <button @click="requestSort('subject')"
                                    class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                    Info <span x-html="getSortIcon('subject')" class="ml-1"></span>
                                </button>
                            </th>
                            @if (in_array(auth()->user()->role, ['admin', 'support']))
                                <th class="hidden lg:table-cell w-32 md:w-44 px-4 md:px-6 py-4">
                                    <button @click="requestSort('user')"
                                        class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                        User <span x-html="getSortIcon('user')" class="ml-1"></span>
                                    </button>
                                </th>
                            @endif
                            <th class="hidden lg:table-cell w-36 md:w-48 px-4 md:px-6 py-4">
                                <div class="text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">
                                    Order Details</div>
                            </th>
                            <th class="w-24 md:w-32 px-4 md:px-6 py-4">
                                <button @click="requestSort('priority')"
                                    class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                    Priority <span x-html="getSortIcon('priority')" class="ml-1"></span>
                                </button>
                            </th>
                            <th class="w-32 md:w-48 px-4 md:px-6 py-4">
                                <button @click="requestSort('status')"
                                    class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                    Status <span x-html="getSortIcon('status')" class="ml-1"></span>
                                </button>
                            </th>
                            <th class="hidden lg:table-cell w-48 px-6 py-4">
                                <button @click="requestSort('attendant')"
                                    class="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group">
                                    Attendant <span x-html="getSortIcon('attendant')" class="ml-1"></span>
                                </button>
                            </th>
                            <th
                                class="w-20 md:w-24 px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody x-show="paginatedTickets.length === 0" x-cloak
                        class="divide-y divide-slate-200 dark:divide-slate-800">
                        <tr>
                            <td colspan="{{ in_array(auth()->user()->role, ['admin', 'support']) ? 9 : 7 }}"
                                class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-slate-100 dark:bg-[#18342f] rounded-3xl mb-4">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">No tickets found
                                    </h3>
                                    <p class="text-slate-600 dark:text-slate-400">There are no tickets to display
                                        at this time.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    <template x-for="(ticket, idx) in paginatedTickets" :key="ticket.id">
                        <tbody
                            class="divide-y divide-slate-200 dark:divide-slate-800 border-b border-slate-200/50 dark:border-[#1d3a34]/30">
                            {{-- Main row --}}
<<<<<<< HEAD
                            <tr class="group hover:bg-emerald-50/50 dark:hover:bg-[#1e293b]/70 transition-all duration-300 cursor-pointer"
                                :class="expandedId === ticket.id ? 'bg-emerald-50/50/80 dark:bg-[#1e293b]/80' : ''"
                                @click="toggleExpand(ticket.id)">
                                    {{-- Checkbox --}}
                                    <td class="px-1.5 sm:px-3 md:px-6 py-4" @click.stop>
                                        @if (in_array(auth()->user()->role, ['admin', 'support']))
                                            <label class="flex items-center px-0.5 cursor-pointer select-none group">
                                                <input type="checkbox"
                                                    class="hidden"
                                                    :checked="selectedIds.includes(ticket.id)"
                                                    @change="toggleSelect(ticket.id)"
                                                />
                                                <span
                                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                    :class="selectedIds.includes(ticket.id) ? 'bg-[#1e3a8a] border-[#1e3a8a]' : 'border-slate-300 dark:border-[#1e3a5f] bg-white dark:bg-[#1e293b]'"
                                                >
                                                    <svg x-show="selectedIds.includes(ticket.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                            </label>
                                        @endif
                                    </td>

                                    {{-- Reference --}}
                                    <td class="px-1.5 sm:px-3 md:px-6 py-4" @click.stop>
                                        <span
                                            class="text-[10px] md:text-sm font-bold text-slate-600 group-hover:text-emerald-950 dark:group-hover:text-emerald-400 transition-colors tracking-tight"
                                            x-text="'#' + ticket.hashid"></span>
                                    </td>

                                    {{-- Subject + snippet --}}
                                    <td class="px-2 sm:px-4 md:px-6 py-4">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="min-w-0 flex-1">
                                        <div class="text-[11px] md:text-sm font-bold text-slate-900 dark:text-white group-hover:translate-x-1 transition-transform duration-300 line-clamp-1 overflow-hidden"
                                            x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase())">
                                        </div>
                                        <div class="text-[10px] text-slate-600 dark:text-slate-400 truncate max-w-[120px] sm:max-w-[250px] md:max-w-[350px] overflow-hidden"
                                            x-text="ticket.content"></div>
                                            </div>
                                            <svg class="lg:hidden w-4 h-4 shrink-0 text-slate-400 transition-transform"
                                                :class="expandedId === ticket.id ? 'rotate-180 text-emerald-950 dark:text-emerald-400' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </td>

                                    {{-- User (admin/support) --}}
=======
                            <tr class="group hover:bg-emerald-50/50 dark:hover:bg-[#18342f]/70 transition-all duration-300 cursor-pointer"
                                :class="expandedId === ticket.id ? 'bg-emerald-50/50/80 dark:bg-[#18342f]/80' : ''"
                                @click="toggleExpand(ticket.id)">
                                {{-- Checkbox --}}
                                <td class="px-4 md:px-6 py-4" @click.stop>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                    @if (in_array(auth()->user()->role, ['admin', 'support']))
                                        <label class="flex items-center px-1 cursor-pointer select-none group">
                                            <input type="checkbox" class="hidden"
                                                :checked="selectedIds.includes(ticket.id)"
                                                @change="toggleSelect(ticket.id)" />
                                            <span
                                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                :class="selectedIds.includes(ticket.id) ? 'bg-[#064e3b] border-[#064e3b]' :
                                                    'border-slate-300 dark:border-[#1d3a34] bg-white dark:bg-[#18342f]'">
                                                <svg x-show="selectedIds.includes(ticket.id)"
                                                    class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        </label>
                                    @endif
                                </td>

<<<<<<< HEAD
                                    {{-- Priority --}}
                                    <td class="px-1.5 sm:px-3 md:px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider"
                                            :class="{
                                                'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': ticket
                                                    .priority === 'high',
                                                'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400': ticket
                                                    .priority === 'medium',
                                                'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400': ticket
                                                    .priority === 'low'
                                            }">
                                            <svg x-show="ticket.priority === 'high'" class="w-3 h-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            <svg x-show="ticket.priority === 'medium'" class="w-3 h-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 12h14" />
                                            </svg>
                                            <svg x-show="ticket.priority === 'low'" class="w-3 h-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            <span class="hidden md:inline" x-text="ticket.priority"></span>
                                        </span>
=======
                                {{-- Reference --}}
                                <td class="px-4 md:px-6 py-4" @click.stop>
                                    <span
                                        class="text-[10px] md:text-sm font-bold text-slate-600 group-hover:text-teal-900 dark:group-hover:text-lime-400 transition-colors tracking-tight"
                                        x-text="'#' + ticket.hashid"></span>
                                </td>

                                {{-- Subject + snippet --}}
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-[11px] md:text-sm font-bold text-slate-900 dark:text-white group-hover:translate-x-1 transition-transform duration-300 line-clamp-1"
                                        x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/_/g, ' ')">
                                    </div>
                                    <div class="text-[10px] text-slate-600 dark:text-slate-400 truncate max-w-[60px] md:max-w-[120px]"
                                        x-text="ticket.content"></div>
                                </td>

                                {{-- User (admin/support) --}}
                                @if (in_array(auth()->user()->role, ['admin', 'support']))
                                    <td class="hidden lg:table-cell px-4 md:px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white truncate max-w-[120px]"
                                            x-text="ticket.name || ticket.user?.name"></div>
                                        <div class="text-[10px] text-slate-600 dark:text-slate-400 truncate max-w-[120px]"
                                            x-text="ticket.email || ticket.user?.email"></div>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                    </td>
                                @endif

<<<<<<< HEAD
                                    {{-- Status --}}
                                    <td class="hidden md:table-cell px-4 md:px-6 py-4" @click.stop>
                                        @if (in_array(auth()->user()->role, ['admin', 'support']))
                                            <select
                                                @change="statusUpdate(ticket.id, $event.target.value)"
                                                class="fauna-select-chevron text-[10px] md:text-xs font-black tracking-widest rounded-xl border-2 bg-transparent focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer py-1 md:py-2 pl-2 pr-8 md:pl-4 md:pr-10 transition-all"
                                                :class="{
                                                    'border-green-400 dark:border-green-500 text-green-600 dark:text-green-400': ticket.status === 'open',
                                                    'border-sky-500 dark:border-sky-400 text-sky-600 dark:text-sky-400': ticket.status === 'in-progress',
                                                    'border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400': ticket.status === 'closed'
                                                }">
                                                <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="open"      :selected="ticket.status === 'open'">Open</option>
                                                <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="in-progress" :selected="ticket.status === 'in-progress'">In Progress</option>
                                                <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="closed"    :selected="ticket.status === 'closed'">Resolved</option>
                                            </select>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold"
                                                :class="{
                                                    'bg-green-100 text-green-600 dark:bg-green-950/30 dark:text-green-400 ring-4 ring-green-500/10': ticket
                                                        .status === 'open',
                                                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 ring-4 ring-emerald-500/10': ticket
                                                        .status === 'in-progress',
                                                    'bg-slate-100 text-slate-500 dark:bg-slate-800/50 dark:text-slate-400': ticket
                                                        .status === 'closed'
                                                }"
                                                x-text="ticket.status.replace('-', ' ')"></span>
                                        @endif
                                    </td>

                                    {{-- Attendant --}}
                                    <td class="hidden lg:table-cell px-6 py-4">
                                        <template x-if="ticket.attendant">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300"
                                                    x-text="ticket.attendant.name.charAt(0)"></div>
                                                <span class="text-xs font-medium text-slate-900 dark:text-white"
                                                    x-text="ticket.attendant.name"></span>
=======
                                {{-- Order details --}}
                                <td class="hidden lg:table-cell px-4 md:px-6 py-4" @click.stop>
                                    <template x-if="ticket.order_type">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span
                                                    class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider"
                                                    :class="ticket.order_type === 'recurrent' ?
                                                        'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' :
                                                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'"
                                                    x-text="orderTypeLabel(ticket.order_type)"></span>
                                                <template x-if="ticket.order_type === 'recurrent'">
                                                    <span
                                                        class="text-[9px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-[#18342f] px-1.5 py-0.5 rounded border border-emerald-900/10 dark:border-[#28524a]"
                                                        x-text="ticket.recurrence_period === 'custom' ? ticket.custom_recurrence_date : recurrencePeriodLabel(ticket.recurrence_period)"></span>
                                                </template>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                            </div>
                                            <template
                                                x-if="ticket.order_activations && ticket.order_activations.length > 0">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Last
                                                        Order Processing:</span>
                                                    <span
                                                        class="text-[9px] font-bold text-slate-500 dark:text-slate-400"
                                                        x-text="formatDateTime(ticket.order_activations[ticket.order_activations.length - 1])"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!ticket.order_type">
                                        <span class="text-[10px] italic text-slate-400 tracking-widest">General
                                            Ticket</span>
                                    </template>
                                </td>

                                {{-- Priority --}}
                                <td class="px-4 md:px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider"
                                        :class="{
                                            'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': ticket
                                                .priority === 'high',
                                            'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400': ticket
                                                .priority === 'medium',
                                            'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400': ticket
                                                .priority === 'low'
                                        }">
                                        <svg x-show="ticket.priority === 'high'" class="w-3 h-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                        <svg x-show="ticket.priority === 'medium'" class="w-3 h-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 12h14" />
                                        </svg>
                                        <svg x-show="ticket.priority === 'low'" class="w-3 h-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                        <span class="hidden md:inline" x-text="ticket.priority"></span>
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 md:px-6 py-4" @click.stop>
                                    @if (in_array(auth()->user()->role, ['admin', 'support']))
                                        <select @change="statusUpdate(ticket.id, $event.target.value)"
                                            class="text-[10px] md:text-xs font-black tracking-widest rounded-xl border-2 bg-transparent focus:ring-2 focus:ring-lime-500 cursor-pointer py-1 md:py-2 pl-2 pr-8 md:pl-4 md:pr-10 transition-all"
                                            :class="{
                                                'border-blue-400 dark:border-blue-500 text-blue-600 dark:text-blue-400': ticket
                                                    .status === 'open',
                                                'border-emerald-500 dark:border-emerald-400 text-emerald-600 dark:text-emerald-400': ticket
                                                    .status === 'in-progress',
                                                'border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400': ticket
                                                    .status === 'closed'
                                            }">
                                            <option value="open" :selected="ticket.status === 'open'">Open
                                            </option>
                                            <option value="in-progress" :selected="ticket.status === 'in-progress'">
                                                Processing</option>
                                            <option value="closed" :selected="ticket.status === 'closed'">Closed
                                            </option>
                                        </select>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold"
                                            :class="{
                                                'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 ring-4 ring-blue-500/10': ticket
                                                    .status === 'open',
                                                'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 ring-4 ring-emerald-500/10': ticket
                                                    .status === 'in-progress',
                                                'bg-slate-100 text-slate-500 dark:bg-slate-800/50 dark:text-slate-400': ticket
                                                    .status === 'closed'
                                            }"
                                            x-text="ticket.status.replace('-', ' ')"></span>
                                    @endif
                                </td>

                                {{-- Attendant --}}
                                <td class="hidden lg:table-cell px-6 py-4">
                                    <template x-if="ticket.attendant">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600"
                                                x-text="ticket.attendant.name.charAt(0)"></div>
                                            <span class="text-xs font-medium text-slate-900 dark:text-white"
                                                x-text="ticket.attendant.name"></span>
                                        </div>
                                    </template>
                                    <template x-if="!ticket.attendant">
                                        <span
                                            class="text-[10px] italic text-slate-400 tracking-widest">Unassigned</span>
                                    </template>
                                </td>

                                {{-- Row actions --}}
                                <td class="px-4 md:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-1 md:space-x-2">

                                        {{-- Edit (ticket owner, not closed) --}}
                                        {{-- @if (auth()->user()->role === 'user')
                                                <template x-if="authId === ticket.user_id && ticket.status !== 'closed'">
                                                    <button @click.stop="openEditModal(ticket)"
                                                        class="p-1.5 md:p-2 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm"
                                                        title="Edit Ticket">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            @endif --}}

                                        {{-- Expand toggle --}}
                                        <button @click.stop="toggleExpand(ticket.id)"
                                            class="p-1.5 md:p-2 rounded-lg transition-all"
                                            :class="expandedId === ticket.id ? 'bg-teal-900 text-white rotate-180' :
                                                'bg-slate-100 dark:bg-[#18342f] text-slate-600 hover:text-teal-900 dark:hover:text-lime-400'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        {{-- Activate order (admin/support) --}}
                                        @if (in_array(auth()->user()->role, ['admin', 'support', 'user']))
                                            <template x-if="ticket.order_type">
                                                <button @click.stop="activateOrder(ticket.id)"
                                                    x-bind:disabled="activatingId === ticket.id"
                                                    class="p-1.5 md:p-2 rounded-lg bg-teal-900 dark:bg-[#10b981] text-white dark:text-[#064e3b] hover:scale-110 transition-all shadow-md disabled:opacity-60 disabled:hover:scale-100 disabled:cursor-not-allowed"
                                                    title="Process Order">
                                                    <svg x-show="activatingId !== ticket.id" class="w-4 h-4"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <svg x-show="activatingId === ticket.id"
                                                        class="w-4 h-4 animate-spin" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4" />
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                </button>
                                            </template>

<<<<<<< HEAD
                                            {{-- Expand toggle --}}
                                            <button @click.stop="toggleExpand(ticket.id)"
                                                class="p-1.5 md:p-2 rounded-lg transition-all"
                                                :class="expandedId === ticket.id ? 'bg-fauna-rose text-white rotate-180' :
                                                    'bg-slate-100 dark:bg-[#1e293b] text-slate-600 dark:text-slate-300 hover:text-emerald-950 dark:hover:text-emerald-400'">
=======
                                            {{-- Delete --}}
                                            <button @click.stop="deleteTicket(ticket.id)"
                                                class="p-1.5 md:p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

<<<<<<< HEAD
                                            @if (in_array(auth()->user()->role, ['admin', 'support', 'user']))
                                                {{-- Delete --}}
                                                <button @click.stop="deleteTicket(ticket.id)"
                                                    class="p-1.5 md:p-2 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
=======
                            {{-- ── Expanded row ──────────────────────────────────────── --}}
                            <tr class="bg-emerald-50/50 dark:bg-[#18342f]/30" x-show="expandedId === ticket.id"
                                x-cloak>
                                <td colspan="{{ in_array(auth()->user()->role, ['admin', 'support']) ? 9 : 7 }}"
                                    class="px-4 md:px-10 py-4 md:py-8 border-l-4 border-lime-500">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12">

                                        {{-- Left: specs + attachments --}}
                                        <div class="space-y-8">
                                            <div>
                                                <h4
                                                    class="text-xl font-black text-slate-900 dark:text-white mb-4 flex items-center tracking-tight">
                                                    <svg class="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Specifications
                                                </h4>
                                                <div
                                                    class="p-5 sm:p-8 rounded-2xl sm:rounded-[2.5rem] bg-white dark:bg-[#102824] border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm relative overflow-hidden">
                                                    <div
                                                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-30">
                                                    </div>

<<<<<<< HEAD
                                {{-- â”€â”€ Expanded row â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                                <tr class="bg-emerald-50/50 dark:bg-[#1e293b]/30" x-show="expandedId === ticket.id" x-cloak>
                                        <td colspan="{{ in_array(auth()->user()->role, ['admin', 'support']) ? 9 : 7 }}"
                                            class="border-l-4 border-emerald-400 p-0">
                                            <div class="px-2 sm:px-3 md:px-6 lg:px-8 py-4 md:py-8 overflow-x-hidden w-full">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 min-w-0 w-full items-start">

                                                {{-- Left: specs + attachments --}}
                                                <div class="space-y-8 min-w-0">
                                                    <div>
                                                        <h4
                                                            class="text-xl font-black text-slate-900 dark:text-white mb-4 flex items-center tracking-tight">
                                                            <svg class="w-5 h-5 mr-3 text-emerald-950 dark:text-emerald-400"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Specifications
                                                        </h4>
                                                        <div
                                                            class="p-4 sm:p-5 md:p-6 rounded-2xl sm:rounded-[2.5rem] bg-white dark:bg-[#0f172a] border border-emerald-950/10 dark:border-[#1e3a5f] shadow-sm relative overflow-hidden min-w-0">
                                                            <div
                                                                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-30">
                                                            </div>

                                                            {{-- Mobile-only controls when table columns are hidden --}}
                                                            <div class="md:hidden lg:hidden mb-6 pb-6 border-b border-slate-100 dark:border-[#1e3a5f]/50 space-y-4">
                                                                <div>
                                                                    <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">Status</div>
                                                                    @if (in_array(auth()->user()->role, ['admin', 'support']))
                                                                        <select
                                                                            @change="statusUpdate(ticket.id, $event.target.value)"
                                                                            @click.stop
                                                                            class="fauna-select-chevron w-full text-xs font-black tracking-widest rounded-xl border-2 bg-transparent focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer py-2 pl-3 pr-10 transition-all"
                                                                            :class="{
                                                                                'border-green-400 dark:border-green-500 text-green-600 dark:text-green-400': ticket.status === 'open',
                                                                                'border-sky-500 dark:border-sky-400 text-sky-600 dark:text-sky-400': ticket.status === 'in-progress',
                                                                                'border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400': ticket.status === 'closed'
                                                                            }">
                                                                            <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="open" :selected="ticket.status === 'open'">Open</option>
                                                                            <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="in-progress" :selected="ticket.status === 'in-progress'">In Progress</option>
                                                                            <option class="bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white" value="closed" :selected="ticket.status === 'closed'">Resolved</option>
                                                                        </select>
                                                                    @else
                                                                        <span
                                                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold"
                                                                            :class="{
                                                                                'bg-green-100 text-green-600 dark:bg-green-950/30 dark:text-green-400': ticket.status === 'open',
                                                                                'bg-sky-100 text-sky-600 dark:bg-sky-950/30 dark:text-sky-400': ticket.status === 'in-progress',
                                                                                'bg-slate-100 text-slate-500 dark:bg-slate-800/50 dark:text-slate-400': ticket.status === 'closed'
                                                                            }"
                                                                            x-text="ticket.status.replace('-', ' ')"></span>
                                                                    @endif
=======
                                                    <div
                                                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.3em] uppercase">
                                                        Creator Information</div>

                                                    <div class="mt-2 mb-6">
                                                        <div class="space-y-1.5">
                                                            <template x-if="ticket.name || ticket.user?.name">
                                                                <div
                                                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                    <svg class="w-3 h-3 text-teal-600 dark:text-lime-500 shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>

                                                                    <span
                                                                        x-text="ticket.name || ticket.user?.name"></span>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                                </div>
                                                            </template>
                                                        </div>

<<<<<<< HEAD
                                                                <div>
                                                                    <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">Attendant</div>
                                                                    <template x-if="ticket.attendant">
                                                                        <div class="flex items-center space-x-2">
                                                                            <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300"
                                                                                x-text="ticket.attendant.name.charAt(0)"></div>
                                                                            <span class="text-sm font-medium text-slate-900 dark:text-white" x-text="ticket.attendant.name"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="!ticket.attendant">
                                                                        <span class="text-xs italic text-slate-400 tracking-widest">Unassigned</span>
                                                                    </template>
=======
                                                        <div class="space-y-1.5">
                                                            <template x-if="ticket.email || ticket.user?.email">
                                                                <div
                                                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                    <svg class="w-3 h-3 text-teal-600 dark:text-lime-500 shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                    </svg>

                                                                    <span
                                                                        x-text="ticket.email || ticket.user?.email"></span>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                                </div>
                                                            </template>
                                                        </div>

<<<<<<< HEAD
                                                                @if (in_array(auth()->user()->role, ['admin', 'support', 'user']))
                                                                    <div>
                                                                        <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">Actions</div>
                                                                        <div class="flex flex-wrap items-center gap-2">
                                                                            <template x-if="authId === ticket.user_id && !ticket.has_support_replied && ticket.status !== 'closed'">
                                                                                <button @click.stop="openEditModal(ticket)"
                                                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-[#1e293b] text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all border border-transparent hover:border-blue-600/20 shadow-sm text-xs font-black tracking-widest">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                    </svg>
                                                                                    Edit Ticket
                                                                                </button>
                                                                            </template>
                                                                            <button @click.stop="deleteTicket(ticket.id)"
                                                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm text-xs font-black tracking-widest">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                                Delete Ticket
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- Tablet: actions/attendant when only actions column is hidden --}}
                                                            <div class="hidden md:block lg:hidden mb-6 pb-6 border-b border-slate-100 dark:border-[#1e3a5f]/50 space-y-4">
                                                                <div>
                                                                    <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">Attendant</div>
                                                                    <template x-if="ticket.attendant">
                                                                        <div class="flex items-center space-x-2">
                                                                            <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300"
                                                                                x-text="ticket.attendant.name.charAt(0)"></div>
                                                                            <span class="text-sm font-medium text-slate-900 dark:text-white" x-text="ticket.attendant.name"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="!ticket.attendant">
                                                                        <span class="text-xs italic text-slate-400 tracking-widest">Unassigned</span>
                                                                    </template>
=======
                                                        <div class="space-y-1.5">
                                                            <template
                                                                x-if="ticket.whatsapp_number || ticket.user?.whatsapp_number">
                                                                <div
                                                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                    <svg class="w-3 h-3 text-teal-600 dark:text-lime-500 shrink-0"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-1.687.845a11.042 11.042 0 005.516 5.516l.845-1.687a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                    </svg>

                                                                    <span
                                                                        x-text="ticket.whatsapp_number || ticket.user?.whatsapp_number"></span>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

<<<<<<< HEAD
                                                                @if (in_array(auth()->user()->role, ['admin', 'support', 'user']))
                                                                    <div>
                                                                        <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">Actions</div>
                                                                        <button @click.stop="deleteTicket(ticket.id)"
                                                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm text-xs font-black tracking-widest">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                            Delete Ticket
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div
                                                                class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">
                                                                Creator Information</div>

                                                                <div class="mt-2 mb-6">
                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.user?.name">
                                                                            <div
                                                                                class="text-[14px] font-medium text-slate-600 dark:text-slate-400 flex items-start gap-2 min-w-0">
                                                                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>

                                                                                <span class="min-w-0 break-words" x-text="ticket.user?.name"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.user?.email">
                                                                            <div
                                                                                class="text-[14px] font-medium text-slate-600 dark:text-slate-400 flex items-start gap-2 min-w-0">
                                                                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                                </svg>

                                                                                <span class="min-w-0 break-all" x-text="ticket.user?.email"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.user?.phone_number">
                                                                            <div
                                                                                class="text-[14px] font-medium text-slate-600 dark:text-slate-400 flex items-start gap-2 min-w-0">
                                                                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-1.687.845a11.042 11.042 0 005.516 5.516l.845-1.687a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                                </svg>

                                                                                <span class="min-w-0 break-all" x-text="ticket.user?.phone_number"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                            <div
                                                                class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">
                                                                Reference</div>
                                                            <div class="flex flex-wrap items-center gap-3 mb-8 min-w-0">
                                                                <div class="text-xl sm:text-2xl text-slate-900 dark:text-white font-black tracking-tight break-all min-w-0"
                                                                    x-text="ticket.hashid"></div>
                                                                <button @click.stop="copyHashid(ticket.hashid)"
                                                                    class="flex items-center gap-2 px-2 py-1 rounded-lg bg-slate-100 dark:bg-[#1e293b] text-slate-600 hover:text-emerald-950 dark:hover:text-emerald-400 transition-all border border-transparent hover:border-emerald-950/20">
                                                                    <template x-if="copiedId === ticket.hashid">
                                                                        <span
                                                                            class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 tracking-wider">
                                                                            <svg class="w-4 h-4" fill="none"
                                                                                stroke="currentColor"
=======
                                                    <div
                                                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.3em] uppercase">
                                                        Reference</div>
                                                    <div class="flex items-center gap-3 mb-8">
                                                        <div class="text-2xl text-slate-900 dark:text-white font-black tracking-tight"
                                                            x-text="ticket.hashid"></div>
                                                        <button @click.stop="copyHashid(ticket.hashid)"
                                                            class="flex items-center gap-2 px-2 py-1 rounded-lg bg-slate-100 dark:bg-[#18342f] text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20">
                                                            <template x-if="copiedId === ticket.hashid">
                                                                <span
                                                                    class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 tracking-wider">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Copied!
                                                                </span>
                                                            </template>
                                                            <template x-if="copiedId !== ticket.hashid">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2.5"
                                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                                </svg>
                                                            </template>
                                                        </button>
                                                    </div>

                                                    <div
                                                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">
                                                        Subject</div>
                                                    <div class="text-xl text-slate-900 dark:text-white font-bold mb-6"
                                                        x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/_/g, ' ')">
                                                    </div>

                                                    <div
                                                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">
                                                        Description</div>
                                                    <div class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-sm mb-6"
                                                        x-text="ticket.content"></div>

                                                    {{-- Attachments --}}
                                                    <template
                                                        x-if="(ticket.images && ticket.images.length > 0) || ticket.filename">
                                                        <div>
                                                            <h4
                                                                class="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center uppercase">
                                                                <svg class="w-4 h-4 mr-2 text-emerald-500"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                Attachments
                                                            </h4>
                                                            <div class="flex flex-wrap gap-4">
                                                                <template x-if="ticket.filename">
                                                                    <a :href="'/storage/' + ticket.filename"
                                                                        :alt="ticket.filename" target="_blank"
                                                                        class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
                                                                        <img :src="'/storage/' + ticket.filename"
                                                                            class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                        <div
                                                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                            <svg class="w-6 h-6 text-white"
                                                                                fill="none" stroke="currentColor"
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                            </svg>
                                                                        </div>
                                                                    </a>
                                                                </template>
                                                                <template x-for="(img, i) in (ticket.images || [])"
                                                                    :key="i">
                                                                    <a :href="'/storage/' + img" target="_blank"
                                                                        class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
                                                                        <img :src="'/storage/' + img"
                                                                            class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                        <div
                                                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                            <svg class="w-6 h-6 text-white"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                            </svg>
                                                                        </div>
                                                                    </a>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <template x-if="ticket.order_type">
                                                        <div
                                                            class="mt-8 pt-8 border-t border-slate-100 dark:border-[#1d3a34]/50">
                                                            <div
<<<<<<< HEAD
                                                                class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.2em] uppercase">
                                                                Subject</div>
                                                            <div class="text-lg sm:text-xl text-slate-900 dark:text-white font-bold mb-6 break-words"
                                                                x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/_/g, ' ')">
                                                            </div>

                                                            <div
                                                                class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.2em] uppercase">
                                                                Description</div>
                                                            <div class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap break-words leading-relaxed text-base sm:text-lg mb-6"
                                                                x-text="ticket.content"></div>

                                                            {{-- Attachments --}}
                                                            <template
                                                                x-if="(ticket.attachments && ticket.attachments.length > 0) || ticket.filename">
                                                                <div>
                                                                    <h4
                                                                        class="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center uppercase">
                                                                        <svg class="w-4 h-4 mr-2 text-emerald-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        Attachments
                                                                    </h4>
                                                                    <div class="flex flex-wrap gap-4">
                                                                        <template x-if="ticket.filename">
                                                                            <div class="contents">
                                                                            {{-- Image: open lightbox --}}
                                                                            <template x-if="isImage(ticket.filename)">
                                                                                <button type="button"
                                                                                    @click.stop="openLightbox('/storage/' + ticket.filename)"
                                                                                    class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md cursor-zoom-in bg-slate-100 dark:bg-[#1e293b]">
                                                                                    <img :src="'/storage/' + ticket.filename" :alt="ticket.filename"
                                                                                        class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                    <span class="absolute bottom-0 inset-x-0 text-center text-[7px] font-black tracking-wider text-white bg-black/40 py-0.5 opacity-0 group-hover/img:opacity-100 transition-opacity">PHOTO</span>
                                                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0zm0 0v.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 8v6m-3-3h6"/></svg>
                                                                                    </div>
                                                                                </button>
                                                                            </template>
                                                                            {{-- Document: open in new tab --}}
                                                                            <template x-if="!isImage(ticket.filename)">
                                                                                <a :href="'/storage/' + ticket.filename" target="_blank"
                                                                                    class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
                                                                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                        </svg>
                                                                                        <span class="text-[9px] font-black text-slate-500" x-text="fileExt(ticket.filename)"></span>
                                                                                    </div>
                                                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                                                    </div>
                                                                                </a>
                                                                            </template>
                                                                            </div>
                                                                        </template>
                                                                        <template x-for="(img, i) in (ticket.attachments || [])" :key="i">
                                                                            <div class="contents">
                                                                            {{-- Image: open lightbox --}}
                                                                            <template x-if="isImage(img)">
                                                                                <button type="button"
                                                                                    @click.stop="openLightbox('/storage/' + img)"
                                                                                    class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md cursor-zoom-in bg-slate-100 dark:bg-[#1e293b]">
                                                                                    <img :src="'/storage/' + img"
                                                                                        class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                    <span class="absolute bottom-0 inset-x-0 text-center text-[7px] font-black tracking-wider text-white bg-black/40 py-0.5 opacity-0 group-hover/img:opacity-100 transition-opacity">PHOTO</span>
                                                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0zm0 0v.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 8v6m-3-3h6"/></svg>
                                                                                    </div>
                                                                                </button>
                                                                            </template>
                                                                            {{-- Document: open in new tab --}}
                                                                            <template x-if="!isImage(img)">
                                                                                <a :href="'/storage/' + img" target="_blank"
                                                                                    class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
                                                                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                        </svg>
                                                                                        <span class="text-[9px] font-black text-slate-500" x-text="fileExt(img)"></span>
                                                                                    </div>
                                                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                                                    </div>
                                                                                </a>
                                                                            </template>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-[#1e3a5f]/50">
                                                                <div class="text-[10px] font-black text-emerald-950 dark:text-emerald-400 mb-2 tracking-[0.3em] uppercase">
                                                                    Attending Support Staff
                                                                </div>

                                                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                                    Past
                                                                </div>
                                                                <div class="flex flex-wrap gap-3 mb-4">
                                                                    <template x-if="ticket.attendants && ticket.attendants.filter(a => a.id !== ticket.attendant?.id).length > 0">
                                                                        <template x-for="att in ticket.attendants.filter(a => a.id !== ticket.attendant?.id)" :key="att.id">
                                                                            <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-emerald-950/10 dark:border-[#1e3a5f]">
                                                                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300"
                                                                                    x-text="att.name ? att.name.charAt(0).toUpperCase() : '?'"></div>
                                                                                <span class="text-xs font-bold text-slate-900 dark:text-white" x-text="att.name"></span>
                                                                            </div>
                                                                        </template>
                                                                    </template>
                                                                    <template x-if="!ticket.attendants || ticket.attendants.filter(a => a.id !== ticket.attendant?.id).length === 0">
                                                                        <span class="text-xs italic text-slate-400">No past support staff.</span>
                                                                    </template>
                                                                </div>

                                                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                                    Current
                                                                </div>
                                                                <div class="flex flex-wrap gap-3">
                                                                    <template x-if="ticket.attendant">
                                                                        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-emerald-950/10 dark:border-[#1e3a5f]">
                                                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300"
                                                                                x-text="ticket.attendant.name ? ticket.attendant.name.charAt(0).toUpperCase() : '?'"></div>
                                                                            <span class="text-xs font-bold text-slate-900 dark:text-white" x-text="ticket.attendant.name"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="!ticket.attendant">
                                                                        <span class="text-xs italic text-slate-400">No current support staff assigned yet.</span>
                                                                    </template>
                                                                </div>
=======
                                                                class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.3em] uppercase">
                                                                Order Information</div>
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                                <div>
                                                                    <div
                                                                        class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                                        Frequency</div>
                                                                    <div class="text-sm text-slate-900 dark:text-white capitalize"
                                                                        x-text="['recurrent', 'recurring'].includes(ticket.order_type) ? orderTypeLabel(ticket.order_type) + ' - ' + (ticket.recurrence_period === 'custom' ? 'Custom: ' + ticket.custom_recurrence_date : recurrencePeriodLabel(ticket.recurrence_period)) : orderTypeLabel(ticket.order_type)">
                                                                    </div>
                                                                </div>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                            </div>

<<<<<<< HEAD
                                                {{-- Right: comments + comment form --}}
                                                <div class="space-y-8">
                                                    <div>
                                                        <h4 class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                                                            <svg class="w-5 h-5 mr-3 text-emerald-950 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                                            Conversation
                                                        </h4>

                                                        <div class="fauna-panel mb-6 p-4 md:p-6 max-h-[400px] md:max-h-[500px] overflow-y-auto overflow-x-hidden pr-1 md:pr-2 custom-scrollbar relative space-y-4 min-w-0">
                                                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-40"></div>
                                                            <template
                                                                x-if="!ticket.comments || ticket.comments.length === 0">
                                                                <div class="text-center py-2 opacity-40">
                                                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                                    <div class="italic text-sm">No comments yet. Start the conversation.</div>
                                                                </div>
                                                            </template>

                                                            <template x-for="(comment, ci) in [...(ticket.comments || [])].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))" :key="ci">
                                                                <div class="flex flex-col min-w-0 max-w-full w-full"
                                                                    :class="(comment.user_id === authId) ? 'items-end' : 'items-start'">
                                                                    <div class="max-w-full w-full sm:max-w-[85%] p-4 sm:p-5 min-w-0"
                                                                        :class="(comment.user_id === authId)
                                                                            ? 'bg-emerald-950 text-white shadow-xl rounded-2xl sm:rounded-[2rem] !rounded-br-none'
                                                                            : 'fauna-panel text-slate-900 dark:text-white rounded-2xl sm:rounded-[2rem] !rounded-bl-none'">
                                                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2 min-w-0">
                                                                            <span class="text-[9px] font-black tracking-widest opacity-80 break-words" x-text="comment.user?.name"></span>
                                                                            <template x-if="comment.user && (comment.user.role === 'support' || comment.user.role === 'admin')">
                                                                                <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider rounded bg-white/20 text-white">Support</span>
                                                                            </template>

                                                                            <span class="text-[9px] opacity-40" x-text="timeAgo(comment.created_at) + ' Â· ' + new Date(comment.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                                                        </div>
                                                                        <div class="text-sm font-medium leading-relaxed break-words" x-text="comment.content"></div>
                                                                        <template x-if="comment.attachments && comment.attachments.length > 0">
                                                                            <div class="flex flex-wrap gap-2 mt-3">
                                                                                <template x-for="(cimg, cii) in (comment.attachments || [])" :key="cii">
                                                                                    <div class="contents">
                                                                                    {{-- Image: open lightbox --}}
                                                                                    <template x-if="isImage(cimg)">
                                                                                        <button type="button"
                                                                                            @click.stop="openLightbox('/storage/' + cimg)"
                                                                                            class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md cursor-zoom-in bg-slate-100 dark:bg-[#1e293b]">
                                                                                            <img :src="'/storage/' + cimg"
                                                                                                class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                            <span class="absolute bottom-0 inset-x-0 text-center text-[7px] font-black tracking-wider text-white bg-black/40 py-0.5 opacity-0 group-hover/img:opacity-100 transition-opacity">PHOTO</span>
                                                                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0zm0 0v.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 8v6m-3-3h6"/></svg>
                                                                                            </div>
                                                                                        </button>
                                                                                    </template>
                                                                                    {{-- Document: open in new tab --}}
                                                                                    <template x-if="!isImage(cimg)">
                                                                                        <a :href="'/storage/' + cimg" target="_blank"
                                                                                            class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
                                                                                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                                </svg>
                                                                                                <span class="text-[9px] font-black text-slate-500" x-text="fileExt(cimg)"></span>
                                                                                            </div>
                                                                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                                                            </div>
                                                                                        </a>
                                                                                    </template>
                                                                                    </div>
                                                                                </template>
=======
                                                            <template
                                                                x-if="ticket.order_activations && ticket.order_activations.length > 0">
                                                                <div class="mt-6">
                                                                    <div
                                                                        class="text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest">
                                                                        Order Process History</div>
                                                                    <div class="space-y-1.5">
                                                                        <template
                                                                            x-for="(date, i) in ticket.order_activations"
                                                                            :key="i">
                                                                            <div
                                                                                class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                                <div
                                                                                    class="w-1 h-1 rounded-full bg-lime-500 shrink-0">
                                                                                </div>
                                                                                <svg class="w-3 h-3 text-teal-600 dark:text-lime-500 shrink-0"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                </svg>
                                                                                <span
                                                                                    x-text="new Date(date).toLocaleDateString('en-GB')"></span>
                                                                                <svg class="w-3 h-3 text-teal-600 dark:text-lime-500 shrink-0"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                                <span
                                                                                    x-text="new Date(date).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' })"></span>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>

                                                    <div
                                                        class="mt-8 pt-8 border-t border-slate-100 dark:border-[#1d3a34]/50">
                                                        <div
                                                            class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.3em] uppercase">
                                                            Attending Support Staff
                                                        </div>

                                                        <div
                                                            class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                            Past
                                                        </div>
                                                        <div class="flex flex-wrap gap-3 mb-4">
                                                            <template
                                                                x-if="ticket.attendants && ticket.attendants.filter(a => a.id !== ticket.attendant?.id).length > 0">
                                                                <template
                                                                    x-for="att in ticket.attendants.filter(a => a.id !== ticket.attendant?.id)"
                                                                    :key="att.id">
                                                                    <div
                                                                        class="flex items-center space-x-2 bg-slate-100 dark:bg-[#18342f] px-3 py-1.5 rounded-xl border border-emerald-900/10 dark:border-[#28524a]">
                                                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600"
                                                                            x-text="att.name ? att.name.charAt(0).toUpperCase() : '?'">
                                                                        </div>
                                                                        <span
                                                                            class="text-xs font-bold text-slate-900 dark:text-white"
                                                                            x-text="att.name"></span>
                                                                    </div>
                                                                </template>
                                                            </template>
                                                            <template
                                                                x-if="!ticket.attendants || ticket.attendants.filter(a => a.id !== ticket.attendant?.id).length === 0">
                                                                <span class="text-xs italic text-slate-400">No past
                                                                    support staff.</span>
                                                            </template>
                                                        </div>

                                                        <div
                                                            class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                            Current
                                                        </div>
                                                        <div class="flex flex-wrap gap-3">
                                                            <template x-if="ticket.attendant">
                                                                <div
                                                                    class="flex items-center space-x-2 bg-slate-100 dark:bg-[#18342f] px-3 py-1.5 rounded-xl border border-emerald-900/10 dark:border-[#28524a]">
                                                                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600"
                                                                        x-text="ticket.attendant.name ? ticket.attendant.name.charAt(0).toUpperCase() : '?'">
                                                                    </div>
                                                                    <span
                                                                        class="text-xs font-bold text-slate-900 dark:text-white"
                                                                        x-text="ticket.attendant.name"></span>
                                                                </div>
                                                            </template>
                                                            <template x-if="!ticket.attendant">
                                                                <span class="text-xs italic text-slate-400">No current
                                                                    support staff assigned yet.</span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right: comments + comment form --}}
                                        <div class="space-y-8">
                                            <div>
                                                <h4
                                                    class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                                                    <svg class="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                    Conversation
                                                </h4>

                                                <div
                                                    class="fauna-panel mb-6 p-4 md:p-6 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar relative overflow-hidden space-y-4">
                                                    <div
                                                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40">
                                                    </div>
                                                    <template x-if="!ticket.comments || ticket.comments.length === 0">
                                                        <div class="text-center py-2 opacity-40">
                                                            <svg class="w-12 h-12 mx-auto mb-3" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                            </svg>
                                                            <div class="italic text-sm">No comments yet. Start the
                                                                conversation.</div>
                                                        </div>
                                                    </template>

                                                    <template
                                                        x-for="(comment, ci) in [...(ticket.comments || [])].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))"
                                                        :key="ci">
                                                        <div class="flex flex-col"
                                                            :class="(comment.user && (comment.user.role === 'support' ||
                                                                comment.user.role === 'admin')) ? 'items-end' :
                                                            'items-start'">
                                                            <div class="max-w-[95%] sm:max-w-[85%] p-4 sm:p-5"
                                                                :class="(comment.user && (comment.user.role === 'support' ||
                                                                    comment.user.role === 'admin')) ?
                                                                'bg-teal-900 text-white shadow-xl rounded-2xl sm:rounded-[2rem] rounded-br-sm sm:rounded-br-md' :
                                                                'fauna-panel text-slate-900 dark:text-white rounded-2xl sm:rounded-[2rem] rounded-bl-sm sm:rounded-bl-md'">
                                                                <div class="flex items-center space-x-3 mb-2">
                                                                    <span
                                                                        class="text-[9px] font-black tracking-widest opacity-80"
                                                                        x-text="comment.user?.name"></span>
                                                                    <template
                                                                        x-if="comment.user && (comment.user.role === 'support' || comment.user.role === 'admin')">
                                                                        <span
                                                                            class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider rounded bg-white/20 text-white">Support</span>
                                                                    </template>
                                                                    <span class="text-[9px] opacity-40"
                                                                        x-text="timeAgo(comment.created_at) + ' · ' + new Date(comment.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                                                </div>
                                                                <div class="text-sm font-medium leading-relaxed"
                                                                    x-text="comment.content"></div>
                                                                <template
                                                                    x-if="comment.images && comment.images.length > 0">
                                                                    <div class="flex flex-wrap gap-2 mt-3">
                                                                        <template
                                                                            x-for="(cimg, cii) in (comment.images || [])"
                                                                            :key="cii">
                                                                            <a :href="'/storage/' + cimg"
                                                                                target="_blank"
                                                                                class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
                                                                                <img :src="'/storage/' + cimg"
                                                                                    class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                <div
                                                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                    <svg class="w-6 h-6 text-white"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                                    </svg>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                {{-- Comment form --}}
                                                @if (in_array(auth()->user()->role, ['admin', 'support']))
                                                    <div x-data="commentForm(ticket.id)" @click.stop>
                                                        @include('dashboard._comment-form')
                                                    </div>
                                                @else
                                                    <template x-if="authId === ticket.user_id">
                                                        <div x-data="commentForm(ticket.id)" @click.stop>
                                                            @include('dashboard._comment-form')
                                                        </div>
                                                    </template>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>

            {{-- Pagination --}}
            <template x-if="totalPages > 1">
                <div
<<<<<<< HEAD
                    class="px-6 py-4 border-t border-emerald-950/10 dark:border-[#1e3a5f] flex items-center justify-between bg-slate-50/50 dark:bg-[#0f172a]/50">
                    <div class="text-[10px] font-black text-slate-400 tracking-widest uppercase"
                        x-text="'Page ' + currentPage + ' of ' + totalPages"></div>
                    <div class="flex items-center gap-2">
                        <button @click="currentPage = Math.max(currentPage - 1, 1)" x-bind:disabled="currentPage === 1"
                            class="p-2 rounded-xl bg-white dark:bg-[#1e293b] border border-emerald-950/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400 hover:text-emerald-950 dark:hover:text-emerald-400 disabled:opacity-50 transition-all">
=======
                    class="px-6 py-4 border-t border-emerald-900/10 dark:border-[#1d3a34] flex items-center justify-between bg-slate-50/50 dark:bg-[#102824]/50">
                    <div class="text-[10px] font-black text-slate-400 tracking-widest uppercase"
                        x-text="'Page ' + currentPage + ' of ' + totalPages"></div>
                    <div class="flex items-center gap-2">
                        <button @click="currentPage = Math.max(currentPage - 1, 1)"
                            x-bind:disabled="currentPage === 1"
                            class="p-2 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 disabled:opacity-50 transition-all">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-1">
                            <template x-for="page in visiblePages" :key="page">
<<<<<<< HEAD
                                <span>
                                    <template x-if="page === '...'">
                                        <span class="text-slate-300 dark:text-slate-700 text-[10px]">...</span>
                                    </template>
                                    <template x-if="page !== '...'">
                                        <button @click="currentPage = page"
                                            class="w-8 h-8 rounded-xl text-[10px] font-black transition-all"
                                            :class="currentPage === page ? 'bg-fauna-rose text-white shadow-lg shadow-fauna-rose/20' :
                                                'text-slate-400 hover:text-emerald-950 dark:hover:text-emerald-400'"
                                            x-text="page"></button>
                                    </template>
                                </span>
=======
                                <template x-if="page === '...'">
                                    <span class="text-slate-300 dark:text-slate-700 text-[10px]">...</span>
                                </template>
                                <template x-if="page !== '...'">
                                    <button @click="currentPage = page"
                                        class="w-8 h-8 rounded-xl text-[10px] font-black transition-all"
                                        :class="currentPage === page ? 'bg-teal-900 text-white shadow-lg shadow-teal-900/20' :
                                            'text-slate-400 hover:text-teal-900 dark:hover:text-lime-400'"
                                        x-text="page"></button>
                                </template>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            </template>
                        </div>
                        <button @click="currentPage = Math.min(currentPage + 1, totalPages)"
                            x-bind:disabled="currentPage === totalPages"
<<<<<<< HEAD
                            class="p-2 rounded-xl bg-white dark:bg-[#1e293b] border border-emerald-950/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400 hover:text-emerald-950 dark:hover:text-emerald-400 disabled:opacity-50 transition-all">
=======
                            class="p-2 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 disabled:opacity-50 transition-all">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

<<<<<<< HEAD
        {{-- â”€â”€ Lightbox modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <div
            x-show="lightboxOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeLightbox()"
            @click.self="closeLightbox()"
            class="fixed inset-0 z-[150] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
        >
            <div class="relative max-w-5xl w-full max-h-[90vh] flex items-center justify-center">
                {{-- Close --}}
                <button
                    @click="closeLightbox()"
                    class="absolute -top-4 -right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-all backdrop-blur-sm border border-white/20"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Image --}}
                <img :src="lightboxSrc" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl" />

                {{-- Open original --}}
                <a
                    :href="lightboxSrc"
                    target="_blank"
                    class="absolute bottom-4 right-4 flex items-center gap-2 px-3 py-1.5 rounded-xl bg-black/40 hover:bg-black/60 text-white text-xs font-bold backdrop-blur-sm border border-white/10 transition-all"
                    title="Open original"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Open original
                </a>
            </div>
        </div>

        {{-- â”€â”€ Edit modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <div x-show="editingTicket !== null" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            @keydown.escape.window="closeEditModal()">
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f172a] rounded-3xl shadow-2xl border border-emerald-950/10 dark:border-[#1e3a5f] p-8"
=======
        {{-- ── Edit modal ─────────────────────────────────────────────────── --}}
        {{-- <div x-show="editingTicket !== null" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            @keydown.escape.window="closeEditModal()">
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#102824] rounded-3xl shadow-2xl border border-emerald-900/10 dark:border-[#1d3a34] p-8"
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                @click.stop>
                <button @click="closeEditModal()"
                    class="absolute top-6 right-6 p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-500/10 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6"
                    x-text="'Edit Ticket #' + (editingTicket ? editingTicket.hashid.substring(0, 8) : '')"></h2>

                <form @submit.prevent="submitEdit()" class="space-y-6" x-ref="editForm"
                    enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Subject /
                                Category</label>
                            <select x-model="editData.category_id"
<<<<<<< HEAD
                                class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 outline-none transition-all">
=======
                                class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                <option value="" disabled>Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Priority</label>
                            <select x-model="editData.priority"
<<<<<<< HEAD
                                class="w-full pl-4 pr-10 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 outline-none transition-all">
                                <option value="low">â¬‡ï¸ Low</option>
                                <option value="medium">âš¡ Medium</option>
                                <option value="high">ðŸš© High</option>
=======
                                class="w-full pl-4 pr-10 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all">
                                <option value="low">⬇️ Low</option>
                                <option value="medium">⚡ Medium</option>
                                <option value="high">🚩 High</option>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Content</label>
                        <textarea x-model="editData.content" rows="4"
<<<<<<< HEAD
                            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 outline-none transition-all resize-none"></textarea>
=======
                            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all resize-none"></textarea>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Attachments</label>
<<<<<<< HEAD
                            <label for="edit-modal-file-input"
                                class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-[#1e293b] text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-emerald-950 dark:hover:text-emerald-400 cursor-pointer transition-all border border-emerald-950/10 dark:border-[#1e3a5f]">
                                Add attachments
=======
                            <label for="edit-images"
                                class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-[#18342f] text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 cursor-pointer transition-all border border-emerald-900/10 dark:border-[#1d3a34]">
                                Add Images
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            </label>
                            <input type="file" id="edit-images" class="hidden" multiple accept="image/*"
                                @change="handleEditImages($event)" />
                        </div>
                        <template x-if="editPreviewUrls.length > 0">
                            <div
<<<<<<< HEAD
                                class="flex flex-wrap gap-4 p-4 rounded-2xl bg-emerald-50/50 dark:bg-[#1e293b]/50 border border-emerald-950/10 dark:border-[#1e3a5f]">
                                {{-- Existing server attachments --}}
                                <template x-for="(img, i) in editExistingAttachments" :key="'existing-' + i">
=======
                                class="flex flex-wrap gap-4 p-4 rounded-2xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
                                <template x-for="(url, i) in editPreviewUrls" :key="i">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                                    <div class="relative group/ep">
                                        <img :src="url"
                                            class="w-20 h-20 rounded-xl object-cover border-2 border-white dark:border-[#1d3a34] shadow-sm" />
                                        <button type="button" @click="removeEditImage(i)"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover/ep:opacity-100 transition-opacity shadow-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button type="button" @click="closeEditModal()"
                            class="flex-1 py-4 px-6 rounded-2xl bg-slate-100 dark:bg-[#18342f] text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" x-bind:disabled="editSubmitting"
<<<<<<< HEAD
                            class="flex-[2] py-4 px-6 rounded-2xl bg-emerald-950 text-white font-black text-xs tracking-widest shadow-xl hover:bg-emerald-800 hover:text-white hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                        >
=======
                            class="flex-[2] py-4 px-6 rounded-2xl bg-teal-900 text-white font-black text-xs tracking-widest shadow-xl hover:bg-[#10b981] hover:text-[#064e3b] hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                            <template x-if="editSubmitting">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>
                            </template>
                            <span x-text="editSubmitting ? 'Saving...' : 'Edit Ticket'">Edit Ticket</span>
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>


    {{-- â”€â”€ Alpine.js component script â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    @push('scripts')
        @php
            $orderTypes = [
                ['id' => 'one-time', 'label' => 'One-Time Order'],
                ['id' => 'recurrent', 'label' => 'Recurring Order'],
            ];
        @endphp
        @php
            $recurrencePeriod = [
                ['id' => 'daily', 'label' => 'Daily'],
                ['id' => 'one-week', 'label' => 'Weekly'],
                ['id' => 'two-weeks', 'label' => 'Bi-Weekly'],
                ['id' => 'monthly', 'label' => 'Monthly'],
                ['id' => 'quarterly', 'label' => 'Quarterly'],
                ['id' => 'yearly', 'label' => 'Yearly'],
                ['id' => 'custom', 'label' => 'Custom'],
            ];
        @endphp

        <script>
            const ORDER_TYPES = @json($orderTypes);
            const RECURRENCE_PERIODS = @json($recurrencePeriod);

            const ROUTES = {
                statusUpdate: (id, status) => `/tickets/${id}/status/${status}`,
                delete: (id) => `/tickets/${id}`,
                bulkDelete: () => `/tickets/bulk-delete`,
                bulkStatus: () => `/tickets/bulk-status`,
                activate: (id) => `/tickets/${id}/activate-order`,
                // editTicket: (id) => `/tickets/${id}`,
                addComment: (id) => `/tickets/${id}/comments`,
            };

            function dashboard() {
                return {
                    allTickets: @json($tickets),
                    authId: {{ auth()->id() ?? 'null' }},
                    filters: {
                        status: '',
                        priority: '',
                        search: ''
                    },
                    selectedIds: [],
                    expandedId: null,
                    showMobileNotice: true,
                    rowsPerPage: 10,
                    currentPage: 1,
                    sortConfig: {
                        key: 'id',
                        direction: 'desc'
                    },
                    copiedId: null,
                    activatingId: null,

                    // Edit modal
                    editingTicket: null,
                    editData: {
                        category_id: '',
                        priority: 'medium',
                        content: ''
                    },
                    editFiles: [],
                    editPreviewUrls: [],
                    editSubmitting: false,

                    init() {
<<<<<<< HEAD
                        this.$watch('selectedIds', () => {
                            this.$dispatch('selection-changed', {
                                filtered: this.effectiveSelectedIds.length,
                                total: this.selectedIds.length
                            });
                        });
                        // Also re-dispatch when filters change so the header count updates
                        this.$watch('filters', () => {
                            this.$dispatch('selection-changed', {
                                filtered: this.effectiveSelectedIds.length,
                                total: this.selectedIds.length
                            });
                        }, { deep: true });

                        // â”€â”€ Status polling â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                        // Poll every 30 s so status badges stay in sync when another
                        // user (e.g. admin/support) changes a ticket in another session.
                        const pollStatuses = async () => {
                            try {
                                const r = await fetch('/tickets/statuses', {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                                if (!r.ok) return;
                                const statuses = await r.json(); // [{id, status}, ...]
                                let changed = false;
                                const updated = this.allTickets.map(t => {
                                    const fresh = statuses.find(s => s.id === t.id);
                                    if (fresh && fresh.status !== t.status) {
                                        changed = true;
                                        return { ...t, status: fresh.status };
                                    }
                                    return t;
                                });
                                if (changed) this.allTickets = updated;
                            } catch (_) { /* silent â€” network error */ }
                        };

                        // Start polling after a short delay so initial page render completes
                        setTimeout(() => {
                            pollStatuses();
                            setInterval(pollStatuses, 30000);
                        }, 5000);
                    },

                    // â”€â”€ Computed â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

                    // The IDs that are both selected AND currently visible after filtering
                    get effectiveSelectedIds() {
                        const filteredIds = new Set(this.filteredTickets.map(t => t.id));
                        return this.selectedIds.filter(id => filteredIds.has(id));
                    },

=======
                        this.$watch('selectedIds', v => this.$dispatch('selection-changed', v.length));
                    },

                    // ── Computed ──────────────────────────────────────────────────
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                    get filteredTickets() {
                        return this.allTickets.filter(t => {
                            const s = this.filters.search.toLowerCase();
                            return (!this.filters.status || t.status === this.filters.status) &&
                                (!this.filters.priority || t.priority === this.filters.priority) &&
                                (!s || t.hashid.toLowerCase().includes(s) ||
                                    t.subject.toLowerCase().includes(s) ||
                                    (t.user?.name || '').toLowerCase().includes(s));
                        });
                    },

                    get sortedTickets() {
                        const items = [...this.filteredTickets];
                        const {
                            key,
                            direction
                        } = this.sortConfig;
                        if (!key) return items;
                        return items.sort((a, b) => {
                            let av = key === 'user' ? a.user?.name : key === 'attendant' ? a.attendant?.name : a[
                                key];
                            let bv = key === 'user' ? b.user?.name : key === 'attendant' ? b.attendant?.name : b[
                                key];
                            if (av < bv) return direction === 'asc' ? -1 : 1;
                            if (av > bv) return direction === 'asc' ? 1 : -1;
                            return 0;
                        });
                    },

                    get totalPages() {
                        return Math.ceil(this.sortedTickets.length / this.rowsPerPage);
                    },

                    get paginatedTickets() {
                        const start = (this.currentPage - 1) * this.rowsPerPage;
                        return this.sortedTickets.slice(start, start + this.rowsPerPage);
                    },

                    get visiblePages() {
                        const pages = [];
                        for (let p = 1; p <= this.totalPages; p++) {
                            if (p === 1 || p === this.totalPages || (p >= this.currentPage - 1 && p <= this.currentPage +
                                    1)) {
                                pages.push(p);
                            } else if (p === this.currentPage - 2 || p === this.currentPage + 2) {
                                pages.push('...');
                            }
                        }
                        return pages;
                    },

                    // â”€â”€ Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    orderTypeLabel(id) {
                        return ORDER_TYPES.find(x => x.id === id)?.label ?? id;
                    },
                    recurrencePeriodLabel(id) {
                        return RECURRENCE_PERIODS.find(x => x.id === id)?.label ?? id;
                    },
                    formatDateTime(dt) {
                        return new Date(dt).toLocaleString('en-GB', {
                            dateStyle: 'short',
                            timeStyle: 'short'
                        });
                    },

                    getSortIcon(key) {
                        if (this.sortConfig.key !== key)
                            return `<svg class="w-3 h-3 opacity-20 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>`;
                        return this.sortConfig.direction === 'asc' ?
<<<<<<< HEAD
                            `<svg class="w-3 h-3 text-emerald-950 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>` :
                            `<svg class="w-3 h-3 text-emerald-950 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>`;
=======
                            `<svg class="w-3 h-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>` :
                            `<svg class="w-3 h-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>`;
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
                    },

                    requestSort(key) {
                        this.sortConfig = {
                            key,
                            direction: this.sortConfig.key === key && this.sortConfig.direction === 'asc' ? 'desc' : 'asc'
                        };
                    },

                    clearFilters() {
                        this.filters = {
                            status: '',
                            priority: '',
                            search: ''
                        };
                        this.currentPage = 1;
                    },
                    toggleExpand(id) {
                        this.expandedId = this.expandedId === id ? null : id;
                    },
                    toggleSelectAll() {
                        this.selectedIds = this.selectedIds.length === this.allTickets.length ? [] : this.allTickets.map(t => t
                            .id);
                    },
                    toggleSelect(id) {
                        this.selectedIds.includes(id) ? this.selectedIds.splice(this.selectedIds.indexOf(id), 1) : this
                            .selectedIds.push(id);
                    },

                    copyHashid(hashid) {
                        (navigator.clipboard?.writeText(hashid) ?? Promise.reject())
                        .catch(() => {
                            const ta = Object.assign(document.createElement('textarea'), {
                                value: hashid,
                                style: 'position:fixed;left:-9999px'
                            });
                            document.body.appendChild(ta);
                            ta.select();
                            document.execCommand('copy');
                            ta.remove();
                        });
                        this.copiedId = hashid;
                        setTimeout(() => this.copiedId = null, 2000);
                    },

                    // â”€â”€ Server actions â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

                    /**
                     * Laravel method-spoofed PATCH â€” always POST with _method=PATCH
                     * so PHP parses the FormData body correctly.
                     */
                    async patchFetch(url, body = {}) {
                        const form = new FormData();
                        form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                        form.append('_method', 'PATCH');
                        for (const [k, v] of Object.entries(body)) {
                            if (Array.isArray(v)) v.forEach(i => form.append(k + '[]', i));
                            else form.append(k, v);
                        }
                        return fetch(url, {
                            method: 'POST',
                            body: form,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    },

                    /**
                     * Laravel method-spoofed DELETE â€” always POST with _method=DELETE
                     * so PHP parses the FormData body correctly.
                     */
                    async deleteFetch(url, body = {}) {
                        const form = new FormData();
                        form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                        form.append('_method', 'DELETE');
                        for (const [k, v] of Object.entries(body)) {
                            if (Array.isArray(v)) v.forEach(i => form.append(k + '[]', i));
                            else form.append(k, v);
                        }
                        return fetch(url, {
                            method: 'POST',
                            body: form,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    },

                    async statusUpdate(id, status) {
                        await this.patchFetch(ROUTES.statusUpdate(id, status));
                        // Optimistically update local state
                        this.allTickets = this.allTickets.map(t => t.id === id ? {
                            ...t,
                            status
                        } : t);
                    },

                    async deleteTicket(id) {
                        const confirmed = await this.confirm({
                            type: 'danger',
                            title: 'Delete Ticket',
                            confirmText: 'Delete Ticket',
                            message: 'Are you sure you want to delete this ticket? This action cannot be undone.'
                        });
                        if (!confirmed) return;
                        const r = await this.deleteFetch(ROUTES.delete(id));
                        if (r.ok) {
                            this.allTickets = this.allTickets.filter(t => t.id !== id);
                            window.showToast('Ticket deleted successfully.');
                        } else {
                            window.showToast('Failed to delete ticket.', 'error');
                        }
                    },

                    async bulkDelete() {
                        const confirmed = await this.confirm({
                            type: 'danger',
                            title: 'Bulk Delete',
                            confirmText: `Delete ${this.selectedIds.length} Tickets`,
                            message: `Delete ${this.selectedIds.length} tickets? This cannot be undone.`
                        });
                        if (!confirmed) return;
                        const r = await this.deleteFetch(ROUTES.bulkDelete(), {
                            ids: this.selectedIds
                        });
                        if (r.ok) {
                            const count = this.selectedIds.length;
                            this.allTickets = this.allTickets.filter(t => !this.selectedIds.includes(t.id));
                            this.selectedIds = [];
                            window.showToast(`${count} tickets deleted successfully.`);
                        } else {
                            window.showToast('Failed to delete tickets.', 'error');
                        }
                    },

                    async bulkStatusChange(status) {
                        const r = await this.patchFetch(ROUTES.bulkStatus(), {
                            ids: this.selectedIds,
                            status
                        });
                        if (r.ok) {
                            this.allTickets = this.allTickets.map(t => this.selectedIds.includes(t.id) ? {
                                ...t,
                                status
                            } : t);
                            this.selectedIds = [];
                        }
                    },

                    async activateOrder(id) {
                        const ticket = this.allTickets.find(t => t.id === id);
                        if (!ticket) return;
                        const activations = Array.isArray(ticket.order_activations) ? ticket.order_activations : [];
                        if (activations.length > 0) {
                            const last = new Date(activations[activations.length - 1]);
                            const diffDays = (Date.now() - last) / 86400000;
                            const required = {
                                daily: 1,
                                'one-week': 7,
                                weekly: 7,
                                'two-weeks': 14,
                                monthly: 30,
                                quarterly: 90,
                                yearly: 365
                            } [ticket.recurrence_period?.toLowerCase().trim()] ?? 0;
                            if (required > 0 && diffDays < required) {
                                // Build human-readable elapsed time
                                const totalDiff = Math.floor(diffDays);
                                const months = Math.floor(totalDiff / 30);
                                const days = totalDiff % 30;
                                let elapsed = '';
                                if (months > 0 && days > 0) {
                                    elapsed =
                                        `${months} month${months > 1 ? 's' : ''} and ${days} day${days > 1 ? 's' : ''}`;
                                } else if (months > 0) {
                                    elapsed = `${months} month${months > 1 ? 's' : ''}`;
                                } else if (days > 0) {
                                    elapsed = `${days} day${days > 1 ? 's' : ''}`;
                                } else {
                                    elapsed = 'less than a day';
                                }

                                // Build human-readable required period
                                const reqMonths = Math.floor(required / 30);
                                const reqDays = required % 30;
                                let reqLabel = '';
                                if (reqMonths > 0 && reqDays > 0) {
                                    reqLabel =
                                        `${reqMonths} month${reqMonths > 1 ? 's' : ''} and ${reqDays} day${reqDays > 1 ? 's' : ''}`;
                                } else if (reqMonths > 0) {
                                    reqLabel = `${reqMonths} month${reqMonths > 1 ? 's' : ''}`;
                                } else {
                                    reqLabel = `${required} day${required > 1 ? 's' : ''}`;
                                }

                                const ok = await this.confirm({
                                    type: 'warning',
                                    confirmText: 'Confirm & Process',
                                    title: 'Early Processing Warning',
                                    message: `This ${ticket.recurrence_period} order was last processed ${elapsed} ago. The required interval is ${reqLabel}. Proceed anyway?`
                                });
                                if (!ok) return;
                            }
                        }
                        this.activatingId = id;
                        const r = await this.patchFetch(ROUTES.activate(id));
                        this.activatingId = null;
                        if (r.ok) {
                            try {
                                const data = await r.json();
                                ticket.status = 'in-progress';
                                ticket.attendant = data.attendant;
                                ticket.attendants = data.attendants;
                                if (!Array.isArray(ticket.order_activations)) {
                                    ticket.order_activations = [];
                                }
                                ticket.order_activations.push(new Date().toISOString());
                                window.showToast('Order processed successfully.');
                            } catch (e) {
                                window.location.reload();
                            }
                        } else {
                            window.showToast('Failed to process order.', 'error');
                        }
                    },

                    // Edit modal
                    openEditModal(ticket) {
                        this.editingTicket = ticket;
                        this.editData = {
                            category_id: ticket.category_id || '',
                            priority: ticket.priority,
                            content: ticket.content
                        };
                        this.editPreviewUrls = (ticket.images || []).map(img => '/storage/' + img);
                        this.editFiles = [];
                    },
                    closeEditModal() {
                        this.editingTicket = null;
                        this.editPreviewUrls = [];
                        this.editFiles = [];
                    },
                    handleEditImages(e) {
                        this.editFiles = Array.from(e.target.files);
                        this.editPreviewUrls = this.editFiles.map(f => URL.createObjectURL(f));
                    },
                    removeEditImage(i) {
                        this.editFiles.splice(i, 1);
                        this.editPreviewUrls.splice(i, 1);
                    },
                    async submitEdit() {
                        this.editSubmitting = true;
                        const form = new FormData();
                        form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                        form.append('_method', 'PATCH');
                        form.append('category_id', this.editData.category_id);
                        form.append('priority', this.editData.priority);
                        form.append('content', this.editData.content);
                        this.editFiles.forEach(f => form.append('images[]', f));
                        await fetch(ROUTES.editTicket(this.editingTicket.id), {
                            method: 'POST',
                            body: form,
                            redirect: 'manual'
                        });
                        this.editSubmitting = false;
                        this.closeEditModal();
                        window.location.reload();
                    },

                    // Confirm helper â€” integrates with your existing confirm modal
                    confirm(opts) {
                        return new Promise(resolve => {
                            this.$dispatch('confirm', {
                                ...opts,
                                onConfirm: () => resolve(true),
                                onCancel: () => resolve(false)
                            });
                        });
                    },
                };
            }

            function timeAgo(dateStr) {
                const date = new Date(dateStr);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);

                const intervals = [
                    { label: 'y',  secs: 31536000 },
                    { label: 'mo', secs: 2592000 },
                    { label: 'd',  secs: 86400 },
                    { label: 'h',  secs: 3600 },
                    { label: 'm',  secs: 60 },
                ];

                for (const i of intervals) {
                    const count = Math.floor(seconds / i.secs);
                    if (count >= 1) {
                        return `${count}${i.label} ago`;
                    }
                }
                return 'just now';
            }

            function commentForm(ticketId) {
                return {
                    content: '',
                    files: [],
                    previews: [],
                    submitting: false,
                    handleImages(e) {
                        this.files = Array.from(e.target.files);
                        this.previews = this.files.map(f => URL.createObjectURL(f));
                    },
                    removeImage(i) {
                        this.files.splice(i, 1);
                        this.previews.splice(i, 1);
                    },
                    async submit() {
                        if (!this.content.trim()) return;
                        this.submitting = true;
                        const form = new FormData();
                        form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                        form.append('content', this.content);
                        this.files.forEach(f => form.append('images[]', f));
                        const r = await fetch(`/tickets/${ticketId}/comments`, {
                            method: 'POST',
                            body: form,
                            redirect: 'manual'
                        });
                        this.submitting = false;
                        if (r.ok || r.redirected || r.type === 'opaqueredirect') {
                            this.content = '';
                            this.files = [];
                            this.previews = [];
                            window.location.reload();
                        }
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
