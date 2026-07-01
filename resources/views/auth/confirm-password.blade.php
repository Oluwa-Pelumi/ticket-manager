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
                :disabled="processing"
                id="password"
                type="password"
                name="password"
                class="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none"
                autofocus
                placeholder="••••••••"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button
                type="submit"
                :disabled="processing"
                class="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest"
            >
                <span x-text="processing ? 'Validating Identity...' : 'Validate Identity'"></span>
            </button>
        </div>
    </form>
</x-guest-layout>
