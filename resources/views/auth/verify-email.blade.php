<x-guest-layout>
    <x-slot name="title">Email Verification</x-slot>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Verify Your Email</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Please verify your email address to access your dashboard.</p>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed space-y-3">
        <p>
            Thanks for signing up! Before accessing your dashboard, please verify
            your email address by clicking on the link we just emailed to:
        </p>

        <div class="p-3 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-center my-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-0.5">Registered Email Address</span>
            <span class="font-bold text-slate-900 dark:text-slate-100 text-base break-all select-all">{{ auth()->user()?->email }}</span>
        </div>

        <p>
            If you didn't receive the email, please check if there is a typo in your email address above or click the button below to request another verification link.
        </p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-6 text-sm font-medium text-green-500 bg-green-500/10 p-3 rounded-xl border border-green-500/20">
            A new verification link has been sent to the email address
            you provided during registration.
        </div>
    @endif

    {{-- Resend verification form --}}
    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6" x-data="{ resending: false }" @submit="resending = true">
        @csrf

        <div class="pt-2">
            <button
                type="submit"
                x-bind:disabled="resending"
                class="fauna-btn-primary w-full !py-4 text-lg disabled:opacity-50 flex items-center justify-center gap-2"
            >
                <template x-if="resending">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </template>
                <span x-text="resending ? 'Sending Email...' : 'Resend Verification Email'"></span>
            </button>
        </div>
    </form>

    <div class="text-center mt-6" x-data="{ signingOut: false }">
        <form method="POST" action="{{ route('logout') }}" @submit="signingOut = true">
            @csrf
            <button type="submit" x-bind:disabled="signingOut" class="inline-flex items-center justify-center gap-2 text-sm font-black tracking-widest text-slate-600 hover:text-emerald-950 dark:hover:text-emerald-400 transition-colors disabled:opacity-50">
                <template x-if="signingOut">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </template>
                <span x-text="signingOut ? 'Signing Out...' : 'Sign Out'"></span>
            </button>
        </form>
    </div>
</x-guest-layout>
