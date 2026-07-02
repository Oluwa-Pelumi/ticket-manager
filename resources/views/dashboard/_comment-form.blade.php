{{--
    Comment form partial — included inside an Alpine `commentForm(ticketId)` scope.
    State available: content, files, previews, submitting, handleImages(), removeImage(), submit()
--}}
<div class="space-y-3">

    {{-- Text area --}}
    <div class="relative">
        <textarea
            x-model="content"
            @keydown.ctrl.enter.prevent="submit()"
            rows="3"
            placeholder="Write a comment… (Ctrl+Enter to send)"
            class="w-full px-4 py-3 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-lime-500 focus:border-transparent outline-none transition-all resize-none shadow-sm"
        ></textarea>
    </div>

    {{-- Image previews --}}
    <template x-if="previews.length > 0">
        <div class="flex flex-wrap gap-2 p-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
            <template x-for="(url, i) in previews" :key="i">
                <div class="relative group/prev">
                    <img :src="url" class="w-16 h-16 rounded-xl object-cover border-2 border-white dark:border-[#1d3a34] shadow-sm" />
                    <button
                        type="button"
                        @click="removeImage(i)"
                        class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 text-white rounded-full opacity-0 group-hover/prev:opacity-100 transition-opacity shadow-md"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </template>

    {{-- Actions row --}}
    <div class="flex items-center justify-between gap-3">

        {{-- Attach images --}}
        <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-500 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 cursor-pointer transition-all text-xs font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Image</span>
            <input type="file" class="hidden" multiple accept="image/*" @change="handleImages($event)" />
        </label>

        {{-- Submit --}}
        <button
            type="button"
            @click="submit()"
            x-bind:disabled="submitting || !content.trim()"
            class="flex items-center gap-2 px-5 py-2 rounded-xl bg-teal-900 text-white text-xs font-black tracking-widest shadow-md hover:bg-[#10b981] hover:text-[#064e3b] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-teal-900 disabled:hover:text-white disabled:active:scale-100"
        >
            <template x-if="!submitting">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </template>
            <template x-if="submitting">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </template>
            <span x-text="submitting ? 'Sending…' : 'Send'"></span>
        </button>

    </div>
</div>
