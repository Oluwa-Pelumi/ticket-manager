@extends('layouts.authenticated')

@section('header')
<div class="flex items-center justify-between w-full">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </div>
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Ticket</h2>
            <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Details</span>
        </div>
    </div>
    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest shadow-sm
        @if($ticket->status === 'open') bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800
        @elseif($ticket->status === 'in-progress') bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800
        @else bg-slate-100 text-slate-600 dark:bg-[#18342f] dark:text-slate-400 border border-emerald-900/10 dark:border-[#1d3a34]
        @endif">
        {{ str_replace('-', ' ', $ticket->status) }}
    </span>
</div>
@endsection

@section('content-body')
<div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 space-y-6 sm:space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ auth()->guest() ? route('check-status') : route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            {{ auth()->guest() ? 'Back to Status Search' : 'Back to Dashboard' }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Left column: ticket details and attachments --}}
        <div class="space-y-8">
            <div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                    <svg class="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Specifications
                </h4>

                <div class="fauna-panel p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40"></div>

                    <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">Ticket Reference</div>
                    <div class="flex items-center gap-3 mb-8 group/id">
                        <div class="text-xl md:text-2xl text-slate-900 dark:text-white font-black tracking-tight break-all">{{ $ticket->hashid }}</div>
                        <button type="button" onclick="copyReference('{{ $ticket->hashid }}', this)"
                            class="flex items-center gap-2 px-2 md:px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-[#18342f] text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20"
                            title="Copy Reference">
                            <svg class="w-4 h-4 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                    </div>

                    <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">Subject</div>
                    <div class="text-lg md:text-xl text-slate-900 dark:text-white font-bold mb-6">
                        {{ $ticket->category->name ?? str_replace('_', ' ', $ticket->subject) }}
                    </div>

                    <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">Priority</div>
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] md:text-xs font-black tracking-wider
                            @if($ticket->priority === 'high') bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400
                            @elseif($ticket->priority === 'medium') bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400
                            @else bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400
                            @endif">
                            @if($ticket->priority === 'high')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            @elseif($ticket->priority === 'medium')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14"/></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            @endif
                            {{ $ticket->priority }}
                        </span>
                    </div>

                    <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em] uppercase">Description</div>
                    <div class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-[13px] md:text-sm mb-8">{{ $ticket->content }}</div>

                    @if($ticket->order_type)
                    <div class="pt-8 border-t border-slate-100 dark:border-[#1d3a34]/50">
                        <h4 class="text-xs font-black text-teal-900 dark:text-lime-400 mb-4 tracking-[0.2em] uppercase">Order Configuration</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">Frequency</div>
                                <div class="text-sm font-bold text-slate-900 dark:text-white capitalize">{{ str_replace('-', ' ', $ticket->order_type) }}</div>
                            </div>
                            @if($ticket->order_type === 'recurrent')
                            <div>
                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">Interval / Period</div>
                                <div class="text-sm font-bold text-slate-900 dark:text-white capitalize">
                                    @if($ticket->recurrence_period === 'custom')
                                        Custom: {{ $ticket->custom_recurrence_date }}
                                    @else
                                        {{ str_replace('-', ' ', $ticket->recurrence_period) }}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if(($ticket->images && count($ticket->images) > 0) || $ticket->filename)
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center">
                    <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Attachments
                </h4>
                <div class="flex flex-wrap gap-4">
                    @if($ticket->filename)
                    <a href="/storage/{{ $ticket->filename }}" target="_blank" class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
                        <img src="/storage/{{ $ticket->filename }}" class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                    </a>
                    @endif
                    @if($ticket->images)
                        @foreach($ticket->images as $img)
                        <a href="/storage/{{ $img }}" target="_blank" class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
                            <img src="/storage/{{ $img }}" class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </a>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Right column: conversation --}}
        <div class="space-y-8">
            <div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                    <svg class="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Conversation
                </h4>

                <div class="fauna-panel mb-6 p-4 md:p-6 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40"></div>

                    @if($ticket->comments && $ticket->comments->count() > 0)
                        @foreach($ticket->comments as $comment)
                        @php
                            $isOwnerSide = $comment->user_id === $ticket->user_id || (!$comment->user_id && !$ticket->user_id);
                        @endphp
                        <div class="flex flex-col {{ $isOwnerSide ? 'items-end' : 'items-start' }}">
                            <div class="max-w-[90%] md:max-w-[85%] p-4 md:p-6 rounded-[2rem] {{ $isOwnerSide ? 'bg-teal-900 text-white rounded-br-sm shadow-xl' : 'bg-white dark:bg-[#18342f] text-slate-900 dark:text-white rounded-bl-sm border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm' }}">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-[9px] md:text-[10px] font-black opacity-70">{{ $comment->user->name ?? 'Guest' }}</span>
                                    <span class="text-[9px] md:text-[10px] opacity-50">{{ $comment->created_at->format('H:i') }}</span>
                                </div>
                                <div class="text-[13px] md:text-sm whitespace-pre-wrap">{{ $comment->content }}</div>
                                @if($comment->images && count($comment->images) > 0)
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($comment->images as $cimg)
                                    <a href="/storage/{{ $cimg }}" target="_blank" class="w-12 h-12 md:w-16 md:h-16 rounded-xl overflow-hidden border border-white/20">
                                        <img src="/storage/{{ $cimg }}" class="w-full h-full object-cover" />
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-2 opacity-40">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <div class="italic text-sm">No comments yet. Start the conversation.</div>
                    </div>
                    @endif
                </div>

                @php
                    $isTicketOwner = (auth()->id() === $ticket->user_id) || !$ticket->user_id;
                    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
                    $isSupport = auth()->check() && auth()->user()->role === 'support';
                @endphp

                @if(($isTicketOwner || $isAdmin || $isSupport) && $ticket->status !== 'closed')
                <form method="POST" action="{{ route('add-comment', ['ticket' => $ticket->id]) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="relative group/comment">
                        <textarea name="content" placeholder="Type your message..." rows="4" required
                            class="w-full px-6 py-5 rounded-[2.5rem] bg-white dark:bg-[#102824] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all resize-none shadow-xl text-sm md:text-base"></textarea>

                        <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                            <input type="file" id="comment-images" name="images[]" class="hidden" multiple accept="image/*" onchange="previewCommentImages(event)">
                            <label for="comment-images" class="p-3 text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 hover:bg-lime-500/10 rounded-2xl cursor-pointer transition-all bg-emerald-50/50 dark:bg-[#18342f]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </label>
                            <button type="submit" class="p-3 bg-teal-900 text-white rounded-2xl shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-110 active:scale-95 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </div>

                    <div id="comment-preview" class="flex flex-wrap gap-3 hidden"></div>
                </form>
                @endif

                @if($ticket->status === 'closed')
                <div class="p-4 bg-slate-100 dark:bg-[#18342f]/50 rounded-2xl text-center text-sm font-medium text-slate-600 border border-emerald-900/10 dark:border-[#1d3a34]">
                    This ticket is closed. No further comments can be added.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyReference(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        btn.querySelector('.copy-icon').outerHTML = '<svg class="w-4 h-4 text-emerald-500 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        setTimeout(() => {
            btn.querySelector('.copy-icon').outerHTML = '<svg class="w-4 h-4 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>';
        }, 2000);
    });
}

function previewCommentImages(e) {
    const container = document.getElementById('comment-preview');
    container.innerHTML = '';
    const files = Array.from(e.target.files);
    if (files.length === 0) { container.classList.add('hidden'); return; }
    container.classList.remove('hidden');
    files.forEach(file => {
        const url = URL.createObjectURL(file);
        const div = document.createElement('div');
        div.className = 'relative';
        div.innerHTML = `<img src="${url}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white dark:border-[#1d3a34] shadow-md" />`;
        container.appendChild(div);
    });
}

function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}
</script>
@endsection