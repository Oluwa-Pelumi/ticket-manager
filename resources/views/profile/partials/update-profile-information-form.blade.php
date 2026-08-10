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
            countryCode: '{{ old('whatsapp_country_code', $user->whatsapp_number ? (preg_match('/^(\+\d+?)(\d{7,10})$/', $user->whatsapp_number, $m) ? $m[1] : '+234') : '+234') }}',
            localNumber: '{{ old('whatsapp_local', $user->whatsapp_number ? (preg_match('/^(\+\d+?)(\d{7,10})$/', $user->whatsapp_number, $m) ? $m[2] : ltrim($user->whatsapp_number, '+')) : '') }}',
            countryCodes: [
                { code: '+93', name: 'AF +93' }, { code: '+355', name: 'AL +355' }, { code: '+213', name: 'DZ +213' },
                { code: '+376', name: 'AD +376' }, { code: '+244', name: 'AO +244' }, { code: '+54', name: 'AR +54' },
                { code: '+374', name: 'AM +374' }, { code: '+61', name: 'AU +61' }, { code: '+43', name: 'AT +43' },
                { code: '+994', name: 'AZ +994' }, { code: '+1242', name: 'BS +1242' }, { code: '+973', name: 'BH +973' },
                { code: '+880', name: 'BD +880' }, { code: '+375', name: 'BY +375' }, { code: '+32', name: 'BE +32' },
                { code: '+501', name: 'BZ +501' }, { code: '+229', name: 'BJ +229' }, { code: '+975', name: 'BT +975' },
                { code: '+591', name: 'BO +591' }, { code: '+387', name: 'BA +387' }, { code: '+267', name: 'BW +267' },
                { code: '+55', name: 'BR +55' }, { code: '+673', name: 'BN +673' }, { code: '+359', name: 'BG +359' },
                { code: '+226', name: 'BF +226' }, { code: '+257', name: 'BI +257' }, { code: '+855', name: 'KH +855' },
                { code: '+237', name: 'CM +237' }, { code: '+1', name: 'CA +1' }, { code: '+238', name: 'CV +238' },
                { code: '+236', name: 'CF +236' }, { code: '+235', name: 'TD +235' }, { code: '+56', name: 'CL +56' },
                { code: '+86', name: 'CN +86' }, { code: '+57', name: 'CO +57' }, { code: '+269', name: 'KM +269' },
                { code: '+243', name: 'CD +243' }, { code: '+242', name: 'CG +242' }, { code: '+506', name: 'CR +506' },
                { code: '+385', name: 'HR +385' }, { code: '+53', name: 'CU +53' }, { code: '+357', name: 'CY +357' },
                { code: '+420', name: 'CZ +420' }, { code: '+45', name: 'DK +45' }, { code: '+253', name: 'DJ +253' },
                { code: '+1767', name: 'DM +1767' }, { code: '+1809', name: 'DO +1809' }, { code: '+593', name: 'EC +593' },
                { code: '+20', name: 'EG +20' }, { code: '+503', name: 'SV +503' }, { code: '+240', name: 'GQ +240' },
                { code: '+291', name: 'ER +291' }, { code: '+372', name: 'EE +372' }, { code: '+251', name: 'ET +251' },
                { code: '+679', name: 'FJ +679' }, { code: '+358', name: 'FI +358' }, { code: '+33', name: 'FR +33' },
                { code: '+241', name: 'GA +241' }, { code: '+220', name: 'GM +220' }, { code: '+995', name: 'GE +995' },
                { code: '+49', name: 'DE +49' }, { code: '+233', name: 'GH +233' }, { code: '+30', name: 'GR +30' },
                { code: '+1473', name: 'GD +1473' }, { code: '+502', name: 'GT +502' }, { code: '+224', name: 'GN +224' },
                { code: '+245', name: 'GW +245' }, { code: '+592', name: 'GY +592' }, { code: '+509', name: 'HT +509' },
                { code: '+504', name: 'HN +504' }, { code: '+36', name: 'HU +36' }, { code: '+354', name: 'IS +354' },
                { code: '+91', name: 'IN +91' }, { code: '+62', name: 'ID +62' }, { code: '+98', name: 'IR +98' },
                { code: '+964', name: 'IQ +964' }, { code: '+353', name: 'IE +353' }, { code: '+972', name: 'IL +972' },
                { code: '+39', name: 'IT +39' }, { code: '+1876', name: 'JM +1876' }, { code: '+81', name: 'JP +81' },
                { code: '+962', name: 'JO +962' }, { code: '+7', name: 'KZ +7' }, { code: '+254', name: 'KE +254' },
                { code: '+686', name: 'KI +686' }, { code: '+850', name: 'KP +850' }, { code: '+82', name: 'KR +82' },
                { code: '+965', name: 'KW +965' }, { code: '+996', name: 'KG +996' }, { code: '+856', name: 'LA +856' },
                { code: '+371', name: 'LV +371' }, { code: '+961', name: 'LB +961' }, { code: '+266', name: 'LS +266' },
                { code: '+231', name: 'LR +231' }, { code: '+218', name: 'LY +218' }, { code: '+423', name: 'LI +423' },
                { code: '+370', name: 'LT +370' }, { code: '+352', name: 'LU +352' }, { code: '+261', name: 'MG +261' },
                { code: '+265', name: 'MW +265' }, { code: '+60', name: 'MY +60' }, { code: '+960', name: 'MV +960' },
                { code: '+223', name: 'ML +223' }, { code: '+356', name: 'MT +356' }, { code: '+692', name: 'MH +692' },
                { code: '+222', name: 'MR +222' }, { code: '+230', name: 'MU +230' }, { code: '+52', name: 'MX +52' },
                { code: '+691', name: 'FM +691' }, { code: '+373', name: 'MD +373' }, { code: '+377', name: 'MC +377' },
                { code: '+976', name: 'MN +976' }, { code: '+382', name: 'ME +382' }, { code: '+212', name: 'MA +212' },
                { code: '+258', name: 'MZ +258' }, { code: '+95', name: 'MM +95' }, { code: '+264', name: 'NA +264' },
                { code: '+674', name: 'NR +674' }, { code: '+977', name: 'NP +977' }, { code: '+31', name: 'NL +31' },
                { code: '+64', name: 'NZ +64' }, { code: '+505', name: 'NI +505' }, { code: '+227', name: 'NE +227' },
                { code: '+234', name: 'NG +234' }, { code: '+389', name: 'MK +389' }, { code: '+47', name: 'NO +47' },
                { code: '+968', name: 'OM +968' }, { code: '+92', name: 'PK +92' }, { code: '+680', name: 'PW +680' },
                { code: '+507', name: 'PA +507' }, { code: '+675', name: 'PG +675' }, { code: '+595', name: 'PY +595' },
                { code: '+51', name: 'PE +51' }, { code: '+63', name: 'PH +63' }, { code: '+48', name: 'PL +48' },
                { code: '+351', name: 'PT +351' }, { code: '+974', name: 'QA +974' }, { code: '+40', name: 'RO +40' },
                { code: '+7', name: 'RU +7' }, { code: '+250', name: 'RW +250' }, { code: '+1869', name: 'KN +1869' },
                { code: '+1758', name: 'LC +1758' }, { code: '+1784', name: 'VC +1784' }, { code: '+685', name: 'WS +685' },
                { code: '+378', name: 'SM +378' }, { code: '+239', name: 'ST +239' }, { code: '+966', name: 'SA +966' },
                { code: '+221', name: 'SN +221' }, { code: '+381', name: 'RS +381' }, { code: '+232', name: 'SL +232' },
                { code: '+65', name: 'SG +65' }, { code: '+421', name: 'SK +421' }, { code: '+386', name: 'SI +386' },
                { code: '+677', name: 'SB +677' }, { code: '+252', name: 'SO +252' }, { code: '+27', name: 'ZA +27' },
                { code: '+211', name: 'SS +211' }, { code: '+34', name: 'ES +34' }, { code: '+94', name: 'LK +94' },
                { code: '+249', name: 'SD +249' }, { code: '+597', name: 'SR +597' }, { code: '+268', name: 'SZ +268' },
                { code: '+46', name: 'SE +46' }, { code: '+41', name: 'CH +41' }, { code: '+963', name: 'SY +963' },
                { code: '+886', name: 'TW +886' }, { code: '+992', name: 'TJ +992' }, { code: '+255', name: 'TZ +255' },
                { code: '+66', name: 'TH +66' }, { code: '+670', name: 'TL +670' }, { code: '+228', name: 'TG +228' },
                { code: '+676', name: 'TO +676' }, { code: '+1868', name: 'TT +1868' }, { code: '+216', name: 'TN +216' },
                { code: '+90', name: 'TR +90' }, { code: '+993', name: 'TM +993' }, { code: '+688', name: 'TV +688' },
                { code: '+256', name: 'UG +256' }, { code: '+380', name: 'UA +380' }, { code: '+971', name: 'AE +971' },
                { code: '+44', name: 'GB +44' }, { code: '+1', name: 'US +1' }, { code: '+598', name: 'UY +598' },
                { code: '+998', name: 'UZ +998' }, { code: '+678', name: 'VU +678' }, { code: '+58', name: 'VE +58' },
                { code: '+84', name: 'VN +84' }, { code: '+967', name: 'YE +967' }, { code: '+260', name: 'ZM +260' },
                { code: '+263', name: 'ZW +263' },
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
                    placeholder="8012345678" maxlength="10" autocomplete="tel-national" />
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
