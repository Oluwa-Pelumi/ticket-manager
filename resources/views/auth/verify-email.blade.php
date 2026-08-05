<x-guest-layout>
    <x-slot name="title">Email Verification</x-slot>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Verify Email</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Almost there! Check your inbox</p>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        Thanks for signing up! Before getting started, could you verify
        your email address by clicking on the link we just emailed to
        you? If you didn't receive the email, we will gladly send you
        another.
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-6 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
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
                class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-[#10b981] hover:text-[#064e3b] hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest flex items-center justify-center gap-2"
            >
                <template x-if="processing">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </template>
                <span x-text="processing ? 'Re-verifying Identity...' : 'Re-verify Identity'"></span>
            </button>
        </div>

<<<<<<< HEAD
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
=======
        <div class="text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-black tracking-widest text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-colors">
                    Terminate Session
                </button>
            </form>
        </div>
    </form>
</x-guest-layout>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
