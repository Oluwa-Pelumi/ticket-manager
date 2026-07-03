<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase">
                    FAQ <span class="text-teal-900 dark:text-lime-400">Management</span>
                </h2>
                <p class="mt-2 text-slate-600 dark:text-slate-400 font-medium tracking-wide">
                    Configure and maintain frequently asked questions for the platform.
                </p>
            </div>
            <div class="flex justify-end shrink-0">
                <button x-data @click="$dispatch('open-faq-modal', { faq: null })"
                    class="inline-flex items-center gap-3 px-6 py-3 bg-teal-900 dark:bg-lime-500 text-white dark:text-[#102824] rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-teal-900/20 dark:shadow-lime-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New FAQ
                </button>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">FAQ Management</x-slot>


    <div class="max-w-[98%] xl:max-w-[1700px] mx-auto py-6 px-2 sm:px-4 lg:px-6">
        <div class="space-y-6">

            {{-- FAQ list --}}
            <div class="grid grid-cols-1 gap-6">
                @forelse ($faqs as $faq)
                    <div class="fauna-panel p-8 group hover:border-lime-500/30 transition-all duration-500">
                        <div class="flex justify-between items-start gap-6">

                            {{-- Content --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <span
                                        class="px-3 py-1 rounded-full bg-teal-900/10 dark:bg-lime-500/10 text-teal-900 dark:text-lime-400 text-[10px] font-black uppercase tracking-widest border border-teal-900/20 dark:border-lime-500/20">
                                        Order: {{ $faq->order ?? 0 }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">
                                    {{ $faq->question }}
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                    {{ $faq->answer }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0">

                                {{-- Edit --}}
                                <button x-data
                                    @click="$dispatch('open-faq-modal', {
                                    faq: {
                                        id:       {{ $faq->id }},
                                        question: {{ Js::from($faq->question) }},
                                        answer:   {{ Js::from($faq->answer) }},
                                        order:    {{ $faq->order ?? 0 }}
                                    }
                                })"
                                    class="p-3 rounded-2xl bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20"
                                    title="Edit FAQ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq->id) }}" x-data
                                    @submit.prevent="
                                    $dispatch('confirm', {
                                        type:        'danger',
                                        title:       'Delete FAQ',
                                        message:     'Are you sure you want to delete this FAQ? This action cannot be undone.',
                                        confirmText: 'Delete FAQ',
                                        onConfirm:   () => $el.submit()
                                    })
                                ">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-3 rounded-2xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                        title="Delete FAQ">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="fauna-panel p-20 text-center">
                        <div
                            class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3
                            class="text-2xl font-black text-slate-900 dark:text-white mb-2 uppercase italic tracking-tighter">
                            No FAQs Found
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 max-w-sm mx-auto font-medium">
                            Start by adding frequently asked questions to help your users.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>



    {{-- ── FAQ Modal (Alpine.js) ──────────────────────────────────────────── --}}
    <div x-data="{
        open: false,
        faq: null,
        question: '',
        answer: '',
        order: 0,
        processing: false,

        init() {
            this._handler = (e) => {
                this.faq = e.detail.faq;
                this.question = this.faq ? this.faq.question : '';
                this.answer = this.faq ? this.faq.answer : '';
                this.order = this.faq ? this.faq.order : 0;
                this.open = true;
            };

            window.addEventListener('open-faq-modal', this._handler);
        },

        destroy() {
            window.removeEventListener('open-faq-modal', this._handler);
        },

        close() {
            this.open = false;
            this.faq = null;
            this.question = '';
            this.answer = '';
            this.order = 0;
            this.processing = false;
        }
    }" x-show="open" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-950/40 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="close()">
        <div class="w-full max-w-2xl fauna-panel p-6 sm:p-10 bg-white/95 dark:bg-[#102824]/95 border-lime-500/20 overflow-y-auto max-h-[90vh]"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" @click.stop>
            {{-- Modal header --}}
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter">
                    <span x-text="faq ? 'Edit' : 'Create'"></span>
                    <span class="text-teal-900 dark:text-lime-400">FAQ</span>
                </h3>
                <button @click="close()"
                    class="p-3 rounded-2xl bg-slate-100 dark:bg-[#18342f] text-slate-500 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Create form (shown when faq == null) --}}
            <form method="POST" action="{{ route('admin.faqs.store') }}" x-show="!faq" class="space-y-8" @submit="processing = true">
                @csrf

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Question</label>
                    <input type="text" name="question" x-model="question"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-bold dark:text-white"
                        placeholder="Enter the question..." />
                    @error('question')
                        <p class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Answer</label>
                    <textarea name="answer" x-model="answer"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-medium dark:text-white min-h-[150px]"
                        placeholder="Enter the answer..."></textarea>
                    @error('answer')
                        <p class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Display
                        Order</label>
                    <input type="number" name="order" x-model="order"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-black dark:text-white" />
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="close()"
                        class="flex-1 py-5 px-8 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-400 font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-sm">
                        Cancel
                    </button>
                    <button type="submit" x-bind:disabled="processing"
                        class="flex-[2] py-5 px-8 bg-teal-900 dark:bg-lime-500 text-white dark:text-[#102824] rounded-[2rem] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-teal-900/20 dark:shadow-lime-500/10 text-sm disabled:opacity-50 flex items-center justify-center gap-2">
                        <template x-if="processing">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Creating...' : 'Create FAQ'">Create FAQ</span>
                    </button>
                </div>
            </form>

            {{-- Edit form (shown when faq != null) --}}
            <form method="POST" :action="`{{ url('admin/faqs') }}/${faq ? faq.id : ''}`" x-show="faq"
                class="space-y-8" @submit="processing = true">
                @csrf
                @method('PATCH')

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Question</label>
                    <input type="text" name="question" x-model="question"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-bold dark:text-white"
                        placeholder="Enter the question..." />
                    @error('question')
                        <p class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Answer</label>
                    <textarea name="answer" x-model="answer"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-medium dark:text-white min-h-[150px]"
                        placeholder="Enter the answer..."></textarea>
                    @error('answer')
                        <p class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label
                        class="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Display
                        Order</label>
                    <input type="number" name="order" x-model="order"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-black dark:text-white" />
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="close()"
                        class="flex-1 py-5 px-8 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-400 font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-sm">
                        Cancel
                    </button>
                    <button type="submit" x-bind:disabled="processing"
                        class="flex-[2] py-5 px-8 bg-teal-900 dark:bg-lime-500 text-white dark:text-[#102824] rounded-[2rem] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-teal-900/20 dark:shadow-lime-500/10 text-sm disabled:opacity-50 flex items-center justify-center gap-2">
                        <template x-if="processing">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </template>
                        <span x-text="processing ? 'Updating...' : 'Update FAQ'">Update FAQ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
