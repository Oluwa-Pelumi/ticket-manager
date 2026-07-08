<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-slate-100 dark:bg-[#1e293b] text-slate-700 dark:text-slate-300 text-xs font-bold border border-blue-900/10 dark:border-[#1e3a5f] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
