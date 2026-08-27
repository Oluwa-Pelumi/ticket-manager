{{--
    Admin FAQ Management View
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                    FAQs
                </h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">
                    Manage Frequently Asked Questions
                </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Manage FAQs</x-slot>

    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6">

        <x-flash-handler />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- ── Create / Edit form ── --}}
            <div class="lg:col-span-1">
                <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">

                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                        {{ isset($editingFaq) ? 'Edit FAQ' : 'Create New FAQ' }}
                    </h3>

                    <form
                        method="POST"
                        action="{{ isset($editingFaq)
                            ? route('admin.faqs.update', $editingFaq->id)
                            : route('admin.faqs.store') }}"
                        class="space-y-5"
                        x-data="{ processing: false }" @submit="processing = true"
                    >
                        @csrf
                        @if (isset($editingFaq))
                            @method('PATCH')
                        @endif

                        {{-- Question --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">
                                Question
                            </label>
                            <input
                                type="text"
                                name="question"
                                value="{{ old('question', $editingFaq->question ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium"
                                placeholder="e.g. How do I track my order?"
                                required
                            />
                            @error('question')
                                <p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Answer --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">
                                Answer
                            </label>
                            <textarea
                                name="answer"
                                rows="5"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium resize-none"
                                placeholder="Enter a clear, helpful answer..."
                                required
                            >{{ old('answer', $editingFaq->answer ?? '') }}</textarea>
                            @error('answer')
                                <p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Display Order --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">
                                Display Order
                            </label>
                            <input
                                type="number"
                                name="order"
                                value="{{ old('order', isset($editingFaq) ? ($editingFaq->order ?? 1) : (count($faqs) + 1)) }}"
                                min="1"
                                max="{{ isset($editingFaq) ? max(1, count($faqs)) : (count($faqs) + 1) }}"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium"
                            />
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col gap-3 pt-1">
                            <button
                                type="submit"
                                x-bind:disabled="processing"
                                class="w-full py-4 rounded-xl bg-teal-900 text-white font-black text-sm tracking-widest shadow-lg hover:bg-[#10b981] hover:text-[#064e3b] transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                            >
                                <template x-if="processing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                <span x-text="processing ? '{{ isset($editingFaq) ? 'Updating...' : 'Creating...' }}' : '{{ isset($editingFaq) ? 'Update FAQ' : 'Create FAQ' }}'">
                                    {{ isset($editingFaq) ? 'Update FAQ' : 'Create FAQ' }}
                                </span>
                            </button>

                            @if (isset($editingFaq))
                                <a
                                    href="{{ route('admin.faqs.index') }}"
                                    class="w-full py-3 rounded-xl border border-emerald-900/10 dark:border-[#1d3a34] text-slate-600 dark:text-slate-400 font-black text-[10px] tracking-widest hover:bg-emerald-50/50 dark:hover:bg-slate-800 transition-all text-center"
                                >
                                    Cancel Edit
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── FAQ list ── --}}
            <div class="lg:col-span-2 space-y-4">
                @forelse ($faqs as $faq)
                    <div class="fauna-panel p-6 group hover:border-lime-500/30 transition-all duration-300 @if(isset($editingFaq) && $editingFaq->id === $faq->id) ring-2 ring-lime-500/50 @endif">
                        <div class="flex justify-between items-start gap-4">

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-0.5 rounded-full bg-teal-900/10 dark:bg-lime-500/10 text-teal-900 dark:text-lime-400 text-[10px] font-black uppercase tracking-widest border border-teal-900/20 dark:border-lime-500/20 shrink-0">
                                        #{{ $faq->order ?? 0 }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2 leading-snug">
                                    {{ $faq->question }}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                    {{ $faq->answer }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.faqs.index', ['edit' => $faq->id]) }}"
                                    class="p-2 bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition-all"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.faqs.destroy', $faq->id) }}"
                                    x-data
                                    @submit.prevent="
                                        $dispatch('confirm', {
                                            type:        'danger',
                                            title:       'Delete FAQ',
                                            confirmText: 'Delete FAQ',
                                            message:     'Are you sure you want to delete this FAQ? This action cannot be undone.',
                                            onConfirm:   () => $el.submit()
                                        })
                                    "
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all"
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="fauna-panel p-16 text-center">
                        <div class="w-16 h-16 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-1">No FAQs yet</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Use the form on the left to add your first FAQ.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</x-app-layout>
