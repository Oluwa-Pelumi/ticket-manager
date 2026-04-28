import { Link } from '@inertiajs/react';

export default function ResponsiveNavLink({
    children,
    active    = false,
    className = '',
    ...props
}) {
    return (
        <Link
            {...props}
            className={`flex w-full items-center gap-4 px-6 py-4 transition-all duration-300 ${
                active
                    ? 'bg-indigo-500/10 text-indigo-500 border-l-4 border-indigo-500 font-black'
                    : 'border-transparent text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-indigo-500 font-bold'
            } text-xs uppercase tracking-[0.2em] ${className}`}
        >
            {children}
        </Link>
    );
}
