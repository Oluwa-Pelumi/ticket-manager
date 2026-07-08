@extends('layouts.authenticated')

@section('title', 'Ticket #' . $ticket->hashid)

@section('header')
<div class="flex items-center justify-between w-full">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-900 flex items-center justify-center shadow-lg border border-white/20">
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
        @if($ticket->status === 'open') bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-sky-400 border border-blue-200 dark:border-blue-800
        @elseif($ticket->status === 'in-progress') bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800
        @else bg-slate-100 text-slate-600 dark:bg-[#1e293b] dark:text-slate-400 border border-blue-900/10 dark:border-[#1e3a5f]
        @endif">
        {{ str_replace('-', ' ', $ticket->status) }}
    </span>
</div>
@endsection

@section('content-body')
<div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 space-y-6 sm:space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ auth()->guest() ? route('check-status') : route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-600 hover:text-blue-900 dark:hover:text-sky-400 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            {{ auth()->guest() ? 'Back to Status Search' : 'Back to Dashboard' }}
        </a>
    </div>

    @php
        $isTicketOwner = (auth()->id() === $ticket->user_id) || !$ticket->user_id;
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $isSupport = auth()->check() && auth()->user()->role === 'support';
        $isCurrentAttendant = $isSupport && auth()->id() === $ticket->attendant?->id;
        $isPastAttendant = $isSupport && !$isCurrentAttendant && in_array(auth()->id(), $ticket->attended_to_by ?? []);
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Left column: ticket details and attachments --}}
        <div class="space-y-8">
            <div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                    <svg class="w-5 h-5 mr-3 text-blue-900 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Specifications
                </h4>

                <div class="fauna-panel p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-400 to-transparent opacity-40"></div>

                    <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">Creator Information</div>
                    <div class="mt-2 mb-6">
                        @if($ticket->name || $ticket->user?->name)
                            <div class="space-y-1.5">
                                <div
                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                    <svg class="w-3 h-3 text-blue-600 dark:text-sky-400 shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>

                                    <span>{{ $ticket->name ?? $ticket->user?->name }}</span>
                                </div>
                            </div>
                        @endif

                        @if($ticket->email || $ticket->user?->email)
                            <div class="space-y-1.5">
                                <div
                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                    <svg class="w-3 h-3 text-blue-600 dark:text-sky-400 shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>

                                    <span>{{ $ticket->email ?? $ticket->user?->email }}</span>
                                </div>
                            </div>
                        @endif

                        @if($ticket->whatsapp_number || $ticket->user?->whatsapp_number)
                            <div class="space-y-1.5">
                                <div
                                    class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                    <svg class="w-3 h-3 text-blue-600 dark:text-sky-400 shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-1.687.845a11.042 11.042 0 005.516 5.516l.845-1.687a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>

                                    <span>{{ $ticket->whatsapp_number ?? $ticket->user?->whatsapp_number }}</span>
                                </div>
                            </div>
                        @endif
                    </div>


                    <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">Reference</div>
                    <div class="flex items-center gap-3 mb-8 group/id">
                        <div class="text-xl md:text-2xl text-slate-900 dark:text-white font-black tracking-tight break-all">{{ $ticket->hashid }}</div>
                        <button type="button" onclick="copyToClipboard('{{ $ticket->hashid }}', this)"
                            class="flex items-center gap-2 px-2 md:px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-[#1e293b] text-slate-600 hover:text-blue-900 dark:hover:text-sky-400 transition-all border border-transparent hover:border-blue-900/20"
                            title="Copy Reference">
                            <svg class="w-4 h-4 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                    </div>

                    <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">Subject</div>
                    <div class="text-lg md:text-xl text-slate-900 dark:text-white font-bold mb-6">
                        {{ $ticket->category->name ?? str_replace('_', ' ', $ticket->subject) }}
                    </div>

                    <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">Priority</div>
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] md:text-xs font-black tracking-wider
                            @if($ticket->priority === 'high') bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400
                            @elseif($ticket->priority === 'medium') bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400
                            @else bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-sky-400
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

                    <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.2em] uppercase">Description</div>
                    <div class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-[13px] md:text-sm mb-8">{{ $ticket->content }}</div>

                    @if($ticket->order_type)
                    <div class="pt-8 border-t border-slate-100 dark:border-[#1e3a5f]/50">
                        <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.3em] uppercase">Order Information</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">Frequency</div>
                                <div class="text-sm text-slate-900 dark:text-white capitalize">
                                    {{ str_replace('-', ' ', $ticket->order_type) }}
                                    @if(in_array($ticket->order_type, ['recurrent', 'recurring']))
                                        - 
                                        @if($ticket->recurrence_period === 'custom')
                                            Custom: {{ $ticket->custom_recurrence_date }}
                                        @else
                                            {{ str_replace('-', ' ', $ticket->recurrence_period) }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($ticket->order_type)
                        <div class="mt-6">
                            <div
                                class="text-[10px] font-black text-slate-400 mb-2 uppercase tracking-widest">
                                Order Process History</div>
                            <div class="space-y-1.5">
                                @if($ticket->order_activations !== null)
                                    @foreach($ticket->order_activations as $date)
                                        <div
                                            class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-2">
                                            <div
                                                class="w-1 h-1 rounded-full bg-sky-400 shrink-0">
                                            </div>
                                            <svg class="w-3 h-3 text-blue-600 dark:text-sky-400 shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>

                                            <svg class="w-3 h-3 text-blue-600 dark:text-sky-400 shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($date)->format('H:i') }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-[#1e3a5f]/50">
                        <div class="text-[10px] font-black text-blue-900 dark:text-sky-400 mb-2 tracking-[0.3em] uppercase">
                            Attending Support Staff
                        </div>

                        <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">
                            Past 
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if($ticket->attendants && count($ticket->attendants) > 0)
                                @foreach($ticket->attendants as $att)
                                    @if($att->id !== $ticket->attendant?->id)
                                        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-blue-900/10 dark:border-[#28524a]">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                {{ $att->name ? Str::upper(Str::substr($att->name, 0, 1)) : '?' }}
                                            </div>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $att->name }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-xs italic text-slate-400">No past support staff.</span>
                            @endif
                        </div>

                        <div class="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest mt-4">
                            Current
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if($ticket->attendant)
                                <div class="flex items-center space-x-2 bg-slate-100 dark:bg-[#1e293b] px-3 py-1.5 rounded-xl border border-blue-900/10 dark:border-[#28524a]">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                        {{ $ticket->attendant->name ? Str::upper(Str::substr($ticket->attendant->name, 0, 1)) : '?' }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $ticket->attendant->name }}</span>
                                </div>
                            @else
                                <span class="text-xs italic text-slate-400">No current support staff assigned yet.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(($ticket->images && count($ticket->images) > 0) || $ticket->filename)
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Attachments
                </h4>
                <div class="flex flex-wrap gap-4">
                    @if($ticket->filename)
                    <a href="/storage/{{ $ticket->filename }}" target="_blank" class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
                        <img src="/storage/{{ $ticket->filename }}" class="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                    </a>
                    @endif
                    @if($ticket->images)
                        @foreach($ticket->images as $img)
                        <a href="/storage/{{ $img }}" target="_blank" class="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1e3a5f] shadow-md">
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
                    <svg class="w-5 h-5 mr-3 text-blue-900 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Conversation
                </h4>

                <div class="fauna-panel mb-6 p-4 md:p-6 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar relative overflow-hidden space-y-4">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-sky-400 to-transparent opacity-40"></div>

                    @if($ticket->comments && $ticket->comments->count() > 0)
                        @foreach($ticket->comments as $comment)
                        @php
                            $isOwnerSide = $comment->user_id === $ticket->user_id || (!$comment->user_id && !$ticket->user_id);
                        @endphp
                        <div class="flex flex-col {{ $isOwnerSide ? 'items-end' : 'items-start' }}">
                            <div class="max-w-[90%] md:max-w-[85%] p-4 md:p-6 rounded-[2rem] {{ $isOwnerSide ? 'bg-blue-900 text-white rounded-br-sm shadow-xl' : 'bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white rounded-bl-sm border border-blue-900/10 dark:border-[#1e3a5f] shadow-sm' }}">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-[9px] md:text-[10px] font-black opacity-70">{{ $comment->user->name ?? 'Guest' }}</span>
                                    @if($comment->user && ($comment->user->role === 'support' || $comment->user->role === 'admin'))
                                        <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider rounded {{ $isOwnerSide ? 'bg-white/20 text-white' : 'bg-blue-500/20 text-blue-800 dark:bg-sky-400/20 dark:text-sky-400' }}">Support</span>
                                    @endif
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

                @if(($isTicketOwner || $isAdmin || $isSupport) && $ticket->status !== 'closed')
                    @if($isPastAttendant)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-sm font-medium text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50 mb-4">
                            This ticket is currently assigned to {{ $ticket->attendant->name ?? 'another support' }}. You are viewing it as a past attendant and cannot reply.
                        </div>
                    @endif

                <form method="POST" action="{{ route('add-comment', ['ticket' => $ticket->id]) }}"
                    enctype="multipart/form-data"
                    class="space-y-4 {{ $isPastAttendant ? 'opacity-60 pointer-events-none' : '' }}"
                    x-data="commentForm({{ $isPastAttendant ? 'true' : 'false' }})"
                    @submit="processing = true">
                    @csrf

                    <div class="space-y-3">
                        <div class="relative group/comment">
                            <textarea name="content" x-model="content" placeholder="Type your message..." rows="4" required {{ $isPastAttendant ? 'disabled' : '' }}
                                class="w-full px-6 py-5 rounded-[2.5rem] bg-white dark:bg-[#0f172a] border border-blue-900/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none transition-all resize-none shadow-xl text-sm md:text-base disabled:bg-slate-50 disabled:dark:bg-[#1e293b] disabled:cursor-not-allowed"></textarea>
                        </div>

                        {{-- Image previews --}}
                        <template x-if="previews.length > 0">
                            <div class="flex flex-wrap gap-2 p-3 rounded-xl bg-blue-50/50 dark:bg-[#1e293b]/50 border border-blue-900/10 dark:border-[#1e3a5f]">
                                <template x-for="(url, i) in previews" :key="i">
                                    <div class="relative group/prev">
                                        <img :src="url" class="w-16 h-16 rounded-xl object-cover border-2 border-white dark:border-[#1e3a5f] shadow-sm" />
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

                        <div class="flex items-center justify-between gap-3">
                            {{-- Attach images --}}
                            <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 dark:bg-[#1e293b] border border-blue-900/10 dark:border-[#1e3a5f] text-slate-500 dark:text-slate-400 hover:text-blue-900 dark:hover:text-sky-400 cursor-pointer transition-all text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Image</span>

                                <input
                                    type="file"
                                    name="images[]"
                                    id="comment-images"
                                    x-ref="fileInput"
                                    multiple
                                    accept="image/*"
                                    @change="handleFiles($event)"
                                    class="hidden"
                                    {{ $isPastAttendant ? 'disabled' : '' }}
                                />
                            </label>

                            {{-- Submit --}}
                            <button type="submit" x-bind:disabled="processing || isPastAttendant || !content.trim()"
                                class="flex items-center gap-2 px-5 py-2 rounded-xl bg-blue-900 text-white text-xs font-black tracking-widest shadow-md hover:bg-[#3b82f6] hover:text-[#1e3a8a] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-900 disabled:hover:text-white disabled:active:scale-100">
                                <template x-if="!processing">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </template>

                                <template x-if="processing">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                <span x-text="processing ? 'Sending…' : 'Send'"></span>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($ticket->status === 'closed')
                <div class="p-4 bg-slate-100 dark:bg-[#1e293b]/50 rounded-2xl text-center text-sm font-medium text-slate-600 border border-blue-900/10 dark:border-[#1e3a5f]">
                    This ticket is closed. No further comments can be added.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>

function commentForm(isPastAttendant) {
    return {
        isPastAttendant,
        processing: false,
        content: '',
        files: [],
        previews: [],

        handleFiles(e) {
            const newFiles = Array.from(e.target.files);
            this.files = [...this.files, ...newFiles];
            this.previews = [...this.previews, ...newFiles.map(file => URL.createObjectURL(file))];
            this.syncInput();
        },

        removeImage(index) {
            URL.revokeObjectURL(this.previews[index]);
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            const dataTransfer = new DataTransfer();
            this.files.forEach(file => dataTransfer.items.add(file));
            this.$refs.fileInput.files = dataTransfer.files;
        },
    };
}

function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}
</script>
@endsection
