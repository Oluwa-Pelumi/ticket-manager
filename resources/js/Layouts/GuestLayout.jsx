import { Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';

export default function GuestLayout({ children }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <div className="relative min-h-screen flex flex-col items-center justify-center bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 overflow-x-hidden transition-colors duration-500 py-12 px-6">
            
            {/* Theme Switcher FAB */}
            <button
                onClick={toggleTheme}
                className="fixed top-8 right-8 z-50 p-3 rounded-2xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 group"
                aria-label="Toggle Theme"
            >
                {theme === 'dark' ? (
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-yellow-400 fill-current" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                ) : (
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-slate-700 fill-current" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M20.354 15.354A9 9 0 118.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                )}
            </button>

            {/* Background Aesthetics */}
            <div className="absolute inset-0 bg-white dark:bg-slate-950 pointer-events-none transition-colors duration-500">
                <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/10 blur-[120px] animate-pulse" />
                <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/10 blur-[120px]" />
            </div>

            <div className="relative z-10 w-full max-w-md">
                <div className="flex justify-center mb-8">
                    <Link href="/">
                        <div className="w-16 h-16 rounded-2xl bg-[#FF2D20] flex items-center justify-center shadow-2xl shadow-[#FF2D20]/40 transform hover:scale-110 transition-transform duration-500">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </Link>
                </div>

                <div className="group relative block p-8 rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-3xl border border-white/50 dark:border-white/5 shadow-2xl transition-all duration-300">
                    {children}
                </div>
            </div>
        </div>
    );
}
