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
                class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-[#10b981] hover:text-[#064e3b] hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest"
            >
                <span x-text="processing ? 'Re-verifying Identity...' : 'Re-verify Identity'"></span>
            </button>
        </div>

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