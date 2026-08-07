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
            <x-input-label for="middle_name" value="Middle Name (Optional)" />
            <x-text-input
                id="middle_name"
                name="middle_name"
                class="mt-1 block w-full"
                value="{{ old('middle_name', $user->middle_name) }}"
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

        {{-- Phone Number with country code --}}
        <div x-data="{
            countryCode: '{{ preg_match('/^(\+\d+)(\d+)$/', old('phone_number', $user->phone_number ?? ''), $m) ? $m[1] : '+234' }}',
            localPhone: '{{ preg_match('/^(\+\d+)(\d+)$/', old('phone_number', $user->phone_number ?? ''), $m) ? $m[2] : '' }}',
            countryCodes: [
                { code: '+1',   name: 'US/CA +1' }, { code: '+7',   name: 'RU +7' },
                { code: '+20',  name: 'EG +20' },   { code: '+27',  name: 'ZA +27' },
                { code: '+33',  name: 'FR +33' },   { code: '+34',  name: 'ES +34' },
                { code: '+39',  name: 'IT +39' },   { code: '+44',  name: 'GB +44' },
                { code: '+49',  name: 'DE +49' },   { code: '+55',  name: 'BR +55' },
                { code: '+61',  name: 'AU +61' },   { code: '+62',  name: 'ID +62' },
                { code: '+81',  name: 'JP +81' },   { code: '+86',  name: 'CN +86' },
                { code: '+91',  name: 'IN +91' },   { code: '+92',  name: 'PK +92' },
                { code: '+212', name: 'MA +212' },  { code: '+213', name: 'DZ +213' },
                { code: '+220', name: 'GM +220' },  { code: '+221', name: 'SN +221' },
                { code: '+223', name: 'ML +223' },  { code: '+224', name: 'GN +224' },
                { code: '+225', name: 'CI +225' },  { code: '+233', name: 'GH +233' },
                { code: '+234', name: 'NG +234' },  { code: '+237', name: 'CM +237' },
                { code: '+250', name: 'RW +250' },  { code: '+251', name: 'ET +251' },
                { code: '+254', name: 'KE +254' },  { code: '+255', name: 'TZ +255' },
                { code: '+256', name: 'UG +256' },  { code: '+260', name: 'ZM +260' },
                { code: '+263', name: 'ZW +263' },  { code: '+966', name: 'SA +966' },
                { code: '+971', name: 'AE +971' },
            ]
        }">
            <x-input-label for="phone_local" value="Phone Number (Optional)" />
            <div class="mt-1 flex rounded-xl overflow-hidden border border-slate-200 dark:border-[#1e3a5f] focus-within:ring-2 focus-within:ring-slate-400 transition-all shadow-sm">
                <select x-model="countryCode"
                    class="shrink-0 px-3 py-2.5 bg-slate-100 dark:bg-[#1e293b]/50 text-slate-600 dark:text-slate-400 font-bold text-sm border-0 border-r border-slate-200 dark:border-[#1e3a5f] outline-none cursor-pointer"
                    style="appearance:none; background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-repeat:no-repeat; background-position:right 6px center; background-size:14px; padding-right:2rem;">
                    <template x-for="c in countryCodes" :key="c.code + c.name">
                        <option :value="c.code" x-text="c.name" :selected="c.code === countryCode"></option>
                    </template>
                </select>
                <input
                    id="phone_local"
                    type="tel"
                    x-model="localPhone"
                    @input="localPhone = localPhone.replace(/\D/g, '')"
                    class="flex-1 px-4 py-2.5 bg-white dark:bg-[#1e293b] text-slate-900 dark:text-white outline-none border-0 font-medium min-w-0"
                    placeholder="8012345678"
                    autocomplete="tel-national"
                />
            </div>
            <input type="hidden" name="phone_number" :value="localPhone ? countryCode + localPhone : ''">
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        @if ($mustVerifyEmail && $user->email_verified_at === null)
            <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 p-4">
                <p class="text-sm text-amber-800 dark:text-amber-300">
                    Your email address is unverified.
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold underline hover:text-rose-950 dark:hover:text-rose-400 transition-colors focus:outline-none">
                            Click here to re-send the verification email.
                        </button>
                    </form>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-400">
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
                class="flex items-center gap-1.5 text-sm font-semibold text-green-600 dark:text-green-400"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Saved successfully.
            </p>
        </div>
    </form>
</section>
