<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4" x-data="{ selectedCount: 0 }"
            @selection-changed.window="selectedCount = $event.detail">
            <div
                class="w-12 h-12 rounded-2xl bg-sky-950 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard</h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Overview & Management</span>
            </div>
            <div x-show="selectedCount > 0" x-cloak
                class="ml-auto flex items-center px-4 py-2 rounded-xl bg-sky-950 text-white text-[10px] font-black tracking-widest shadow-lg">
                Selected: <span x-text="selectedCount"></span> tickets
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Dashboard</x-slot>


    {{--
    =====================================================================
    Alpine component — all client-side state lives here.
    Data flows: PHP renders JSON into x-data, Alpine drives the UI.
    =====================================================================
    --}}
    <div class="max-w-[98%] xl:max-w-[1700px] mx-auto py-2 px-2 sm:px-4 lg:px-6" x-data="dashboard()"
        x-init="init()"
        @comment-added.window="handleNewComment($event.detail)">

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
                    <div x-show="selectedIds.length > 0" x-cloak
                        class="flex items-center space-x-2 p-1.5 md:p-2 bg-slate-100 dark:bg-[#0f172a] rounded-2xl border border-sky-950/10 dark:border-[#1e3a5f]">
                        <span class="hidden sm:inline text-xs font-bold text-slate-600 dark:text-slate-400 px-2"
                            x-text="selectedIds.length + ' Selected'"></span>

                        <select
                            @change="
                                selectedIds.length
                                    ? (bulkStatusChange($event.target.value), $event.target.value = '')
                                    : ($event.target.value = '')
                            "
                            :class="{ 'opacity-40 cursor-not-allowed': !selectedIds.length }"
                            class="text-[10px] md:text-xs font-black bg-white dark:bg-[#1e293b] text-slate-600 dark:text-slate-300 border-none rounded-xl focus:ring-2 focus:ring-sky-400 py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10">
                            <option value="" disabled selected>Change Status of all selected</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>

                        <button @click="bulkDelete()"
                            class="p-1.5 md:p-2 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all"
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
                        class="w-full md:w-auto text-center px-6 py-3 bg-sky-950 text-white rounded-2xl font-black text-xs tracking-widest shadow-xl hover:bg-sky-800 hover:text-white hover:scale-105 active:scale-95 transition-all">
                        Submit a New Ticket
                    </a>
                @endif
            </div>
        </div>

        <x-flash-handler />

        {{-- Filter bar --}}
        <div
            class="flex flex-wrap items-center gap-3 md:gap-4 mb-6 p-4 rounded-2xl bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-sky-950/10 dark:border-[#1e3a5f]">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Filters:</span>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3 w-full sm:w-auto">
                <select x-model="filters.status" @change="currentPage = 1"
                    class="bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-sky-400 outline-none transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="in-progress">In-Progress</option>
                    <option value="closed">Closed</option>
                </select>

                <select x-model="filters.priority" @change="currentPage = 1"
                    class="bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-medium text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-sky-400 outline-none transition-all cursor-pointer">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            {{-- Search --}}
            <div class="relative group w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-sky-950 dark:group-focus-within:text-sky-400 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model.debounce.300ms="filters.search" @input="currentPage = 1"
                    placeholder="Search by ID, subject, or user..."
                    class="block w-full pl-12 pr-4 py-3 rounded-2xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-sky-400 focus:border-transparent transition-all shadow-sm" />
            </div>

            <button x-show="filters.status || filters.priority || filters.search" @click="clearFilters()"
                    class="flex-shrink-0 text-[10px] md:text-xs font-bold text-rose-500 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-rose-500/10 transition-all flex items-center justify-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear All
                </button>

            <div class="order-last flex items-center gap-4 w-full sm:w-auto sm:ml-auto">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Rows:</span>
                    <select x-model.number="rowsPerPage" @change="currentPage = 1"
                        class="text-[10px] font-black bg-slate-100 dark:bg-[#1e293b] text-slate-600 dark:text-slate-300 border-none rounded-lg focus:ring-2 focus:ring-sky-400 py-1 pl-2 pr-8 transition-all cursor-pointer">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="text-[10px] font-black tracking-[0.2em] text-slate-400"
                    x-text="'Showing ' + ((currentPage - 1) * rowsPerPage + 1) + ' – ' + Math.min(currentPage * rowsPerPage, sortedTickets.length) + ' of ' + sortedTickets.length">
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
                        <span class="text-[11px] font-medium leading-relaxed">Some columns are hidden on smaller screens. Switch
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

        {{-- ── Tickets table ──────────────────────────────────────────────── --}}
        <div
            class="relative group overflow-hidden rounded-2xl fauna-panel transition-all duration-500 hover:shadow-sky-400/10">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left min-w-[800px] lg:min-w-full border-collapse">
                    <thead>
                        <tr class="border-b border-sky-950/10 dark:border-[#1e3a5f]">
                            {{-- Select-all --}}
                            <th class="w-12 md:w-16 px-4 md:px-6 py-4">
                                @if (in_array(auth()->user()->role, ['admin', 'support']))
                                    <label class="flex items-center px-1 cursor-pointer select-none group">
                                        <input type="checkbox"
                                            class="hidden"
                                            :checked="allTickets.length > 0 && selectedIds.length === allTickets.length"
                                            @change="toggleSelectAll()"
                                        />
                                        <span
                                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                            :class="(allTickets.length > 0 && selectedIds.length === allTickets.length) ? 'bg-[#1e3a8a] border-[#1e3a8a]' : 'border-slate-300 dark:border-[#1e3a5f] bg-white dark:bg-[#1e293b]'"
                                        >
                                            <svg x-show="allTickets.length > 0 && selectedIds.length === allTickets.length" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
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

                    <tbody x-show="paginatedTickets.length === 0" x-cloak class="divide-y divide-slate-200 dark:divide-slate-800">
                        <tr>
                            <td colspan="{{ in_array(auth()->user()->role, ['admin', 'support']) ? 9 : 7 }}"
                                class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-slate-100 dark:bg-[#1e293b] rounded-3xl mb-4">
                                        <svg class="w-12 h-12 text-slate-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
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
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 border-b border-slate-200/50 dark:border-[#1e3a5f]/30">
                            {{-- Main row --}}
                            <tr class="group hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70 transition-all duration-300 cursor-pointer"
                                :class="expandedId === ticket.id ? 'bg-sky-50/50/80 dark:bg-[#1e293b]/80' : ''"
                                @click="toggleExpand(ticket.id)">
                                    {{-- Checkbox --}}
                                    <td class="px-4 md:px-6 py-4" @click.stop>
                                        @if (in_array(auth()->user()->role, ['admin', 'support']))
                                            <label class="flex items-center px-1 cursor-pointer select-none group">
                                                <input type="checkbox"
                                                    class="hidden"
                                                    :checked="selectedIds.includes(ticket.id)"
                                                    @change="toggleSelect(ticket.id)"
                                                />
                                                <span
                                                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
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
                                    <td class="px-4 md:px-6 py-4" @click.stop>
                                        <span
                                            class="text-[10px] md:text-sm font-bold text-slate-600 group-hover:text-sky-950 dark:group-hover:text-sky-400 transition-colors tracking-tight"
                                            x-text="'#' + ticket.hashid"></span>
                                    </td>

                                    {{-- Subject + snippet --}}
                                    <td class="px-4 md:px-6 py-4">

                                        <div class="text-[11px] md:text-sm font-bold text-slate-900 dark:text-white group-hover:translate-x-1 transition-transform duration-300 line-clamp-1"
                                            x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase())">
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
                                        </td>
                                    @endif

                                    {{-- Priority --}}
                                    <td class="px-4 md:px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider"
                                            :class="{
                                                'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400': ticket
                                                    .priority === 'high',
                                                'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400': ticket
                                                    .priority === 'medium',
                                                'bg-sky-100 text-sky-600 dark:bg-sky-950/30 dark:text-sky-400': ticket
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
                                            <select
                                                @change="statusUpdate(ticket.id, $event.target.value)"
                                                class="text-[10px] md:text-xs font-black tracking-widest rounded-xl border-2 border-sky-400 bg-transparent focus:ring-2 focus:ring-sky-400 focus:outline-none cursor-pointer py-1 md:py-2 pl-2 pr-8 md:pl-4 md:pr-10 transition-all"
                                                :class="{
                                                    'border-sky-400 dark:border-sky-500 text-sky-600 dark:text-sky-400': ticket.status === 'open',
                                                    'border-sky-500 dark:border-sky-400 text-sky-600 dark:text-sky-400': ticket.status === 'in-progress',
                                                    'border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400': ticket.status === 'closed'
                                                }">
                                                <option value="open"      :selected="ticket.status === 'open'">Open</option>
                                                <option value="in-progress" :selected="ticket.status === 'in-progress'">Processing</option>
                                                <option value="closed"    :selected="ticket.status === 'closed'">Resolved</option>
                                            </select>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold"
                                                :class="{
                                                    'bg-sky-100 text-sky-600 dark:bg-sky-950/30 dark:text-sky-400 ring-4 ring-sky-500/10': ticket
                                                        .status === 'open',
                                                    'bg-sky-100 text-sky-600 dark:bg-sky-950/30 dark:text-sky-400 ring-4 ring-sky-500/10': ticket
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
                                                        class="p-1.5 md:p-2 rounded-lg bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white transition-all shadow-sm"
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
                                                :class="expandedId === ticket.id ? 'bg-sky-950 text-white rotate-180' :
                                                    'bg-slate-100 dark:bg-[#1e293b] text-slate-600 hover:text-sky-950 dark:hover:text-sky-400'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            @if (in_array(auth()->user()->role, ['admin', 'support', 'user']))
                                                {{-- Delete --}}
                                                <button @click.stop="deleteTicket(ticket.id)"
                                                    class="p-1.5 md:p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
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

                                {{-- ── Expanded row ──────────────────────────────────────── --}}
                                <tr class="bg-sky-50/50 dark:bg-[#1e293b]/30" x-show="expandedId === ticket.id" x-cloak>
                                        <td colspan="{{ in_array(auth()->user()->role, ['admin', 'support']) ? 9 : 7 }}"
                                            class="px-4 md:px-10 py-4 md:py-8 border-l-4 border-sky-400">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12">

                                                {{-- Left: specs + attachments --}}
                                                <div class="space-y-8">
                                                    <div>
                                                        <h4
                                                            class="text-xl font-black text-slate-900 dark:text-white mb-4 flex items-center tracking-tight">
                                                            <svg class="w-5 h-5 mr-3 text-sky-950 dark:text-sky-400"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Specifications
                                                        </h4>
                                                        <div
                                                            class="p-5 sm:p-8 rounded-2xl sm:rounded-[2.5rem] bg-white dark:bg-[#0f172a] border border-sky-950/10 dark:border-[#1e3a5f] shadow-sm relative overflow-hidden">
                                                            <div
                                                                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-400 to-transparent opacity-30">
                                                            </div>

                                                            <div
                                                                class="text-[10px] font-black text-sky-950 dark:text-sky-400 mb-2 tracking-[0.3em] uppercase">
                                                                Creator Information</div>

                                                                <div class="mt-2 mb-6">
                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.name || ticket.user?.name">
                                                                            <div
                                                                                class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                                <svg class="w-3 h-3 text-sky-600 dark:text-sky-400 shrink-0"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>

                                                                                <span x-text="ticket.name || ticket.user?.name"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.email || ticket.user?.email">
                                                                            <div
                                                                                class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                                <svg class="w-3 h-3 text-sky-600 dark:text-sky-400 shrink-0"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                                </svg>

                                                                                <span x-text="ticket.email || ticket.user?.email"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <div class="space-y-1.5">
                                                                        <template x-if="ticket.phone_number || ticket.user?.phone_number">
                                                                            <div
                                                                                class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                                                                <svg class="w-3 h-3 text-sky-600 dark:text-sky-400 shrink-0"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-1.687.845a11.042 11.042 0 005.516 5.516l.845-1.687a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                                </svg>

                                                                                <span x-text="ticket.phone_number || ticket.user?.phone_number"></span>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                            <div
                                                                class="text-[10px] font-black text-sky-950 dark:text-sky-400 mb-2 tracking-[0.3em] uppercase">
                                                                Reference</div>
                                                            <div class="flex items-center gap-3 mb-8">
                                                                <div class="text-2xl text-slate-900 dark:text-white font-black tracking-tight"
                                                                    x-text="ticket.hashid"></div>
                                                                <button @click.stop="copyHashid(ticket.hashid)"
                                                                    class="flex items-center gap-2 px-2 py-1 rounded-lg bg-slate-100 dark:bg-[#1e293b] text-slate-600 hover:text-sky-950 dark:hover:text-sky-400 transition-all border border-transparent hover:border-sky-950/20">
                                                                    <template x-if="copiedId === ticket.hashid">
                                                                        <span
                                                                            class="flex items-center gap-1 text-[10px] font-bold text-sky-500 tracking-wider">
                                                                            <svg class="w-4 h-4" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M5 13l4 4L19 7" />
                                                                            </svg>
                                                                            Copied!
                                                                        </span>
                                                                    </template>
                                                                    <template x-if="copiedId !== ticket.hashid">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2.5"
                                                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                                        </svg>
                                                                    </template>
                                                                </button>
                                                            </div>

                                                            <div
                                                                class="text-[10px] font-black text-sky-950 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">
                                                                Subject</div>
                                                            <div class="text-xl text-slate-900 dark:text-white font-bold mb-6"
                                                                x-text="ticket.category ? ticket.category.name : ticket.subject.replace(/_/g, ' ')">
                                                            </div>

                                                            <div
                                                                class="text-[10px] font-black text-sky-950 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">
                                                                Description</div>
                                                            <div class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-sm mb-6"
                                                                x-text="ticket.content"></div>

                                                            {{-- Attachments --}}
                                                            <template
                                                                x-if="(ticket.attachments && ticket.attachments.length > 0) || ticket.filename">
                                                                <div>
                                                                    <h4
                                                                        class="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center uppercase">
                                                                        <svg class="w-4 h-4 mr-2 text-sky-500"
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
                                                                                target="_blank"
                                                                                class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">

                                                                                {{-- Image preview --}}
                                                                                <template x-if="isImage(ticket.filename)">
                                                                                    <img :src="'/storage/' + ticket.filename" :alt="ticket.filename"
                                                                                        class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                </template>

                                                                                {{-- Document icon --}}
                                                                                <template x-if="!isImage(ticket.filename)">
                                                                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                        </svg>
                                                                                        <span class="text-[9px] font-black text-slate-500" x-text="fileExt(ticket.filename)"></span>
                                                                                    </div>
                                                                                </template>

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
                                                                        <template x-for="(img, i) in (ticket.attachments || [])"
                                                                            :key="i">
                                                                            <a :href="'/storage/' + img" target="_blank"
                                                                                class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">

                                                                                {{-- Image preview --}}
                                                                                <template x-if="isImage(img)">
                                                                                    <img :src="'/storage/' + img"
                                                                                        class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                </template>

                                                                                {{-- Document icon --}}
                                                                                <template x-if="!isImage(img)">
                                                                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                        </svg>
                                                                                        <span class="text-[9px] font-black text-slate-500" x-text="fileExt(img)"></span>
                                                                                    </div>
                                                                                </template>

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

                                                            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-[#1e3a5f]/50">
                                                                <div class="text-[10px] font-black text-sky-950 dark:text-sky-400 mb-2 tracking-[0.3em] uppercase">
                                                                    Attending Support Staff
                                                                </div>

                                                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                                                                    Past
                                                                </div>
                                                                <div class="flex flex-wrap gap-3 mb-4">
                                                                    <template x-if="ticket.attendants && ticket.attendants.filter(a => a.id !== ticket.attendant?.id).length > 0">
                                                                        <template x-for="att in ticket.attendants.filter(a => a.id !== ticket.attendant?.id)" :key="att.id">
                                                                            <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-sky-950/10 dark:border-[#28524a]">
                                                                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600"
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
                                                                        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-sky-950/10 dark:border-[#28524a]">
                                                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600"
                                                                                x-text="ticket.attendant.name ? ticket.attendant.name.charAt(0).toUpperCase() : '?'"></div>
                                                                            <span class="text-xs font-bold text-slate-900 dark:text-white" x-text="ticket.attendant.name"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="!ticket.attendant">
                                                                        <span class="text-xs italic text-slate-400">No current support staff assigned yet.</span>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Right: comments + comment form --}}
                                                <div class="space-y-8">
                                                    <div>
                                                        <h4 class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                                                            <svg class="w-5 h-5 mr-3 text-sky-950 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                                            Conversation
                                                        </h4>

                                                        <div class="fauna-panel mb-6 p-4 md:p-6 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar relative overflow-hidden space-y-4">
                                                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-400 to-transparent opacity-40"></div>
                                                            <template
                                                                x-if="!ticket.comments || ticket.comments.length === 0">
                                                                <div class="text-center py-2 opacity-40">
                                                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                                    <div class="italic text-sm">No comments yet. Start the conversation.</div>
                                                                </div>
                                                            </template>

                                                            <template x-for="(comment, ci) in [...(ticket.comments || [])].sort((a, b) => new Date(a.created_at) - new Date(b.created_at))" :key="ci">
                                                                <div class="flex flex-col"
                                                                    :class="(comment.user && (comment.user.role === 'support' || comment.user.role === 'admin')) ? 'items-end' : 'items-start'">
                                                                    <div class="max-w-[95%] sm:max-w-[85%] p-4 sm:p-5"
                                                                        :class="(comment.user && (comment.user.role === 'support' || comment.user.role === 'admin'))
                                                                            ? 'bg-sky-950 text-white shadow-xl rounded-2xl sm:rounded-[2rem] rounded-br-sm sm:rounded-br-md'
                                                                            : 'fauna-panel text-slate-900 dark:text-white rounded-2xl sm:rounded-[2rem] rounded-bl-sm sm:rounded-bl-md'">
                                                                        <div class="flex items-center space-x-3 mb-2">
                                                                            <span class="text-[9px] font-black tracking-widest opacity-80" x-text="comment.user?.name"></span>
                                                                            <template x-if="comment.user && (comment.user.role === 'support' || comment.user.role === 'admin')">
                                                                                <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider rounded bg-white/20 text-white">Support</span>
                                                                            </template>

                                                                            <span class="text-[9px] opacity-40" x-text="timeAgo(comment.created_at) + ' · ' + new Date(comment.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                                                        </div>
                                                                        <div class="text-sm font-medium leading-relaxed" x-text="comment.content"></div>
                                                                        <template x-if="comment.attachments && comment.attachments.length > 0">
                                                                            <div class="flex flex-wrap gap-2 mt-3">
                                                                                <template x-for="(cimg, cii) in (comment.attachments || [])" :key="cii">
                                                                                    <a :href="'/storage/' + cimg" target="_blank"
                                                                                        class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">

                                                                                        {{-- Image preview --}}
                                                                                        <template x-if="isImage(cimg)">
                                                                                            <img :src="'/storage/' + cimg"
                                                                                                class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                        </template>

                                                                                        {{-- Document icon --}}
                                                                                        <template x-if="!isImage(cimg)">
                                                                                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-[#1e293b] gap-1">
                                                                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                                </svg>
                                                                                                <span class="text-[9px] font-black text-slate-500" x-text="fileExt(cimg)"></span>
                                                                                            </div>
                                                                                        </template>

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
                    class="px-6 py-4 border-t border-sky-950/10 dark:border-[#1e3a5f] flex items-center justify-between bg-slate-50/50 dark:bg-[#0f172a]/50">
                    <div class="text-[10px] font-black text-slate-400 tracking-widest uppercase"
                        x-text="'Page ' + currentPage + ' of ' + totalPages"></div>
                    <div class="flex items-center gap-2">
                        <button @click="currentPage = Math.max(currentPage - 1, 1)" x-bind:disabled="currentPage === 1"
                            class="p-2 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400 hover:text-sky-950 dark:hover:text-sky-400 disabled:opacity-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-1">
                            <template x-for="page in visiblePages" :key="page">
                                <span>
                                    <template x-if="page === '...'">
                                        <span class="text-slate-300 dark:text-slate-700 text-[10px]">...</span>
                                    </template>
                                    <template x-if="page !== '...'">
                                        <button @click="currentPage = page"
                                            class="w-8 h-8 rounded-xl text-[10px] font-black transition-all"
                                            :class="currentPage === page ? 'bg-sky-950 text-white shadow-lg shadow-sky-950/20' :
                                                'text-slate-400 hover:text-sky-950 dark:hover:text-sky-400'"
                                            x-text="page"></button>
                                    </template>
                                </span>
                            </template>
                        </div>
                        <button @click="currentPage = Math.min(currentPage + 1, totalPages)"
                            x-bind:disabled="currentPage === totalPages"
                            class="p-2 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400 hover:text-sky-950 dark:hover:text-sky-400 disabled:opacity-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- ── Edit modal ─────────────────────────────────────────────────── --}}
        {{-- <div x-show="editingTicket !== null" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
            @keydown.escape.window="closeEditModal()">
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f172a] rounded-3xl shadow-2xl border border-sky-950/10 dark:border-[#1e3a5f] p-8"
                @click.stop>
                <button @click="closeEditModal()"
                    class="absolute top-6 right-6 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all">
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
                                class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none transition-all">
                                <option value="" disabled>Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Priority</label>
                            <select x-model="editData.priority"
                                class="w-full pl-4 pr-10 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none transition-all">
                                <option value="low">⬇️ Low</option>
                                <option value="medium">⚡ Medium</option>
                                <option value="high">🚩 High</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Content</label>
                        <textarea x-model="editData.content" rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Attachments</label>
                            <label for="edit-attachments"
                                class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-[#1e293b] text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-sky-950 dark:hover:text-sky-400 cursor-pointer transition-all border border-sky-950/10 dark:border-[#1e3a5f]">
                                Add attachments
                            </label>
                            <input type="file" id="edit-attachments" class="hidden" multiple accept="image/*,txt,test/plain,xls,xlsx,.pdf,.doc,.docx,application/vnd.ms-excel,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetxml.sheet"
                                @change="handleEditAttachments($event)" />
                        </div>
                        <template x-if="editPreviewUrls.length > 0">
                            <div
                                class="flex flex-wrap gap-4 p-4 rounded-2xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f]">
                                <template x-for="(url, i) in editPreviewUrls" :key="i">
                                    <div class="relative group/ep">
                                        <img :src="url"
                                            class="w-20 h-20 rounded-xl object-cover border-2 border-white dark:border-[#1e3a5f] shadow-sm" />
                                        <button type="button" @click="removeEditAttachment(i)"
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
                            class="flex-1 py-4 px-6 rounded-2xl bg-slate-100 dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" x-bind:disabled="editSubmitting"
                            class="flex-[2] py-4 px-6 rounded-2xl bg-sky-950 text-white font-black text-xs tracking-widest shadow-xl hover:bg-sky-800 hover:text-white hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                        >
                            <template x-if="editSubmitting">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                            </template>
                            <span x-text="editSubmitting ? 'Saving...' : 'Edit Ticket'">Edit Ticket</span>
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>


    {{-- ── Alpine.js component script ────────────────────────────────────── --}}
    @push('scripts')
        <script>
            const ROUTES = {
                statusUpdate: (id, status) => `/tickets/${id}/status/${status}`,
                delete: (id) => `/tickets/${id}`,
                bulkDelete: () => `/tickets/bulk-delete`,
                bulkStatus: () => `/tickets/bulk-status`,
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
                        this.$watch('selectedIds', v => this.$dispatch('selection-changed', v.length));
                    },

                    // ── Computed ──────────────────────────────────────────────────
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
                            let av = key === 'user' ? a.user?.name : key === 'attendant' ? a.attendant?.name : a[key];
                            let bv = key === 'user' ? b.user?.name : key === 'attendant' ? b.attendant?.name : b[key];
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
                            if (p === 1 || p === this.totalPages || (p >= this.currentPage - 1 && p <= this.currentPage + 1)) {
                                pages.push(p);
                            } else if (p === this.currentPage - 2 || p === this.currentPage + 2) {
                                pages.push('...');
                            }
                        }
                        return pages;
                    },

                    // ── Helpers ───────────────────────────────────────────────────
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

                    // Returns true if the given filename/path looks like an image based on extension
                    isImage(filename) {
                        if (!filename) return false;
                        const ext = filename.split('.').pop().toLowerCase().split('?')[0];
                        return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(ext);
                    },

                    // Returns the uppercase file extension for a filename/path (used as the document-icon label)
                    fileExt(filename) {
                        if (!filename) return '';
                        return filename.split('.').pop().toUpperCase().split('?')[0];
                    },

                    getSortIcon(key) {
                        if (this.sortConfig.key !== key)
                            return `<svg class="w-3 h-3 opacity-20 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>`;
                        return this.sortConfig.direction === 'asc' ?
                            `<svg class="w-3 h-3 text-sky-950 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>` :
                            `<svg class="w-3 h-3 text-sky-950 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>`;
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
                        this.selectedIds = this.selectedIds.length === this.allTickets.length ? [] : this.allTickets.map(t => t.id);
                    },
                    toggleSelect(id) {
                        this.selectedIds.includes(id) ? this.selectedIds.splice(this.selectedIds.indexOf(id), 1) : this.selectedIds.push(id);
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

                    // ── Server actions ────────────────────────────────────────────

                    /**
                     * Laravel method-spoofed PATCH — always POST with _method=PATCH
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
                     * Laravel method-spoofed DELETE — always POST with _method=DELETE
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
                        this.allTickets = this.allTickets.map(t => t.id === id ? { ...t, status } : t);
                    },

                    handleNewComment({ ticketId, comment }) {
                        const ticket = this.allTickets.find(t => t.id === ticketId);
                        if (ticket) {
                            if (!ticket.comments) ticket.comments = [];
                            ticket.comments.push(comment);
                        }
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
                        const r = await this.deleteFetch(ROUTES.bulkDelete(), { ids: this.selectedIds });
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
                        const r = await this.patchFetch(ROUTES.bulkStatus(), { ids: this.selectedIds, status });
                        if (r.ok) {
                            this.allTickets = this.allTickets.map(t => this.selectedIds.includes(t.id) ? { ...t, status } : t);
                            this.selectedIds = [];
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
                        this.editPreviewUrls = (ticket.attachments || []).map(img => '/storage/' + img);
                        this.editFiles = [];
                    },
                    closeEditModal() {
                        this.editingTicket = null;
                        this.editPreviewUrls = [];
                        this.editFiles = [];
                    },
                    handleEditAttachments(e) {
                        this.editFiles = Array.from(e.target.files);
                        this.editPreviewUrls = this.editFiles.map(f => URL.createObjectURL(f));
                    },
                    removeEditAttachment(i) {
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
                        this.editFiles.forEach(f => form.append('attachments[]', f));
                        await fetch(ROUTES.editTicket(this.editingTicket.id), {
                            method: 'POST',
                            body: form,
                            redirect: 'manual'
                        });
                        this.editSubmitting = false;
                        this.closeEditModal();
                        window.location.reload();
                    },

                    // Confirm helper — integrates with your existing confirm modal
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
                    handleAttachments(e) {
                        const newFiles = Array.from(e.target.files);
                        this.files = [...this.files, ...newFiles];
                        this.previews = [...this.previews, ...newFiles.map(f => ({
                            url: URL.createObjectURL(f),
                            name: f.name,
                            isImage: f.type.startsWith('image/')
                        }))];
                    },
                    removeAttachment(i) {
                        URL.revokeObjectURL(this.previews[i].url);
                        this.files.splice(i, 1);
                        this.previews.splice(i, 1);
                    },
                    async submit() {
                        if (!this.content.trim()) return;
                        this.submitting = true;
                        try {
                            const form = new FormData();
                            form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                            form.append('content', this.content);
                            this.files.forEach(f => form.append('attachments[]', f));
                            const r = await fetch(`/tickets/${ticketId}/comments`, {
                                method: 'POST',
                                body: form,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            if (r.ok) {
                                const data = await r.json();
                                if (data.success && data.comment) {
                                    window.dispatchEvent(new CustomEvent('comment-added', {
                                        detail: { ticketId: ticketId, comment: data.comment }
                                    }));
                                }
                                this.content = '';
                                this.files = [];
                                this.previews = [];
                                window.showToast('Comment posted successfully.');
                            } else {
                                window.showToast('Failed to post comment. Please try again.', 'error');
                            }
                        } catch (err) {
                            console.error('Comment error:', err);
                            window.showToast('An error occurred. Please try again.', 'error');
                        } finally {
                            this.submitting = false;
                        }
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
