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
            <x-input-label for="name" value="Name" />
            <x-text-input
                id="name"
                name="name"
                class="mt-1 block w-full"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
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

        <div x-data="{
            countryCode: '{{ old('whatsapp_country_code', $user->whatsapp_number ? (preg_match('/^(\+\d+)(\d{7,})$/', $user->whatsapp_number, $m) ? $m[1] : '+234') : '+234') }}',
            localNumber: '{{ old('whatsapp_local', $user->whatsapp_number ? (preg_match('/^(\+\d+)(\d{7,})$/', $user->whatsapp_number, $m) ? $m[2] : ltrim($user->whatsapp_number, '+')) : '') }}',
            countryCodes: [
                { code: '+1', name: 'US/CA +1' },{ code: '+7', name: 'RU +7' },{ code: '+20', name: 'EG +20' },
                { code: '+27', name: 'ZA +27' },{ code: '+30', name: 'GR +30' },{ code: '+31', name: 'NL +31' },
                { code: '+32', name: 'BE +32' },{ code: '+33', name: 'FR +33' },{ code: '+34', name: 'ES +34' },
                { code: '+36', name: 'HU +36' },{ code: '+39', name: 'IT +39' },{ code: '+40', name: 'RO +40' },
                { code: '+41', name: 'CH +41' },{ code: '+43', name: 'AT +43' },{ code: '+44', name: 'GB +44' },
                { code: '+45', name: 'DK +45' },{ code: '+46', name: 'SE +46' },{ code: '+47', name: 'NO +47' },
                { code: '+48', name: 'PL +48' },{ code: '+49', name: 'DE +49' },{ code: '+51', name: 'PE +51' },
                { code: '+52', name: 'MX +52' },{ code: '+55', name: 'BR +55' },{ code: '+56', name: 'CL +56' },
                { code: '+57', name: 'CO +57' },{ code: '+58', name: 'VE +58' },{ code: '+60', name: 'MY +60' },
                { code: '+61', name: 'AU +61' },{ code: '+62', name: 'ID +62' },{ code: '+63', name: 'PH +63' },
                { code: '+64', name: 'NZ +64' },{ code: '+65', name: 'SG +65' },{ code: '+66', name: 'TH +66' },
                { code: '+81', name: 'JP +81' },{ code: '+82', name: 'KR +82' },{ code: '+84', name: 'VN +84' },
                { code: '+86', name: 'CN +86' },{ code: '+90', name: 'TR +90' },{ code: '+91', name: 'IN +91' },
                { code: '+92', name: 'PK +92' },{ code: '+93', name: 'AF +93' },{ code: '+94', name: 'LK +94' },
                { code: '+98', name: 'IR +98' },{ code: '+212', name: 'MA +212' },{ code: '+213', name: 'DZ +213' },
                { code: '+216', name: 'TN +216' },{ code: '+218', name: 'LY +218' },{ code: '+220', name: 'GM +220' },
                { code: '+221', name: 'SN +221' },{ code: '+223', name: 'ML +223' },{ code: '+224', name: 'GN +224' },
                { code: '+225', name: 'CI +225' },{ code: '+226', name: 'BF +226' },{ code: '+227', name: 'NE +227' },
                { code: '+228', name: 'TG +228' },{ code: '+229', name: 'BJ +229' },{ code: '+230', name: 'MU +230' },
                { code: '+231', name: 'LR +231' },{ code: '+232', name: 'SL +232' },{ code: '+233', name: 'GH +233' },
                { code: '+234', name: 'NG +234' },{ code: '+235', name: 'TD +235' },{ code: '+236', name: 'CF +236' },
                { code: '+237', name: 'CM +237' },{ code: '+238', name: 'CV +238' },{ code: '+239', name: 'ST +239' },
                { code: '+240', name: 'GQ +240' },{ code: '+241', name: 'GA +241' },{ code: '+242', name: 'CG +242' },
                { code: '+243', name: 'CD +243' },{ code: '+244', name: 'AO +244' },{ code: '+245', name: 'GW +245' },
                { code: '+248', name: 'SC +248' },{ code: '+249', name: 'SD +249' },{ code: '+250', name: 'RW +250' },
                { code: '+251', name: 'ET +251' },{ code: '+252', name: 'SO +252' },{ code: '+253', name: 'DJ +253' },
                { code: '+254', name: 'KE +254' },{ code: '+255', name: 'TZ +255' },{ code: '+256', name: 'UG +256' },
                { code: '+257', name: 'BI +257' },{ code: '+258', name: 'MZ +258' },{ code: '+260', name: 'ZM +260' },
                { code: '+261', name: 'MG +261' },{ code: '+263', name: 'ZW +263' },{ code: '+264', name: 'NA +264' },
                { code: '+265', name: 'MW +265' },{ code: '+266', name: 'LS +266' },{ code: '+267', name: 'BW +267' },
                { code: '+268', name: 'SZ +268' },{ code: '+971', name: 'AE +971' },{ code: '+972', name: 'IL +972' },
                { code: '+966', name: 'SA +966' },{ code: '+967', name: 'YE +967' },{ code: '+968', name: 'OM +968' },
                { code: '+974', name: 'QA +974' },{ code: '+965', name: 'KW +965' },{ code: '+973', name: 'BH +973' },
            ]
        }">
            <x-input-label for="whatsapp_local" value="WhatsApp Number" />
            <div class="mt-1 flex rounded-xl overflow-hidden border border-emerald-900/10 dark:border-[#1d3a34] focus-within:ring-2 focus-within:ring-lime-500 transition-all shadow-sm">
                <select x-model="countryCode"
                    class="shrink-0 px-3 py-2.5 bg-slate-100 dark:bg-[#0f2420] text-slate-600 dark:text-slate-400 font-bold text-sm border-0 outline-none border-r border-emerald-900/10 dark:border-[#1d3a34] cursor-pointer"
                    style="appearance:none; background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-repeat:no-repeat; background-position:right 6px center; background-size:14px; padding-right:2rem;">
                    <template x-for="c in countryCodes" :key="c.code">
                        <option :value="c.code" x-text="c.name" :selected="c.code === countryCode"></option>
                    </template>
                </select>
                <input id="whatsapp_local" type="tel" x-model="localNumber"
                    class="flex-1 px-4 py-2.5 bg-white dark:bg-[#18342f] text-slate-900 dark:text-white outline-none border-0 font-medium min-w-0"
                    placeholder="8012345678" autocomplete="tel-national" />
            </div>
            {{-- Hidden field sends the combined value --}}
            <input type="hidden" name="whatsapp_number" :value="localNumber ? countryCode + localNumber : ''">
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
        </div>

        @if ($mustVerifyEmail && $user->email_verified_at === null)
            <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 p-4">
                <p class="text-sm text-amber-800 dark:text-amber-300">
                    Your email address is unverified.
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold underline hover:text-teal-900 dark:hover:text-lime-400 transition-colors focus:outline-none">
                            Click here to re-send the verification email.
                        </button>
                    </form>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400">
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
                class="flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Saved successfully.
            </p>
        </div>
    </form>
</section>
