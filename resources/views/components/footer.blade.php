<<<<<<< HEAD
﻿<footer class="border-t border-emerald-950/10 px-6 py-16 dark:border-[#1e3a5f] bg-white dark:bg-[#020617]">
=======
<footer class="border-t border-emerald-900/10 px-6 py-16 dark:border-[#1d3a34] bg-white dark:bg-[#0b1715]">
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
    <div class="container mx-auto flex flex-col items-center justify-between gap-6 md:flex-row">
        <div class="flex items-center gap-2 opacity-60">
            <img src="{{ asset('logo.svg') }}?v=2" alt="{{ config('app.name') }} logo" class="w-5 h-5">
            <span class="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">{{ config('app.name') }}</span>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</footer>
