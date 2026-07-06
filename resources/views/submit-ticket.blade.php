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

    <div class="fauna-shell relative min-h-screen flex flex-col items-center overflow-x-hidden transition-colors duration-500 selection:bg-lime-500 selection:text-teal-900">

        {{-- Background Layer --}}
        <div class="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10"></div>

        <div class="relative z-10 w-full max-w-3xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14 pb-12 sm:pb-16">

            {{-- Header --}}
            <div class="fauna-panel mb-6 sm:mb-10 p-4 sm:p-6 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40"></div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
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
                <a href="{{ route('home') }}" class="inline-flex items-center text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-900 transition-colors mb-4 tracking-widest">
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
                    order_type: '{{ old('order_type', '') }}',
                    recurrence_period: '{{ old('recurrence_period', '') }}',
                    whatsapp: '{{ old('whatsapp_number', $user->whatsapp_number ?? '') }}'.replace(/^\+234/, ''),
                    previewUrls: [],
                    handleFiles(e) {
                        this.previewUrls = Array.from(e.target.files).map(f => URL.createObjectURL(f));
                    },
                    removePreview(idx, inputEl) {
                        const dt = new DataTransfer();
                        const files = Array.from(inputEl.files);
                        files.splice(idx, 1);
                        files.forEach(f => dt.items.add(f));
                        inputEl.files = dt.files;
                        this.previewUrls.splice(idx, 1);
                    }
                }"
                @submit="processing = true"
            >
                @csrf
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Name --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="name">
                            Name *
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name ?? '') }}"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                            placeholder="Enter your name"
                            required
                        />
                        @error('name')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
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
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                            placeholder="email@example.com"
                            required
                        />
                        @error('email')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="whatsapp">
                            WhatsApp Contact
                        </label>
                        <div class="flex rounded-2xl overflow-hidden border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm focus-within:ring-2 focus-within:ring-lime-500 transition-all">
                            <span class="flex items-center px-4 bg-slate-100 dark:bg-[#0f2420] text-slate-600 dark:text-slate-400 font-bold text-sm border-r border-emerald-900/10 dark:border-[#1d3a34] select-none shrink-0">
                                +234
                            </span>
                            <input
                                id="whatsapp"
                                type="tel"
                                x-model="whatsapp"
                                class="flex-1 px-5 py-4 bg-white dark:bg-[#18342f] text-slate-900 dark:text-white outline-none font-medium"
                                placeholder="8012345678"
                            />
                        </div>
                        <input type="hidden" name="whatsapp_number" :value="whatsapp ? '+234' + whatsapp : ''">
                        @error('whatsapp_number')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div class="space-y-3">
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
                                        'active' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-400 text-emerald-600 dark:text-emerald-400',
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
                                $inactiveClasses = 'bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-slate-400';
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
                            order_type = '';
                            recurrence_period = '';
                        "
                        class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold shadow-sm"
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
                                $isDisabled = !$user && $catSlug === 'order';
                                $label = $isDisabled ? "{$catName} (requires account)" : $catName;
                            @endphp
                            <option
                                value="{{ $catSlug }}"
                                data-category-id="{{ $catId }}"
                                @selected(old('subject') === $catSlug)
                                @disabled($isDisabled)
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

                {{-- Conditional Order Fields --}}
                @if($user)
                    <div x-show="subject === 'order'" x-cloak class="space-y-6 p-6 rounded-2xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
                        <div class="space-y-3">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">
                                Order Type
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($orderType as $type)
                                    <label
                                        :class="order_type === '{{ $type['id'] }}' ? 'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400' : 'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'"
                                        class="flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                                    >
                                        <input
                                            type="radio"
                                            name="order_type"
                                            value="{{ $type['id'] }}"
                                            class="hidden"
                                            x-model="order_type"
                                            @change="recurrence_period = ''"
                                        />
                                        <span class="text-xs font-black tracking-widest uppercase">
                                            {{ $type['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="order_type === 'recurrent'" x-cloak class="space-y-3">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">
                                Recurrence Period
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($recurrencePeriod as $period)
                                    <label
                                        :class="recurrence_period === '{{ $period['id'] }}' ? 'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400' : 'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'"
                                        class="flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all"
                                    >
                                        <input
                                            type="radio"
                                            name="recurrence_period"
                                            value="{{ $period['id'] }}"
                                            class="hidden"
                                            x-model="recurrence_period"
                                        />
                                        <span class="text-[10px] font-black tracking-tight uppercase text-center">
                                            {{ $period['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div x-show="recurrence_period === 'custom'" x-cloak class="pt-2">
                                <input
                                    type="date"
                                    name="custom_recurrence_date"
                                    value="{{ old('custom_recurrence_date') }}"
                                    class="w-full px-5 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold"
                                    min="{{ now()->toDateString() }}"
                                />
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Content --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" for="content">
                        Support Specification *
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none resize-none font-medium shadow-sm"
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
                            name="images[]"
                            @change="handleFiles($event)"
                            class="hidden"
                            id="image-upload"
                            accept="image/*"
                            multiple
                        />
                        <label
                            for="image-upload"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-emerald-900/20 dark:border-[#1d3a34] rounded-3xl p-10 hover:border-teal-900 dark:hover:border-lime-500 hover:bg-lime-500/5 transition-all cursor-pointer group"
                        >
                            <div class="w-12 h-12 mb-4 rounded-2xl bg-slate-100 dark:bg-[#18342f] flex items-center justify-center text-slate-400 group-hover:bg-teal-900 group-hover:text-white transition-all shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <span class="text-slate-900 dark:text-white font-bold text-sm">
                                Upload Snapshots
                            </span>
                            <span class="text-slate-600 text-xs mt-1">
                                PNG, JPG up to 5MB each. Drag and drop supported.
                            </span>
                        </label>
                    </div>

                    {{-- Previews --}}
                    <div x-show="previewUrls.length > 0" x-cloak class="mt-6">
                        <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-4 tracking-[0.2em]">
                            Ready for Ticket (<span x-text="previewUrls.length"></span>)
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <template x-for="(url, idx) in previewUrls" :key="idx">
                                <div class="relative group/preview">
                                    <img :src="url" class="w-28 h-28 object-cover rounded-2xl border-2 border-white dark:border-[#1d3a34] shadow-2xl transition-transform group-hover/preview:scale-110" :alt="'Preview ' + (idx + 1)">
                                    <button
                                        type="button"
                                        @click="removePreview(idx, document.getElementById('image-upload'))"
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
                    @error('images')
                        <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-6">
                    <button
                        type="submit"
                        x-bind:disabled="processing || {{ empty($categories) ? 'true' : 'false' }}"
                        class="group w-full py-5 rounded-[2rem] bg-teal-900 text-white font-black text-xl shadow-2xl hover:bg-[#10b981] hover:text-[#064e3b] hover:-translate-y-1 active:translate-y-0 active:shadow-none disabled:opacity-50 disabled:hover:translate-y-0 transition-all flex items-center justify-center gap-2"
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
            </form>
        </div>
    </div>


</x-app-layout>
