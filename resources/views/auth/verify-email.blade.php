<x-guest-layout>
    <x-slot name="title">Email Verification</x-slot>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Verify Your Email</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Please verify your email address to access your dashboard.</p>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        Thanks for signing up! Before accessing your dashboard, please verify
        your email address by clicking on the link we just emailed to
        you. If you didn't receive the email, click the button below to request another.
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-6 text-sm font-medium text-sky-500 bg-sky-500/10 p-3 rounded-xl border border-sky-500/20">
            A new verification link has been sent to the email address
            you provided during registration.
        </div>
    @endif

    {{-- Resend verification form --}}
    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6" x-data="{ processing: false }" @submit="processing = true">
        @csrf

        <div class="pt-2">
            <button
                type="submit"
                x-bind:disabled="processing"
                class="fauna-btn-primary w-full !py-4 text-lg disabled:opacity-50 flex items-center justify-center gap-2"
            >
                <template x-if="processing">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </template>
                <span x-text="processing ? 'Sending Email...' : 'Resend Verification Email'"></span>
            </button>
        </div>

        <div class="text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-black tracking-widest text-slate-600 hover:text-sky-950 dark:hover:text-sky-400 transition-colors">
                    Terminate Session
                </button>
            </form>
        </div>
    </form>
</x-guest-layout>
