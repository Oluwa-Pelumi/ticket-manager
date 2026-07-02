@extends('layouts.guest')

@section('guest-content')
<div class="mb-10 text-center">
    <h1 class="text-4xl font-medium text-slate-900 dark:text-white">Login</h1>
    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">Use your support account credentials to continue.</p>
</div>

@if(session('status'))
<div class="mb-6 text-sm font-bold text-emerald-500 bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20 backdrop-blur-md">
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ processing: false }" @submit="processing = true">
    @csrf

    <div class="space-y-2">
        <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300 pl-4">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
            class="w-full rounded-full border-emerald-900/20 px-4 py-3 shadow focus:border-lime-500 focus:ring-lime-500 dark:border-[#1d3a34] dark:bg-[#18342f] dark:text-white"
            placeholder="your@email.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <label for="password" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300 pl-3">Password</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium underline hover:text-lime-600 transition-colors">Forgot password?</a>
            @endif
        </div>
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="w-full rounded-full border-emerald-900/20 px-4 py-3 shadow focus:border-lime-500 focus:ring-lime-500 dark:border-[#1d3a34] dark:bg-[#18342f] dark:text-white"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <label for="remember" class="flex items-center px-1 cursor-pointer select-none group">
        <input id="remember" type="checkbox" name="remember" 
            class="w-5 h-5 rounded-md bg-slate-100 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-teal-900 dark:text-lime-500 focus:ring-2 focus:ring-emerald-900 dark:focus:ring-lime-500 focus:ring-offset-0 transition-all cursor-pointer">
        <span class="ms-3 text-[11px] font-bold tracking-widest text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Remember me</span>
    </label>

    <div class="pt-2">
        <button type="submit" x-bind:disabled="processing" class="fauna-btn-primary w-full !py-3.5 text-lg disabled:opacity-50">
            <span x-text="processing ? 'Signing In...' : 'Sign In'">Sign In</span>
        </button>
    </div>

    <div class="text-center pt-4">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-bold text-teal-900 dark:text-lime-400 hover:underline">Register</a>
        </p>
    </div>
</form>
@endsection