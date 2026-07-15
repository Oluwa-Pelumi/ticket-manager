<section x-data="{
    processing: false,
    recentlySuccessful: {{ session('status') === 'profile-updated' ? 'true' : 'false' }}
}">
    <header class="mb-6">
        <h2 class="text-base font-black tracking-[0.2em] text-slate-800 dark:text-white">
            General Information
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            Update your account's profile information and email address.
        </p>
    </header>

    {{-- Profile information form --}}
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5" @submit="processing = true">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="first_name" value="First Name" />
            <x-text-input
                id="first_name"
                name="first_name"
                class="mt-1 block w-full"
                value="{{ old('first_name', $user->first_name) }}"
                required
                autofocus
                autocomplete="first_name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="middle_name" value="Middle Name" />
            <x-text-input
                id="middle_name"
                name="middle_name"
                class="mt-1 block w-full"
                value="{{ old('middle_name', $user->middle_name) }}"
                required
                autofocus
                autocomplete="middle_name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
        </div>

        <div>
            <x-input-label for="last_name" value="Last Name" />
            <x-text-input
                id="last_name"
                name="last_name"
                class="mt-1 block w-full"
                value="{{ old('last_name', $user->last_name) }}"
                required
                autofocus
                autocomplete="last_name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        @if (auth()->user()->role === 'user')
        <div>
            <x-input-label for="matric_no" value="Matriculation Number" />
            <x-text-input
                id="matric_no"
                name="matric_no"
                type="text"
                class="mt-1 block w-full"
                value="{{ old('matric_no', $user->matric_no) }}"
                required
                autocomplete="matric_no"
            />
            <x-input-error class="mt-2" :messages="$errors->get('matric_no')" />
        </div>
        @endif

        @if ($mustVerifyEmail && $user->email_verified_at === null)
            <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 p-4">
                <p class="text-sm text-amber-800 dark:text-amber-300">
                    Your email address is unverified.
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold underline hover:text-sky-950 dark:hover:text-sky-400 transition-colors focus:outline-none">
                            Click here to re-send the verification email.
                        </button>
                    </form>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-2 text-sm font-medium text-sky-600 dark:text-sky-400">
                        A new verification link has been sent to your email address.
                    </div>
                @endif
            </div>
        @endif

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
                class="flex items-center gap-1.5 text-sm font-semibold text-sky-600 dark:text-sky-400"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Saved successfully.
            </p>
        </div>
    </form>
</section>
