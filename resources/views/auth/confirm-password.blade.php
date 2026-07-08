<x-guest-layout>
    <x-slot name="title">Confirm Password</x-slot>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Security Check</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Please confirm your identity</p>
    </div>

    <div class="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        This is a secure area of the application. Please confirm your
        password before continuing.
    </div>

    {{-- Password confirmation form --}}
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6" x-data="{ processing: false }" @submit="processing = true">
        @csrf

        <div class="space-y-2">
            <x-input-label for="password" value="Password" class="text-slate-700 dark:text-slate-300 font-semibold" />

            <x-text-input
                id="password"
                type="password"
                name="password"
                class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none"
                autofocus
                placeholder="••••••••"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button
                type="submit"
                x-bind:disabled="processing"
                class="w-full py-4 rounded-2xl bg-sky-950 text-white font-black text-lg shadow-xl hover:bg-sky-800 hover:text-white hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest flex items-center justify-center gap-2"
            >
                <template x-if="processing">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </template>
                <span x-text="processing ? 'Validating Identity...' : 'Validate Identity'"></span>
            </button>
        </div>
    </form>
</x-guest-layout>
