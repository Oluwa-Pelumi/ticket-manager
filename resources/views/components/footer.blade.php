<footer class="border-t border-rose-950/10 px-6 py-16 dark:border-[#1e3a5f] bg-white dark:bg-[#020617]">
    <div class="container mx-auto flex flex-col items-center justify-between gap-6 md:flex-row">
        <div class="flex items-center gap-2 opacity-60">
            <img src="{{ asset('logo.svg') }}?v=3" alt="{{ config('app.name') }} logo" class="w-5 h-5">
            <span class="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
        </div>
    </div>
</footer>
