@php
    $orderType = [['id' => 'one-time', 'label' => 'One Time'], ['id' => 'recurrent', 'label' => 'Recurrent Order']];

    $recurrencePeriod = [
        ['id' => 'one-week', 'label' => 'Weekly'],
        ['id' => 'two-weeks', 'label' => 'Once every 2 weeks'],
        ['id' => 'three-weeks', 'label' => 'Once every 3 weeks'],
        ['id' => 'monthly', 'label' => 'Monthly'],
        ['id' => 'yearly', 'label' => 'Yearly'],
        ['id' => 'custom', 'label' => 'Pick Date'],
    ];

    $user = auth()->user();
@endphp

<x-app-layout>

    <x-slot name="title">Submit Ticket</x-slot>

    <div
        class="fauna-shell relative min-h-screen flex flex-col items-center overflow-x-hidden transition-colors duration-500 selection:bg-lime-500 selection:text-teal-900">

        {{-- Background Layer --}}
        <div class="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10"></div>

        <div class="relative z-10 w-full max-w-3xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14 pb-12 sm:pb-16">

            {{-- Header --}}
            <div class="fauna-panel mb-6 sm:mb-10 p-4 sm:p-6 md:p-10 relative overflow-hidden">
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40">
                </div>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            Support
                        </h1>
                        <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">
                            Submit Ticket
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center mb-10">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-900 transition-colors mb-4 tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Homepage
                </a>
            </div>

            @if (session('success') || session('error') || session('status'))
                <x-flash-handler />
            @endif

            {{-- Ticket submission form --}}
            <form action="{{ route('submit-ticket') }}" method="POST" enctype="multipart/form-data"
                class="fauna-panel relative block p-5 sm:p-8 md:p-12 space-y-8 overflow-hidden"
                x-init="$nextTick(() => { const el = document.getElementById('name') || document.getElementById('subject'); if(el) { el.focus(); } })"
                x-data="{
                    processing: false,
                    subject: '{{ old('subject', '') }}',
                    category_id: '{{ old('category_id', '') }}',
                    priority: '{{ old('priority', 'low') }}',
                    order_type: '{{ old('order_type', '') }}',
                    recurrence_period: '{{ old('recurrence_period', '') }}',
                    countryCode: '+234',
                    whatsapp: '{{ old('whatsapp_number', $user->whatsapp_number ?? '') }}'.replace(/^\+\d{1,3}/, ''),
                    countryCodes: [
                        { code: '+93', name: 'AF +93' },
                        { code: '+355', name: 'AL +355' },
                        { code: '+213', name: 'DZ +213' },
                        { code: '+376', name: 'AD +376' },
                        { code: '+244', name: 'AO +244' },
                        { code: '+54', name: 'AR +54' },
                        { code: '+374', name: 'AM +374' },
                        { code: '+61', name: 'AU +61' },
                        { code: '+43', name: 'AT +43' },
                        { code: '+994', name: 'AZ +994' },
                        { code: '+1242', name: 'BS +1242' },
                        { code: '+973', name: 'BH +973' },
                        { code: '+880', name: 'BD +880' },
                        { code: '+375', name: 'BY +375' },
                        { code: '+32', name: 'BE +32' },
                        { code: '+501', name: 'BZ +501' },
                        { code: '+229', name: 'BJ +229' },
                        { code: '+975', name: 'BT +975' },
                        { code: '+591', name: 'BO +591' },
                        { code: '+387', name: 'BA +387' },
                        { code: '+267', name: 'BW +267' },
                        { code: '+55', name: 'BR +55' },
                        { code: '+673', name: 'BN +673' },
                        { code: '+359', name: 'BG +359' },
                        { code: '+226', name: 'BF +226' },
                        { code: '+257', name: 'BI +257' },
                        { code: '+855', name: 'KH +855' },
                        { code: '+237', name: 'CM +237' },
                        { code: '+1', name: 'CA +1' },
                        { code: '+238', name: 'CV +238' },
                        { code: '+236', name: 'CF +236' },
                        { code: '+235', name: 'TD +235' },
                        { code: '+56', name: 'CL +56' },
                        { code: '+86', name: 'CN +86' },
                        { code: '+57', name: 'CO +57' },
                        { code: '+269', name: 'KM +269' },
                        { code: '+243', name: 'CD +243' },
                        { code: '+242', name: 'CG +242' },
                        { code: '+506', name: 'CR +506' },
                        { code: '+385', name: 'HR +385' },
                        { code: '+53', name: 'CU +53' },
                        { code: '+357', name: 'CY +357' },
                        { code: '+420', name: 'CZ +420' },
                        { code: '+45', name: 'DK +45' },
                        { code: '+253', name: 'DJ +253' },
                        { code: '+1767', name: 'DM +1767' },
                        { code: '+1809', name: 'DO +1809' },
                        { code: '+593', name: 'EC +593' },
                        { code: '+20', name: 'EG +20' },
                        { code: '+503', name: 'SV +503' },
                        { code: '+240', name: 'GQ +240' },
                        { code: '+291', name: 'ER +291' },
                        { code: '+372', name: 'EE +372' },
                        { code: '+251', name: 'ET +251' },
                        { code: '+679', name: 'FJ +679' },
                        { code: '+358', name: 'FI +358' },
                        { code: '+33', name: 'FR +33' },
                        { code: '+241', name: 'GA +241' },
                        { code: '+220', name: 'GM +220' },
                        { code: '+995', name: 'GE +995' },
                        { code: '+49', name: 'DE +49' },
                        { code: '+233', name: 'GH +233' },
                        { code: '+30', name: 'GR +30' },
                        { code: '+1473', name: 'GD +1473' },
                        { code: '+502', name: 'GT +502' },
                        { code: '+224', name: 'GN +224' },
                        { code: '+245', name: 'GW +245' },
                        { code: '+592', name: 'GY +592' },
                        { code: '+509', name: 'HT +509' },
                        { code: '+504', name: 'HN +504' },
                        { code: '+36', name: 'HU +36' },
                        { code: '+354', name: 'IS +354' },
                        { code: '+91', name: 'IN +91' },
                        { code: '+62', name: 'ID +62' },
                        { code: '+98', name: 'IR +98' },
                        { code: '+964', name: 'IQ +964' },
                        { code: '+353', name: 'IE +353' },
                        { code: '+972', name: 'IL +972' },
                        { code: '+39', name: 'IT +39' },
                        { code: '+1876', name: 'JM +1876' },
                        { code: '+81', name: 'JP +81' },
                        { code: '+962', name: 'JO +962' },
                        { code: '+7', name: 'KZ +7' },
                        { code: '+254', name: 'KE +254' },
                        { code: '+686', name: 'KI +686' },
                        { code: '+850', name: 'KP +850' },
                        { code: '+82', name: 'KR +82' },
                        { code: '+965', name: 'KW +965' },
                        { code: '+996', name: 'KG +996' },
                        { code: '+856', name: 'LA +856' },
                        { code: '+371', name: 'LV +371' },
                        { code: '+961', name: 'LB +961' },
                        { code: '+266', name: 'LS +266' },
                        { code: '+231', name: 'LR +231' },
                        { code: '+218', name: 'LY +218' },
                        { code: '+423', name: 'LI +423' },
                        { code: '+370', name: 'LT +370' },
                        { code: '+352', name: 'LU +352' },
                        { code: '+261', name: 'MG +261' },
                        { code: '+265', name: 'MW +265' },
                        { code: '+60', name: 'MY +60' },
                        { code: '+960', name: 'MV +960' },
                        { code: '+223', name: 'ML +223' },
                        { code: '+356', name: 'MT +356' },
                        { code: '+692', name: 'MH +692' },
                        { code: '+222', name: 'MR +222' },
                        { code: '+230', name: 'MU +230' },
                        { code: '+52', name: 'MX +52' },
                        { code: '+691', name: 'FM +691' },
                        { code: '+373', name: 'MD +373' },
                        { code: '+377', name: 'MC +377' },
                        { code: '+976', name: 'MN +976' },
                        { code: '+382', name: 'ME +382' },
                        { code: '+212', name: 'MA +212' },
                        { code: '+258', name: 'MZ +258' },
                        { code: '+95', name: 'MM +95' },
                        { code: '+264', name: 'NA +264' },
                        { code: '+674', name: 'NR +674' },
                        { code: '+977', name: 'NP +977' },
                        { code: '+31', name: 'NL +31' },
                        { code: '+64', name: 'NZ +64' },
                        { code: '+505', name: 'NI +505' },
                        { code: '+227', name: 'NE +227' },
                        { code: '+234', name: 'NG +234' },
                        { code: '+389', name: 'MK +389' },
                        { code: '+47', name: 'NO +47' },
                        { code: '+968', name: 'OM +968' },
                        { code: '+92', name: 'PK +92' },
                        { code: '+680', name: 'PW +680' },
                        { code: '+507', name: 'PA +507' },
                        { code: '+675', name: 'PG +675' },
                        { code: '+595', name: 'PY +595' },
                        { code: '+51', name: 'PE +51' },
                        { code: '+63', name: 'PH +63' },
                        { code: '+48', name: 'PL +48' },
                        { code: '+351', name: 'PT +351' },
                        { code: '+974', name: 'QA +974' },
                        { code: '+40', name: 'RO +40' },
                        { code: '+7', name: 'RU +7' },
                        { code: '+250', name: 'RW +250' },
                        { code: '+1869', name: 'KN +1869' },
                        { code: '+1758', name: 'LC +1758' },
                        { code: '+1784', name: 'VC +1784' },
                        { code: '+685', name: 'WS +685' },
                        { code: '+378', name: 'SM +378' },
                        { code: '+239', name: 'ST +239' },
                        { code: '+966', name: 'SA +966' },
                        { code: '+221', name: 'SN +221' },
                        { code: '+381', name: 'RS +381' },
                        { code: '+232', name: 'SL +232' },
                        { code: '+65', name: 'SG +65' },
                        { code: '+421', name: 'SK +421' },
                        { code: '+386', name: 'SI +386' },
                        { code: '+677', name: 'SB +677' },
                        { code: '+252', name: 'SO +252' },
                        { code: '+27', name: 'ZA +27' },
                        { code: '+211', name: 'SS +211' },
                        { code: '+34', name: 'ES +34' },
                        { code: '+94', name: 'LK +94' },
                        { code: '+249', name: 'SD +249' },
                        { code: '+597', name: 'SR +597' },
                        { code: '+268', name: 'SZ +268' },
                        { code: '+46', name: 'SE +46' },
                        { code: '+41', name: 'CH +41' },
                        { code: '+963', name: 'SY +963' },
                        { code: '+886', name: 'TW +886' },
                        { code: '+992', name: 'TJ +992' },
                        { code: '+255', name: 'TZ +255' },
                        { code: '+66', name: 'TH +66' },
                        { code: '+670', name: 'TL +670' },
                        { code: '+228', name: 'TG +228' },
                        { code: '+676', name: 'TO +676' },
                        { code: '+1868', name: 'TT +1868' },
                        { code: '+216', name: 'TN +216' },
                        { code: '+90', name: 'TR +90' },
                        { code: '+993', name: 'TM +993' },
                        { code: '+688', name: 'TV +688' },
                        { code: '+256', name: 'UG +256' },
                        { code: '+380', name: 'UA +380' },
                        { code: '+971', name: 'AE +971' },
                        { code: '+44', name: 'GB +44' },
                        { code: '+1', name: 'US +1' },
                        { code: '+598', name: 'UY +598' },
                        { code: '+998', name: 'UZ +998' },
                        { code: '+678', name: 'VU +678' },
                        { code: '+58', name: 'VE +58' },
                        { code: '+84', name: 'VN +84' },
                        { code: '+967', name: 'YE +967' },
                        { code: '+260', name: 'ZM +260' },
                        { code: '+263', name: 'ZW +263' },
                    ],
                    previews: [],
                    attachedFiles: [],
                    submitted: false,
                    ticketRef: '',
                    submitError: '',
                    copiedRef: false,
                    previewLightboxSrc: '',
                    previewLightboxOpen: false,
                    openPreview(url) {
                        this.previewLightboxSrc = url;
                        this.previewLightboxOpen = true;
                    },
                    handleFiles(e) {
                        const newFiles = Array.from(e.target.files);
                        this.attachedFiles = [...this.attachedFiles, ...newFiles];
                        this.previews = [...this.previews, ...newFiles.map(f => ({
                            url: URL.createObjectURL(f),
                            name: f.name,
                            isImage: f.type.startsWith('image/')
                        }))];
                        this.syncInput();
                    },
                    removePreview(idx) {
                        URL.revokeObjectURL(this.previews[idx].url);
                        this.attachedFiles.splice(idx, 1);
                        this.previews.splice(idx, 1);
                        this.syncInput();
                    },
                    syncInput() {
                        const dt = new DataTransfer();
                        this.attachedFiles.forEach(f => dt.items.add(f));
                        document.getElementById('file-upload').files = dt.files;
                    },
                    async submitTicket() {
                        if (this.processing) return;
                        this.processing = true;
                        this.submitError = '';
                        try {
                            const form = new FormData(this.$el);
                            const r = await fetch(this.$el.action, {
                                method: 'POST',
                                body: form,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await r.json();
                            if (r.ok && data.success) {
                                this.ticketRef = data.hashid;
                                this.submitted = true;
                            } else {
                                const msgs = data.errors ?
                                    Object.values(data.errors).flat().join(' ') :
                                    (data.message || 'Submission failed. Please try again.');
                                this.submitError = msgs;
                            }
                        } catch (err) {
                            this.submitError = 'An error occurred. Please check your connection.';
                            console.error(err);
                        } finally {
                            this.processing = false;
                        }
                    }
                }"
                @submit.prevent="submitTicket()">
                @csrf
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40">
                </div>

                {{-- Success state --}}
                <div x-show="submitted" x-cloak class="py-12 flex flex-col items-center text-center space-y-6">
                    <div
                        class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center ring-4 ring-emerald-200 dark:ring-emerald-800/40">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Ticket Submitted!</h2>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Your support request has been received.
                        </p>
                    </div>
                    <div
                        class="px-6 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 space-y-1">
                        <div
                            class="text-[10px] font-black tracking-widest text-emerald-950 dark:text-emerald-400 uppercase">
                            Reference Code</div>
                        <div class="flex items-center justify-center gap-2">
                            <div class="text-2xl font-black tracking-widest text-emerald-950 dark:text-white"
                                x-text="ticketRef"></div>
                            <button type="button"
                                @click="
                                (navigator.clipboard?.writeText(ticketRef) ?? Promise.reject())
                                .catch(() => {
                                    const ta = Object.assign(document.createElement('textarea'), { value: ticketRef, style: 'position:fixed;left:-9999px' });
                                    document.body.appendChild(ta);
                                    ta.select();
                                    document.execCommand('copy');
                                    ta.remove();
                                });
                                copiedRef = true;
                                setTimeout(() => copiedRef = false, 2000);
                            "
                                class="p-2 rounded-lg bg-emerald-200 dark:bg-emerald-800/50 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-300 dark:hover:bg-emerald-700 transition-all"
                                title="Copy Reference Code">
                                <svg x-show="!copiedRef" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg x-show="copiedRef" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-xs text-slate-400">Bookmark the ticket page to track updates</div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full max-w-sm">
                        <a :href="'/ticket/' + ticketRef"
                            class="fauna-btn-primary flex-1 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Ticket
                        </a>
                        <button type="button"
                            @click="submitted = false; ticketRef = ''; submitError = ''; processing = false; phone = ''; subject = ''; content = ''; previews = []; attachedFiles = []; copiedRef = false;"
                            class="flex-1 px-5 py-3 rounded-2xl border border-emerald-950/20 dark:border-[#1d3a34] text-slate-600 dark:text-slate-400 font-black text-sm hover:bg-slate-50 dark:hover:bg-[#18342f] transition-all">
                            Submit Another
                        </button>
                    </div>
                </div>

                {{-- Form fields --}}
                <div x-show="!submitted" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Name --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1"
                                for="name">
                                Name *
                            </label>
                            <input id="name" name="name" type="text"
                                value="{{ old('name', $user->name ?? '') }}"
                                class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                                placeholder="Enter your name" required />
                            @error('name')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1"
                                for="email">
                                Email Address *
                            </label>
                            <input id="email" name="email" type="email"
                                value="{{ old('email', $user->email ?? '') }}"
                                class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                                placeholder="email@example.com" required />
                            @error('email')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- WhatsApp --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1"
                                for="whatsapp">
                                WhatsApp Contact
                            </label>
                            <div
                                class="flex rounded-2xl overflow-hidden border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm focus-within:ring-2 focus-within:ring-lime-500 transition-all">
                                <select x-model="countryCode"
                                    class="shrink-0 px-3 py-4 bg-slate-100 dark:bg-[#0f2420] text-slate-600 dark:text-slate-400 font-bold text-sm border-0 outline-none border-r border-emerald-900/10 dark:border-[#1d3a34] cursor-pointer appearance-none pr-6"
                                    style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 4px center; background-size: 14px;">
                                    <template x-for="c in countryCodes" :key="c.code + c.name">
                                        <option :value="c.code" x-text="c.name" :selected="c.code === countryCode && c.name.startsWith('NG')"></option>
                                    </template>
                                </select>
                                <input maxlength="10" id="whatsapp" type="tel" x-model="whatsapp"
                                    class="flex-1 px-5 py-4 bg-white dark:bg-[#18342f] text-slate-900 dark:text-white outline-none border-0 font-medium min-w-0"
                                    placeholder="8012345678" />
                            </div>
                            <input type="hidden" name="whatsapp_number" :value="whatsapp ? countryCode + whatsapp : ''">
                            @error('whatsapp_number')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1">
                                Priority *
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                @php
                                    $priorityOptions = [
                                        [
                                            'value' => 'low',
                                            'label' => 'Low',
                                            'icon' =>
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />',
                                            'active' =>
                                                'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-400 text-emerald-600 dark:text-emerald-400',
                                        ],
                                        [
                                            'value' => 'medium',
                                            'label' => 'Medium',
                                            'icon' =>
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14" />',
                                            'active' =>
                                                'bg-amber-50 dark:bg-amber-900/20 border-amber-400 text-amber-600 dark:text-amber-400',
                                        ],
                                        [
                                            'value' => 'high',
                                            'label' => 'High',
                                            'icon' =>
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />',
                                            'active' =>
                                                'bg-red-50 dark:bg-red-900/20 border-red-400 text-red-600 dark:text-red-400',
                                        ],
                                    ];
                                    $inactiveClasses =
                                        'bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-slate-400';
                                @endphp
                                @foreach ($priorityOptions as $opt)
                                    <button type="button" x-bind:disabled="processing"
                                        @click="priority = '{{ $opt['value'] }}'"
                                        :class="priority === '{{ $opt['value'] }}' ? '{{ $opt['active'] }}' :
                                            '{{ $inactiveClasses }}'"
                                        class="flex flex-col items-center justify-center gap-2 py-4 rounded-2xl border-2 font-bold text-xs tracking-widest transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            {!! $opt['icon'] !!}
                                        </svg>
                                        {{ $opt['label'] }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="priority" x-model="priority">
                            @error('priority')
                                <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Subject / Category --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1"
                            for="subject">
                            Support Category *
                        </label>
                        <select id="subject" name="subject" x-model="subject"
                            @change="
                            const opt = $event.target.selectedOptions[0];
                            category_id = opt.dataset.categoryId || '';
                            order_type = '';
                            recurrence_period = '';
                        "
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold shadow-sm"
                            required>
                            <option value="" disabled>
                                {{ empty($categories) ? 'No categories available at the moment' : 'Select Category / Topic' }}
                            </option>
                            @foreach ($categories as $cat)
                                @php
                                    $catSlug = $cat['slug'] ?? $cat->slug;
                                    $catName = $cat['name'] ?? $cat->name;
                                    $catId = $cat['id'] ?? $cat->id;
                                    $isDisabled = !$user && $catSlug === 'order';
                                    $label = $isDisabled ? "{$catName} (requires account)" : $catName;
                                @endphp
                                <option value="{{ $catSlug }}" data-category-id="{{ $catId }}"
                                    @selected(old('subject') === $catSlug) @disabled($isDisabled)
                                    class="text-slate-900 dark:text-white font-medium">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="category_id" x-model="category_id">

                        @if (empty($categories))
                            <div class="text-amber-500 text-xs mt-1 font-semibold">
                                Support categories are currently unavailable. You cannot submit a ticket right now.
                            </div>
                        @endif
                        @error('subject')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Conditional Order Fields --}}
                    @if ($user)
                        <div x-show="subject === 'order'" x-cloak
                            class="space-y-6 p-6 rounded-2xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
                            <div class="space-y-3">
                                <label
                                    class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">
                                    Order Type
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($orderType as $type)
                                        <label
                                            :class="order_type === '{{ $type['id'] }}' ?
                                                'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400' :
                                                'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'"
                                            class="flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all">
                                            <input type="radio" name="order_type" value="{{ $type['id'] }}"
                                                class="hidden" x-model="order_type"
                                                @change="recurrence_period = ''" />
                                            <span class="text-xs font-black tracking-widest uppercase">
                                                {{ $type['label'] }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div x-show="order_type === 'recurrent'" x-cloak class="space-y-3">
                                <label
                                    class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">
                                    Recurrence Period
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach ($recurrencePeriod as $period)
                                        <label
                                            :class="recurrence_period === '{{ $period['id'] }}' ?
                                                'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400' :
                                                'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'"
                                            class="flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all">
                                            <input type="radio" name="recurrence_period"
                                                value="{{ $period['id'] }}" class="hidden"
                                                x-model="recurrence_period" />
                                            <span class="text-[10px] font-black tracking-tight uppercase text-center">
                                                {{ $period['label'] }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div x-show="recurrence_period === 'custom'" x-cloak class="pt-2">
                                    <input type="date" name="custom_recurrence_date"
                                        value="{{ old('custom_recurrence_date') }}"
                                        class="w-full px-5 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold"
                                        min="{{ now()->toDateString() }}" />
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="space-y-3">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1"
                            for="content">
                            Support Specification *
                        </label>
                        <textarea id="content" name="content" rows="6"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none resize-none font-medium shadow-sm"
                            placeholder="Describe the problem or inquiry with as much detail as possible..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Attachment Upload --}}
                    <div class="space-y-4">
                        <label class="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1 block">
                            Attachments
                        </label>
                        <div class="relative group/upload">
                            <input type="file" name="attachments[]" @change="handleFiles($event)" class="hidden"
                                id="file-upload"
                                accept="image/*,.txt,text/plain,.xls,.xlsx,.pdf,.doc,.docx,application/vnd.ms-excel,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                multiple />
                            <label for="file-upload"
                                class="flex flex-col items-center justify-center border-2 border-dashed border-emerald-900/20 dark:border-[#1d3a34] rounded-3xl p-10 hover:border-teal-900 dark:hover:border-lime-500 hover:bg-lime-500/5 transition-all cursor-pointer group">
                                <div
                                    class="w-12 h-12 mb-4 rounded-2xl bg-slate-100 dark:bg-[#18342f] flex items-center justify-center text-slate-400 group-hover:bg-teal-900 group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </div>
                                <span class="text-slate-900 dark:text-white font-bold text-sm">
                                    Upload Attachments
                                </span>
                                <span class="text-slate-600 text-xs mt-1">
                                    Any image format and/or document file up to 5MB each. Drag and drop supported.
                                </span>
                            </label>
                        </div>

                        {{-- Previews --}}
                        <div x-show="previews.length > 0" x-cloak class="mt-6">
                            <div class="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-4 tracking-[0.2em]">
                                Ready for Ticket (<span x-text="previews.length"></span>)
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <template x-for="(file, idx) in previews" :key="idx">
                                    <div class="relative group/preview">
                                        <template x-if="file.isImage">
                                            <button type="button" @click="openPreview(file.url)"
                                                class="relative block w-28 h-28 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-2xl cursor-zoom-in focus:outline-none">
                                                <img :src="file.url"
                                                    class="w-full h-full object-cover transition-transform group-hover/preview:scale-110"
                                                    :alt="file.name" />
                                                <span
                                                    class="absolute bottom-0 inset-x-0 text-center text-[7px] font-black tracking-wider text-white bg-black/40 py-0.5">PHOTO</span>
                                            </button>
                                        </template>

                                        <template x-if="!file.isImage">
                                            <div
                                                class="w-28 h-28 rounded-2xl border-2 border-white dark:border-[#1d3a34] shadow-2xl bg-slate-100 dark:bg-[#18342f] flex flex-col items-center justify-center gap-1 p-2">
                                                <svg class="w-8 h-8 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span
                                                    class="text-[9px] font-bold text-slate-500 text-center w-full truncate"
                                                    x-text="file.name.split('.').pop().toUpperCase()"></span>
                                            </div>
                                        </template>

                                        <button type="button" @click="removePreview(idx)"
                                            class="absolute -top-3 -right-3 bg-red-500 text-white p-2 rounded-xl shadow-xl hover:bg-red-600 transition-all opacity-0 group-hover/preview:opacity-100 scale-75 group-hover/preview:scale-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('attachments')
                            <div class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-6">
                        <div x-show="submitError" x-cloak
                            class="mb-4 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 text-red-600 dark:text-red-400 text-sm font-semibold"
                            x-text="submitError"></div>

                        <button type="submit"
                            x-bind:disabled="processing || {{ empty($categories) ? 'true' : 'false' }}"
                            class="group w-full py-5 rounded-[2rem] bg-teal-900 text-white font-black text-xl shadow-2xl hover:bg-[#10b981] hover:text-[#064e3b] hover:-translate-y-1 active:translate-y-0 active:shadow-none disabled:opacity-50 disabled:hover:translate-y-0 transition-all flex items-center justify-center gap-2">
                            <template x-if="processing">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>
                            </template>
                            <span x-text="processing ? 'Submitting Ticket...' : 'Submit Ticket'"></span>
                            <template x-if="!processing">
                                <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>{{-- /x-show="!submitted" --}}

                {{-- Pre-upload image preview lightbox --}}
                <template x-if="previewLightboxOpen">
                    <div @click.self="previewLightboxOpen = false"
                        @keydown.escape.window="previewLightboxOpen = false"
                        class="fixed inset-0 z-[300] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                        <div class="relative max-w-4xl w-full max-h-[90vh] flex items-center justify-center">
                            <button type="button" @click="previewLightboxOpen = false"
                                class="absolute -top-4 -right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 text-white rounded-full transition-all backdrop-blur-sm border border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <img :src="previewLightboxSrc"
                                class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl" />
                        </div>
                    </div>
                </template>
            </form>
        </div>
    </div>
</x-app-layout>
