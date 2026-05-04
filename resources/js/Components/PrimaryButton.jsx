export default function PrimaryButton({
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
                `inline-flex items-center justify-center rounded-2xl border border-transparent bg-teal-900 px-6 py-4 text-xs font-black tracking-[0.2em] text-white shadow-xl transition-all duration-300 hover:scale-[1.02] hover:bg-lime-500 hover:text-teal-900 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 ${
                    disabled && 'opacity-50 grayscale cursor-not-allowed hover:scale-100'
                } ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}




