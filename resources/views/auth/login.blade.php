@extends('layouts.guest')

@section('guest-content')
<div class="mb-10 text-center">
    <h1 class="text-4xl font-medium text-slate-900 dark:text-white">Login</h1>
    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">Use your support account credentials to continue.</p>
</div>

@if(session('status'))
<div class="mb-6 text-sm font-bold text-blue-500 bg-blue-500/10 p-4 rounded-2xl border border-blue-500/20 backdrop-blur-md">
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ processing: false }" @submit="processing = true">
    @csrf

    <div class="space-y-2">
        <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300 pl-4">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
            class="w-full rounded-full border-blue-900/20 px-4 py-3 shadow focus:border-sky-400 focus:ring-sky-400 dark:border-[#1e3a5f] dark:bg-[#1e293b] dark:text-white"
            placeholder="your@email.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <label for="password" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300 pl-3">Password</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium underline hover:text-sky-500 transition-colors">Forgot password?</a>
            @endif
        </div>
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="w-full rounded-full border-blue-900/20 px-4 py-3 shadow focus:border-sky-400 focus:ring-sky-400 dark:border-[#1e3a5f] dark:bg-[#1e293b] dark:text-white"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <label for="remember" class="flex items-center px-1 cursor-pointer select-none group" x-data="{ checked: false }">
        <input id="remember" type="checkbox" name="remember" class="hidden" x-model="checked">
        <span 
            class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
            :class="checked ? 'bg-[#1e3a8a] border-[#1e3a8a]' : 'border-slate-300 dark:border-[#1e3a5f] bg-white dark:bg-[#1e293b]'"
        >
            <svg x-show="checked" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </span>
        <span class="ms-3 text-[11px] font-bold tracking-widest text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Remember me</span>
    </label>

    <div class="pt-2">
        <button type="submit" x-bind:disabled="processing" class="fauna-btn-primary w-full !py-3.5 text-lg disabled:opacity-50 flex items-center justify-center gap-2">
            <template x-if="processing">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </template>
            <span x-text="processing ? 'Signing In...' : 'Sign In'">Sign In</span>
        </button>
    </div>

    <div class="text-center pt-4">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-bold text-blue-900 dark:text-sky-400 hover:underline">Register</a>
        </p>
    </div>
</form>
@endsection