export default function DangerButton({
    className = '',
    disabled,
    children,
    ...props
}) {
    return (
        <button
            {...props}
            className={
                `inline-flex items-center justify-center rounded-2xl border border-transparent bg-rose-600 px-6 py-3 text-xs font-black tracking-[0.2em] text-white shadow-xl shadow-rose-600/20 transition-all duration-300 hover:bg-rose-500 hover:scale-[1.02] hover:shadow-rose-600/30 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 ${
                    disabled && 'opacity-50 grayscale cursor-not-allowed hover:scale-100'
                } ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}




