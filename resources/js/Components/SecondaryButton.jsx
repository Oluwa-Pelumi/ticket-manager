export default function SecondaryButton({
    disabled,
    children,
    className = '',
    type      = 'button',
    ...props
}) {
    return (
        <button
            {...props}
            type={type}
            className={
                `inline-flex items-center justify-center rounded-2xl border border-emerald-900/10 dark:border-[#1d3a34] bg-white/50 dark:bg-[#18342f]/50 backdrop-blur-xl px-6 py-3 text-xs font-black tracking-[0.2em] text-slate-700 dark:text-slate-300 shadow-lg transition-all duration-300 hover:bg-emerald-50/50 dark:hover:bg-slate-700/50 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:opacity-50 ${
                    disabled && 'opacity-50 grayscale cursor-not-allowed hover:scale-100'
                } ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}




