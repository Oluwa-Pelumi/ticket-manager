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
                    ? 'bg-white dark:bg-slate-900 text-indigo-500 shadow-sm border border-slate-200/50 dark:border-slate-800/50'
                    : 'text-slate-500 dark:text-slate-400 hover:text-indigo-500 hover:bg-slate-300/20 dark:hover:bg-slate-700/20') +
                ' ' + className
            }
        >
            {children}
        </Link>
    );
}
