import { Link } from '@inertiajs/react';

export default function NavLink({
    active    = false,
    className = '',
    children,
    ...props
}) {
    return (
        <Link
            {...props}
            className={
                'inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest transition duration-200 ease-in-out rounded-xl ' +
                (active
                    ? 'bg-white dark:bg-[#102824] text-teal-900 dark:text-lime-400 shadow-sm border border-emerald-900/10/50 dark:border-[#1d3a34]/50'
                    : 'text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 hover:bg-slate-300/20 dark:hover:bg-slate-700/20') +
                ' ' + className
            }
        >
            {children}
        </Link>
    );
}




