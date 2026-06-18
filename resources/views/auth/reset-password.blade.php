@extends('layouts.guest')

@section('guest-content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">New Password</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Almost there! Set your new password</p>
</div>

<form method="POST" action="{{ route('password.store') }}" class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="space-y-2">
        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">New Password</label>
        <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="••••••••">
        @error('password_confirmation')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-4">
        <button type="submit" class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-lime-500 hover:text-teal-900 transition-all tracking-widest">
            Update Credentials
        </button>
    </div>
</form>
@endsection