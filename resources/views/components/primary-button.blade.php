<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-teal-900 text-white text-xs font-black tracking-widest shadow-lg hover:bg-[#10b981] hover:text-[#064e3b] hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
