{{--
    Admin FAQ Management View
    Provides frequently asked creation creation/editing.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-700 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">FAQs</h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Manage Frequently Asked Questions</span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">FAQ Management</x-slot>

    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6"
        x-data="{
            editing: null,
            question: '',
            answer: '',
            order: 0,
            processing: false,

            startEdit(faq) {
                this.editing = faq;
                this.question = faq.question;
                this.answer = faq.answer;
                this.order = faq.order;
                this.$nextTick(() => {
                    document.getElementById('faq-form-panel').scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },

            cancelEdit() {
                this.editing = null;
                this.question = '';
                this.answer = '';
                this.order = 0;
                this.processing = false;
            }
        }">

        <x-flash-handler />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- ── Create / Edit Form ── --}}
            <div class="lg:col-span-1" id="faq-form-panel">
                <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">

                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight"
                        x-text="editing ? 'Edit FAQ' : 'Add New FAQ'">
                        Add New FAQ
                    </h3>

                    {{-- Create form --}}
                    <form method="POST" action="{{ route('admin.faqs.store') }}"
                        class="space-y-6" x-show="!editing"
                        @submit="processing = true">
                        @csrf

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Question</label>
                            <textarea name="question" rows="3"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium @error('question') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                                placeholder="e.g. How do I submit a ticket?"
                                required>{{ old('question') }}</textarea>
                            @error('question')
                                <p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Answer</label>
                            <textarea name="answer" rows="5"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border text-slate-900 dark:text-white focus:ring-2 transition-all outline-none font-medium @error('answer') border-rose-500 dark:border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                                placeholder="Enter a clear, helpful answer..."
                                required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Display Order</label>
                            <input type="number" name="order" value="{{ old('order', 0) }}"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 transition-all outline-none font-black" />
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" x-bind:disabled="processing"
                                class="fauna-btn-primary w-full !py-4 disabled:opacity-50 flex items-center justify-center gap-2">
                                <template x-if="processing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                <span x-text="processing ? 'Adding FAQ...' : 'Add FAQ'">Add FAQ</span>
                            </button>
                        </div>
                    </form>

                    {{-- Edit form --}}
                    <form method="POST" x-bind:action="`{{ url('admin/faqs') }}/${editing ? editing.id : ''}`"
                        class="space-y-6" x-show="editing" x-cloak
                        @submit="processing = true">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Question</label>
                            <textarea name="question" rows="3" x-model="question"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 transition-all outline-none font-medium"
                                required></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Answer</label>
                            <textarea name="answer" rows="5" x-model="answer"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 transition-all outline-none font-medium"
                                required></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Display Order</label>
                            <input type="number" name="order" x-model="order"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-slate-400 transition-all outline-none font-black" />
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" x-bind:disabled="processing"
                                class="fauna-btn-primary w-full !py-4 disabled:opacity-50 flex items-center justify-center gap-2">
                                <template x-if="processing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                <span x-text="processing ? 'Updating FAQ...' : 'Update FAQ'">Update FAQ</span>
                            </button>

                            <button type="button" @click="cancelEdit()"
                                class="w-full py-3 rounded-xl border border-rose-950/10 dark:border-[#1e3a5f] text-slate-600 font-black text-[10px] tracking-widest hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-all text-center">
                                Cancel Edit
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- ── FAQ List ── --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-rose-950/10 dark:border-[#1e3a5f] shadow-2xl">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-rose-950/10 dark:border-[#1e3a5f]">
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">
                                    Question &amp; Answer
                                </th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-center w-16">
                                    Order
                                </th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($faqs as $faq)
                                <tr class="hover:bg-rose-50/50 dark:hover:bg-[#1e293b]/70 transition-colors">

                                    {{-- Question + Answer preview --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white mb-1">
                                            {{ $faq->question }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 font-medium leading-relaxed">
                                            {{ $faq->answer }}
                                        </div>
                                    </td>

                                    {{-- Order badge --}}
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400">
                                            {{ $faq->order ?? 0 }}
                                        </span>
                                    </td>

                                    {{-- Edit / Delete --}}
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Edit --}}
                                            <button type="button"
                                                @click="startEdit({
                                                    id:       {{ $faq->id }},
                                                    question: {{ Js::from($faq->question) }},
                                                    answer:   {{ Js::from($faq->answer) }},
                                                    order:    {{ $faq->order ?? 0 }}
                                                })"
                                                class="p-2 rounded-lg bg-slate-100 dark:bg-[#1e293b] text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all border border-transparent hover:border-blue-600/20"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq->id) }}"
                                                x-data
                                                @submit.prevent="$dispatch('confirm', {
                                                    type:           'danger',
                                                    title:          'Delete FAQ',
                                                    message:        'Are you sure you want to delete this FAQ? This action cannot be undone.',
                                                    confirmText:    'Delete FAQ',
                                                    successMessage: 'FAQ deleted successfully.',
                                                    form:            $el
                                                })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">
                                            No FAQs yet. Add one to get started.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
