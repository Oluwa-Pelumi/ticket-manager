import { Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function GuestLayout({ children }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <div className="relative min-h-screen flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950 overflow-x-hidden transition-colors duration-500 py-12 px-6 selection:bg-indigo-500 selection:text-white">
            
            {/* Background Layer */}
            <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-60 dark:opacity-40" />

            {/* Top Navigation */}
            <nav className="fixed top-0 right-0 z-50 p-6">
                <button
                    onClick={toggleTheme}
                    className="p-3 rounded-2xl glass-card hover:scale-110 active:scale-95 transition-all shadow-xl"
                    aria-label="Toggle Theme"
                >
                    {theme === 'dark' ? (
                        <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                    ) : (
                        <svg className="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                    )}
                </button>
            </nav>

            <div className="relative z-10 w-full max-w-md">
                <div className="flex flex-col items-center mb-10 text-center">
                    <Link href="/" className="group mb-6">
                        <div className="w-20 h-20 p-4 rounded-[2rem] bg-white dark:bg-slate-900 shadow-2xl border border-slate-200/60 dark:border-slate-800/60 transition-transform group-hover:scale-110 group-hover:rotate-3">
                            <ApplicationLogo className="w-full h-full" />
                        </div>
                    </Link>
                    <h2 className="text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                        laradrug <span className="text-indigo-500">.</span>
                    </h2>
                    <p className="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">
                        PORTAL ACCESS
                    </p>
                </div>

                <div className="relative p-8 md:p-10 rounded-[2.5rem] glass-card border-white/20 dark:border-slate-800/50">
                    <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50" />
                    {children}
                </div>

                <div className="mt-8 text-center">
                    <p className="text-sm text-slate-500 dark:text-slate-500">
                        &copy; {new Date().getFullYear()} {import.meta.env.VITE_APP_NAME}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    );
}
