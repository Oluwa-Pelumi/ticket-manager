<<<<<<< HEAD
﻿<x-guest-layout>
    <x-slot name="title">Recover Password</x-slot>
=======
@extends('layouts.guest')
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)

@section('guest-content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Recover Password</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">We'll send you a recovery link</p>
</div>

<div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
</div>

@if(session('status'))
<div class="mb-6 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-6" x-data="{ processing: false }" @submit="processing = true">
    @csrf
    <div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="your@email.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-2">
        <button type="submit" x-bind:disabled="processing" class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-[#10b981] hover:text-[#064e3b] transition-all tracking-widest disabled:opacity-50 flex items-center justify-center gap-2">
            <template x-if="processing">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </template>
            <span x-text="processing ? 'Requesting Recovery...' : 'Request Recovery'">Request Recovery</span>
        </button>
    </div>

<<<<<<< HEAD
    @if (session('status'))
        <div class="mb-6 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-red-500/20">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" x-data="{ processing: false }"
        @submit="processing = true">
        @csrf
        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('email') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="your@email.com">
            @error('email')
                <p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit" x-bind:disabled="processing"
                class="fauna-btn-primary w-full !py-4 text-lg disabled:opacity-50 flex items-center justify-center gap-2">
                <template x-if="processing">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                </template>
                <span x-text="processing ? 'Requesting Recovery...' : 'Request Recovery'">Request Recovery</span>
            </button>
        </div>

        <div class="text-center pt-4">
            <a href="{{ route('login') }}"
                class="text-sm font-black tracking-widest text-slate-600 hover:text-emerald-950 dark:hover:text-emerald-400 transition-colors">
                Return to Access
            </a>
        </div>
    </form>
</x-guest-layout>
=======
    <div class="text-center pt-4">
        <a href="{{ route('login') }}" class="text-sm font-black tracking-widest text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-colors">
            Return to Access
        </a>
    </div>
</form>
@endsection
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
