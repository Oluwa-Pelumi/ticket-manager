import { Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function GuestLayout({ children }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <div className="fauna-shell relative min-h-screen overflow-x-hidden py-10">
            <div className="container mx-auto px-4">
                <nav className="mb-12 flex items-center justify-between border-b border-slate-200 py-6 dark:border-slate-700">
                    <Link href="/" className="inline-flex items-center gap-3">
                        <ApplicationLogo className="h-8 w-8 text-teal-900 dark:text-lime-400" />
                        <span className="text-xl font-semibold tracking-tight">laradrug</span>
                    </Link>

                    <div className="flex items-center gap-3">
                        <Link href={route('home')} className="fauna-btn-secondary !px-4 !py-2">
                            Home
                        </Link>
                        <button
                            onClick={toggleTheme}
                            className="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 transition hover:border-teal-900 hover:text-teal-900 dark:border-slate-600 dark:text-slate-200"
                            aria-label="Toggle Theme"
                        >
                            {theme === 'dark' ? (
                                <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                            ) : (
                                <svg className="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                            )}
                        </button>
                    </div>
                </nav>

                <div className="mx-auto w-full max-w-md">
                    <div className="fauna-panel px-8 py-10 md:px-10 md:py-12">
                        {children}
                    </div>
                    <p className="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                        &copy; {new Date().getFullYear()} {import.meta.env.VITE_APP_NAME}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    );
}
