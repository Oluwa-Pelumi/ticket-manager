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
                `inline-flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 backdrop-blur-xl px-6 py-3 text-xs font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 shadow-lg transition-all duration-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:opacity-50 ${
                    disabled && 'opacity-50 grayscale cursor-not-allowed hover:scale-100'
                } ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
