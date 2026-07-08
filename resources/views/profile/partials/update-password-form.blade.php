<section x-data="{
    processing: false,
    recentlySuccessful: {{ session('status') === 'password-updated' ? 'true' : 'false' }}
}">
    <header class="mb-6">
        <h2 class="text-base font-black tracking-[0.2em] text-slate-800 dark:text-white">
            Security Credentials
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    {{-- Password update form --}}
    <form method="POST" action="{{ route('password.update') }}" class="space-y-5" @submit="processing = true">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" value="Current Password" />
            <x-text-input
                id="current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="New Password" />
            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button x-bind:disabled="processing">
                <span x-text="processing ? 'Saving...' : 'Save'"></span>
            </x-primary-button>

            <p
                x-show="recentlySuccessful"
                x-transition:enter="transition ease-in-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:leave="transition ease-in-out duration-300"
                x-transition:leave-end="opacity-0"
                class="flex items-center gap-1.5 text-sm font-semibold text-blue-600 dark:text-sky-400"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Password updated.
            </p>
        </div>
    </form>
</section>
