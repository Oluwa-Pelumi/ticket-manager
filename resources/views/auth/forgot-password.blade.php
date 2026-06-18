@extends('layouts.guest')

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

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf
    <div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none"
            placeholder="your@email.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-2">
        <button type="submit" class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-lime-500 hover:text-teal-900 transition-all tracking-widest">
            Request Recovery
        </button>
    </div>

    <div class="text-center pt-4">
        <a href="{{ route('login') }}" class="text-sm font-black tracking-widest text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-colors">
            Return to Access
        </a>
    </div>
</form>
@endsection