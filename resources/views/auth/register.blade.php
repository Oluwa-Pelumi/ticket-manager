@extends('layouts.guest')

@section('guest-content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Join us to start managing your tickets</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ processing: false }" @submit="processing = true">
    @csrf

    <div class="space-y-2">
        <label for="name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="John Doe">
        @error('name')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="john@example.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="••••••••">
        @error('password_confirmation')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-4">
        <button type="submit" x-bind:disabled="processing" class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-lime-500 hover:text-teal-900 transition-all tracking-widest disabled:opacity-50">
            <span x-text="processing ? 'Creating Account...' : 'Create Account'">Create Account</span>
        </button>
    </div>

    <div class="text-center pt-4">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-bold text-teal-900 dark:text-lime-400 hover:underline">Sign In</a>
        </p>
    </div>
</form>
@endsection