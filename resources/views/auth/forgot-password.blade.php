@extends('layouts.guest')

@section('title', 'Recover Password')

@section('guest-content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Recover Password</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">We'll send you a recovery link</p>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link
        that will allow you to choose a new one.
    </div>

    @if (session('status'))
        <div class="mb-6 text-sm font-medium text-rose-500 bg-rose-500/10 p-3 rounded-xl border border-rose-500/20">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6" x-data="{ processing: false }"
        @submit="processing = true">
        @csrf
        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-full border-fauna-rose/20 px-4 py-3 shadow focus:border-fauna-rose focus:ring-fauna-rose dark:border-[#1e3a5f] dark:bg-[#1e293b] dark:text-white"
                placeholder="your@email.com">
            @error('email')
                <p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>
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
                class="text-sm font-black tracking-widest text-slate-600 hover:text-rose-950 dark:hover:text-rose-400 transition-colors">
                Return to Access
            </a>
        </div>
    </form>
@endsection
