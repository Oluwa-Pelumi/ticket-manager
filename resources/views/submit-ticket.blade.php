@php
    $orderType = [
        ['id' => 'one-time', 'label' => 'One Time'],
        ['id' => 'recurrent', 'label' => 'Recurrent Order'],
    ];

    $recurrencePeriod = [
        ['id' => 'one-week', 'label' => 'Weekly'],
        ['id' => 'two-weeks', 'label' => 'Once every 2 weeks'],
        ['id' => 'three-weeks', 'label' => 'Once every 3 weeks'],
        ['id' => 'monthly', 'label' => 'Monthly'],
        ['id' => 'yearly', 'label' => 'Yearly'],
        ['id' => 'custom', 'label' => 'Pick Date'],
    ];

    $user = auth()->user();
@endphp

<x-app-layout>

    <x-slot name="title">Submit Ticket</x-slot>

    <div class="fauna-shell relative min-h-screen flex flex-col items-center overflow-x-hidden transition-colors duration-500 selection:bg-rose-400 selection:text-rose-950">

        {{-- Background Layer --}}
        <div class="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10"></div>

        <div class="relative z-10 w-full max-w-3xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14 pb-12 sm:pb-16">

            {{-- Header --}}
            <div class="fauna-panel mb-6 sm:mb-10 p-4 sm:p-6 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-rose-400 to-transparent opacity-40"></div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-700 flex items-center justify-center shadow-lg border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            Support
                        </h1>
                        <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">
                            Submit Ticket
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center mb-10">
                <a href="{{ route('home') }}" class="inline-flex items-center text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-rose-950 transition-colors mb-4 tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Homepage
                </a>
            </div>

            @if(session('success') || session('error') || session('status'))
                <x-flash-handler />
            @endif

            {{-- Ticket submission form --}}
            <form
                action="{{ route('submit-ticket') }}"
                method="POST"
                enctype="multipart/form-data"
                class="fauna-panel relative block p-5 sm:p-8 md:p-12 space-y-8 overflow-hidden"
                x-data="{
                    processing: false,
                    subject: '{{ old('subject', '') }}',
                    category_id: '{{ old('category_id', '') }}',
                    priority: '{{ old('priority', 'low') }}',
                    phone: '{{ old('phone_number', $user->phone_number ?? '') }}'.replace(/^\+234/, ''),
                    previews: [],
                    attachedFiles: [],
                    submitted: false,
                    ticketRef: '',
                    submitError: '',
                    copiedRef: false,
                    previewLightboxSrc: '',
                    previewLightboxOpen: false,
                    openPreview(url) {
                        this.previewLightboxSrc = url;
                        this.previewLightboxOpen = true;
                    },
                    handleFiles(e) {
                        const newFiles = Array.from(e.target.files);
                        this.attachedFiles = [...this.attachedFiles, ...newFiles];
                        this.previews = [...this.previews, ...newFiles.map(f => ({
                            url: URL.createObjectURL(f),
                            name: f.name,
                            isImage: f.type.startsWith('image/')
                        }))];
                        this.syncInput();
                    },
                    removePreview(idx) {
                        URL.revokeObjectURL(this.previews[idx].url);
                        this.attachedFiles.splice(idx, 1);
                        this.previews.splice(idx, 1);
                        this.syncInput();
                    },
                    syncInput() {
                        const dt = new DataTransfer();
                        this.attachedFiles.forEach(f => dt.items.add(f));
                        document.getElementById('file-upload').files = dt.files;
                    },
                    async submitTicket() {
                        if (this.processing) return;
                        this.processing = true;
                        this.submitError = '';
                        try {
                            const form = new FormData(this.$el);
                            const r = await fetch(this.$el.action, {
                                method: 'POST',
                                body: form,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await r.json();
                            if (r.ok && data.success) {
                                this.ticketRef = data.hashid;
                                this.submitted = true;
                            } else {
                                const msgs = data.errors
                                    ? Object.values(data.errors).flat().join(' ')
                                    : (data.message || 'Submission failed. Please try again.');
                                this.submitError = msgs;
                            }
                        } catch (err) {
                            this.submitError = 'An error occurred. Please check your connection.';
                            console.error(err);
                        } finally {
                            this.processing = false;
                        }
                    }
                }"
                @submit.prevent="submitTicket()"
            >
                @csrf
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-rose-400 to-transparent opacity-40"></div>

                {{-- ✅ Success state --}}
                <div x-show="submitted" x-cloak class="py-12 flex flex-col items-center text-center space-y-6">
                    <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center ring-4 ring-emerald-200 dark:ring-emerald-800/40">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Ticket Submitted!</h2>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Your support request has been received.</p>
                    </div>
                    <div class="px-6 py-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 space-y-1">
                        <div class="text-[10px] font-black tracking-widest text-rose-950 dark:text-rose-400 uppercase">Reference Code</div>
                        <div class="flex items-center justify-center gap-2">
                            <div class="text-2xl font-black tracking-widest text-rose-950 dark:text-white" x-text="ticketRef"></div>
                            <button type="button" @click="
                                (navigator.clipboard?.writeText(ticketRef) ?? Promise.reject())
                                .catch(() => {
                                    const ta = Object.assign(document.createElement('textarea'), { value: ticketRef, style: 'position:fixed;left:-9999px' });
                                    document.body.appendChild(ta);
                                    ta.select();
                                    document.execCommand('copy');
                                    ta.remove();
                                });
                                copiedRef = true;
                                setTimeout(() => copiedRef = false, 2000);
                            " class="p-2 rounded-lg bg-rose-200 dark:bg-rose-800/50 text-rose-700 dark:text-rose-300 hover:bg-rose-300 dark:hover:bg-rose-700 transition-all" title="Copy Reference Code">
                                <svg x-show="!copiedRef" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg x-show="copiedRef" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-xs text-slate-400">Bookmark the ticket page to track updates</div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full max-w-sm">
                        <a :href="'/ticket/' + ticketRef"
                            class="fauna-btn-primary flex-1 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Ticket
                        </a>
                        <button type="button"
                            @click="submitted = false; ticketRef = ''; submitError = ''; processing = false; phone = ''; subject = ''; content = ''; previews = []; attachedFiles = []; copiedRef = false;"
                            class="flex-1 px-5 py-3 rounded-2xl border border-rose-950/20 dark:border-[#1e3a5f] text-slate-600 dark:text-slate-400 font-black text-sm hover:bg-slate-50 dark:hover:bg-[#1e293b] transition-all">
                            Submit Another
                        </button>
                    </div>
                </div>

                {{-- Form fields --}}
                <div x-show="!submitted" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Name Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:col-span-2">
                        {{-- First Name --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="first_name">
                                First Name *
                            </label>
                            <input
                                id="first_name"
                                name="first_name"
                                type="text"
                                value="{{ old('first_name', $user->first_name ?? '') }}"
                                class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium shadow-sm disabled:opacity-50 disabled:bg-slate-100 disabled:dark:bg-[#0f172a] @error('first_name') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                                placeholder="First name"
                                disabled
                            />
                            @error('first_name')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Middle Name --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="middle_name">
                                Middle Name
                            </label>
                            <input
                                id="middle_name"
                                name="middle_name"
                                type="text"
                                value="{{ old('middle_name', $user->middle_name ?? '') }}"
                                class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium shadow-sm disabled:opacity-50 disabled:bg-slate-100 disabled:dark:bg-[#0f172a] @error('middle_name') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                                placeholder="Middle name"
                                disabled
                            />
                            @error('middle_name')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="last_name">
                                Last Name *
                            </label>
                            <input
                                id="last_name"
                                name="last_name"
                                type="text"
                                value="{{ old('last_name', $user->last_name ?? '') }}"
                                class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium shadow-sm disabled:opacity-50 disabled:bg-slate-100 disabled:dark:bg-[#0f172a] @error('last_name') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                                placeholder="Last name"
                                disabled
                            />
                            @error('last_name')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="email">
                            Email Address *
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email ?? '') }}"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium shadow-sm disabled:opacity-50 disabled:bg-slate-100 disabled:dark:bg-[#0f172a] @error('email') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                            placeholder="email@example.com"
                            disabled
                        />
                        @error('email')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="phone">
                            Phone Contact
                        </label>
                        <div class="flex rounded-2xl overflow-hidden border shadow-sm @if($user->phone_number) opacity-50 bg-slate-100 dark:bg-[#0f172a] pointer-events-none @else bg-white dark:bg-[#1e293b] @endif transition-all @error('phone_number') border-rose-500 dark:border-rose-500 @else border-slate-200 dark:border-[#1e3a5f] @enderror">
                            <span class="flex items-center px-4 @if($user->phone_number) bg-slate-100/50 dark:bg-[#0f172a]/50 @else bg-slate-50 dark:bg-[#1e293b]/50 @endif text-slate-600 dark:text-slate-400 font-bold text-sm border-r border-slate-200 dark:border-[#1e3a5f] select-none shrink-0">
                                +234
                            </span>
                            <input
                                id="phone"
                                type="tel"
                                x-model="phone"
                                maxlength="10"
                                @input="phone = phone.replace(/\D/g, '').slice(0, 10)"
                                class="flex-1 px-5 py-4 border-0 focus:ring-0 bg-transparent dark:bg-transparent text-slate-900 dark:text-white outline-none font-medium"
                                placeholder="8012345678"
                                @if($user->phone_number) disabled @endif
                            />
                        </div>
                        <input type="hidden" name="phone_number" :value="phone ? '+234' + phone : ''">
                        <div class="flex items-center justify-between mt-1">
                            <div>
                                @error('phone_number')
                                    <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div x-show="phone.length > 0" x-cloak class="text-xs font-bold tabular-nums"
                                :class="phone.length === 10 ? 'text-amber-500' : 'text-slate-400'">
                                <span x-text="phone.length"></span>/10
                            </div>
                        </div>
                    </div>

                    {{-- Priority --}}
                    <div class="space-y-3 md:col-span-2">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1">
                            Priority *
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            @php
                                $priorityOptions = [
                                    [
                                        'value' => 'low',
                                        'label' => 'Low',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />',
                                        'active' => 'bg-rose-50 dark:bg-rose-950/20 border-rose-400 text-rose-600 dark:text-rose-400',
                                    ],
                                    [
                                        'value' => 'medium',
                                        'label' => 'Medium',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14" />',
                                        'active' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-400 text-amber-600 dark:text-amber-400',
                                    ],
                                    [
                                        'value' => 'high',
                                        'label' => 'High',
                                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />',
                                        'active' => 'bg-red-50 dark:bg-red-900/20 border-red-400 text-red-600 dark:text-red-400',
                                    ],
                                ];
                                $inactiveClasses = 'bg-white dark:bg-[#1e293b] border-slate-200 dark:border-[#1e3a5f] text-slate-400';
                            @endphp
                            @foreach($priorityOptions as $opt)
                                <button
                                    type="button"
                                    x-bind:disabled="processing"
                                    @click="priority = '{{ $opt['value'] }}'"
                                    :class="priority === '{{ $opt['value'] }}' ? '{{ $opt['active'] }}' : '{{ $inactiveClasses }}'"
                                    class="flex flex-col items-center justify-center gap-2 py-4 rounded-2xl border-2 font-bold text-xs tracking-widest transition-all shadow-sm"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $opt['icon'] !!}
                                    </svg>
                                    {{ $opt['label'] }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="priority" x-model="priority">
                        @error('priority')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Subject / Category --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="subject">
                        Support Category *
                    </label>
                    <select
                        id="subject"
                        name="subject"
                        x-model="subject"
                        @change="
                            const opt = $event.target.selectedOptions[0];
                            category_id = opt.dataset.categoryId || '';
                        "
                        class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-bold shadow-sm @error('subject') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                        required
                    >
                        <option value="" disabled>
                            {{ empty($categories) ? 'No categories available at the moment' : 'Select Department / Topic' }}
                        </option>
                        @foreach($categories as $cat)
                            @php
                                $catSlug = $cat['slug'] ?? $cat->slug;
                                $catName = $cat['name'] ?? $cat->name;
                                $catId   = $cat['id'] ?? $cat->id;
                                $label   =  $catName;
                            @endphp
                            <option
                                value="{{ $catSlug }}"
                                data-category-id="{{ $catId }}"
                                @selected(old('subject') === $catSlug)
                                class="text-slate-900 dark:text-white font-medium"
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="category_id" x-model="category_id">

                    @if(empty($categories))
                        <div class="text-amber-500 text-xs mt-1 font-semibold">
                            Support categories are currently unavailable. You cannot submit a ticket right now.
                        </div>
                    @endif
                    @error('subject')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="content">
                        Support Specification *
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none resize-none font-medium shadow-sm @error('content') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                        placeholder="Describe the problem or inquiry with as much detail as possible..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image Upload --}}
                <div class="space-y-4">
                    <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1 block">
                        Attachments
                    </label>
                    <div class="relative group/upload">
                        <input
                            type="file"
                            name="attachments[]"
                            @change="handleFiles($event)"
                            class="hidden"
                            id="file-upload"
                            accept="image/*,.txt,text/plain,.xls,.xlsx,.pdf,.doc,.docx,application/vnd.ms-excel,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            multiple
                        />
                        <label
                            for="file-upload"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-rose-950/20 dark:border-[#1e3a5f] rounded-3xl p-10 hover:border-rose-950 dark:hover:border-rose-400 hover:bg-rose-400/5 transition-all cursor-pointer group"
                        >
                            <div class="w-12 h-12 mb-4 rounded-2xl bg-slate-100 dark:bg-[#1e293b] flex items-center justify-center text-slate-400 group-hover:bg-rose-950 group-hover:text-white transition-all shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <span class="text-slate-900 dark:text-white font-bold text-sm">
                                Upload Attachments
                            </span>
                            <span class="text-slate-600 text-xs mt-1">
                                Any image format and/or document file up to 5MB each. Drag and drop supported.
                            </span>
                        </label>
                    </div>

                    {{-- Previews --}}
                    <div x-show="previews.length > 0" x-cloak class="mt-6">
                        <div class="text-[10px] font-black text-rose-950 dark:text-rose-400 mb-4 tracking-[0.2em]">
                            Ready for Ticket (<span x-text="previews.length"></span>)
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <template x-for="(file, idx) in previews" :key="idx">
                                <div class="relative group/preview">
                                    <template x-if="file.isImage">
                                        <button type="button" @click="openPreview(file.url)"
                                            class="relative block w-28 h-28 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-2xl cursor-zoom-in focus:outline-none">
                                            <img :src="file.url" class="w-full h-full object-cover transition-transform group-hover/preview:scale-110" :alt="file.name" />
                                            <span class="absolute bottom-0 inset-x-0 text-center text-[7px] font-black tracking-wider text-white bg-black/40 py-0.5">PHOTO</span>
                                        </button>
                                    </template>
                                    <template x-if="!file.isImage">
                                        <div class="w-28 h-28 rounded-2xl border-2 border-white dark:border-[#1e3a5f] shadow-2xl bg-slate-100 dark:bg-[#1e293b] flex flex-col items-center justify-center gap-1 p-2">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-[9px] font-bold text-slate-500 text-center w-full truncate" x-text="file.name.split('.').pop().toUpperCase()"></span>
                                        </div>
                                    </template>
                                    <button
                                        type="button"
                                        @click="removePreview(idx)"
                                        class="absolute -top-3 -right-3 bg-red-500 text-white p-2 rounded-xl shadow-xl hover:bg-red-600 transition-all opacity-0 group-hover/preview:opacity-100 scale-75 group-hover/preview:scale-100"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    @error('attachments')
                        <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-6">
                    <div x-show="submitError" x-cloak class="mb-4 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 text-red-600 dark:text-red-400 text-sm font-semibold" x-text="submitError"></div>
                    <button
                        type="submit"
                        x-bind:disabled="processing || {{ empty($categories) ? 'true' : 'false' }}"
                        class="fauna-btn-primary group w-full !py-5 !text-xl disabled:opacity-50 disabled:hover:translate-y-0 flex items-center justify-center gap-2"
                    >
                        <template x-if="processing">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Submitting Ticket...' : 'Submit Ticket'"></span>
                        <template x-if="!processing">
                            <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </template>
                    </button>
                </div>
                </div>{{-- /x-show="!submitted" --}}

                {{-- Pre-upload image preview lightbox --}}
                <template x-if="previewLightboxOpen">
                    <div
                        @click.self="previewLightboxOpen = false"
                        @keydown.escape.window="previewLightboxOpen = false"
                        class="fixed inset-0 z-[300] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                    >
                        <div class="relative max-w-4xl w-full max-h-[90vh] flex items-center justify-center">
                            <button
                                type="button"
                                @click="previewLightboxOpen = false"
                                class="absolute -top-4 -right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-all backdrop-blur-sm border border-white/20"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <img :src="previewLightboxSrc" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl" />
                        </div>
                    </div>
                </template>
            </form>
        </div>
    </div>
</x-app-layout>
