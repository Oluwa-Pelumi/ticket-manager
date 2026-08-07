{{--
    Comment form partial — included inside an Alpine `commentForm(ticketId)` scope.
    State available: content, files, previews, submitting, handleImages(), removeImage(), submit()
--}}
<div class="space-y-3  min-w-0 max-w-full w-full">

    {{-- Text area --}}
    <div class="relative">
        <textarea x-model="content" @keydown.ctrl.enter.prevent="submit()" rows="3"
            placeholder="Write a comment… (Ctrl+Enter to send)"
            class="w-full px-4 py-3 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-lime-500 focus:border-transparent outline-none transition-all resize-none shadow-sm"></textarea>
    </div>

    {{-- Attachment previews --}}
    <template x-if="previews.length > 0">
        <div
            class="flex flex-wrap gap-2 p-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
            <template x-for="(file, i) in previews" :key="i">
                <div class="relative group/prev">
                    <template x-if="file.isImage">
                        {{-- Image: show thumbnail, click to preview --}}
                        <button type="button" @click="openPreview(file.url)"
                            class="relative block w-16 h-16 rounded-xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-sm cursor-zoom-in focus:outline-none bg-slate-100 dark:bg-[#18342f]">
                            <img :src="file.url"
                                class="w-full h-full object-cover transition-transform group-hover/prev:scale-110" />
                            <span
                                class="absolute bottom-0 inset-x-0 text-center text-[6px] font-black tracking-wider text-white bg-black/40 py-0.5">PHOTO</span>
                        </button>
                    </template>
                    <template x-if="!file.isImage">
                        {{-- Document: show file icon + extension --}}
                        <div
                            class="w-16 h-16 rounded-xl border-2 border-white dark:border-[#1d3a34] shadow-sm bg-slate-100 dark:bg-[#18342f] flex flex-col items-center justify-center gap-1 px-1">
                            <svg class="w-6 h-6 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-[8px] font-bold text-slate-500 truncate w-full text-center"
                                x-text="file.name.split('.').pop().toUpperCase()"></span>
                        </div>
                    </template>
                    <button type="button" @click="removeAttachment(i)"
                        class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 text-white rounded-full opacity-0 group-hover/prev:opacity-100 transition-opacity shadow-md">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </template>

    {{-- Actions row --}}
    <div class="flex flex-wrap items-center justify-between gap-3">

        {{-- Attach attachments --}}
        <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-[#18342f] border border-rose-950/10 dark:border-[#1d3a34] text-slate-500 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 cursor-pointer transition-all text-xs font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
            <span>Attachment</span>
            <input type="file" name="attachments[]" class="hidden" multiple
                   accept="image/*,.txt,text/plain,.xls,.xlsx,.pdf,.doc,.docx,application/vnd.ms-excel,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                   @change="handleAttachments($event)" />
        </label>

        {{-- Submit --}}
        <button type="button" @click="submit()" x-bind:disabled="submitting || !content.trim()"
            class="flex items-center gap-2 px-5 py-2 rounded-xl bg-teal-900 text-white text-xs font-black tracking-widest shadow-md hover:bg-[#10b981] hover:text-[#064e3b] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-teal-900 disabled:hover:text-white disabled:active:scale-100">
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
</div>
