@extends('layouts.guest')

@section('guest-content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">New Password</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Almost there! Set your new password</p>
</div>

<form method="POST" action="{{ route('password.store') }}" class="space-y-5" x-data="{ processing: false }" @submit="processing = true">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="space-y-2">
        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username"
            class="w-full px-4 py-3 rounded-xl bg-blue-50/50 dark:bg-[#1e293b]/50 border border-blue-900/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">New Password</label>
        <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-blue-50/50 dark:bg-[#1e293b]/50 border border-blue-900/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-blue-50/50 dark:bg-[#1e293b]/50 border border-blue-900/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="••••••••">
        @error('password_confirmation')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-4">
        <button type="submit" x-bind:disabled="processing" class="w-full py-4 rounded-2xl bg-blue-900 text-white font-black text-lg shadow-xl hover:bg-[#3b82f6] hover:text-[#1e3a8a] transition-all tracking-widest disabled:opacity-50 flex items-center justify-center gap-2">
            <template x-if="processing">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </template>
            <span x-text="processing ? 'Updating Credentials...' : 'Update Credentials'">Update Credentials</span>
        </button>
    </div>
</form>
@endsection