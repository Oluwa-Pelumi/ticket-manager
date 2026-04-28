import { Head, Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Home({ auth }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <>
            <Head title="laradrug | Support System" />

            <div className="relative min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 transition-colors duration-500 overflow-x-hidden selection:bg-indigo-500 selection:text-white">

                {/* Background Layer */}
                <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-60 dark:opacity-40" />

                {/* Navbar */}
                <nav className="fixed top-0 z-50 w-full px-6 py-4 glass-navbar border-b border-slate-200/50 dark:border-slate-800/50">
                    <div className="max-w-7xl mx-auto flex justify-between items-center">
                        <div className="flex items-center gap-3 group cursor-pointer">
                            <div className="w-10 h-10 p-2 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200/60 dark:border-slate-800/60 transition-transform group-hover:scale-110">
                                <ApplicationLogo className="w-full h-full" />
                            </div>
                            <span className="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                laradrug<span className="text-indigo-500">.</span>
                            </span>
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-slate-800/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 backdrop-blur-md">
                                <Link href={route('home')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl bg-white dark:bg-slate-900 text-indigo-500 shadow-sm border border-slate-200/50 dark:border-slate-800/50">
                                    Home
                                </Link>
                                {!auth.user ? (
                                    <Link href={route('login')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-500 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                        Sign In
                                    </Link>
                                ) : (
                                    <Link href={route('dashboard')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-500 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                        Dashboard
                                    </Link>
                                )}
                            </div>

                            <button
                                onClick={toggleTheme}
                                className="w-10 h-10 rounded-2xl flex items-center justify-center glass-card hover:scale-110 active:scale-95 transition-all"
                                aria-label="Toggle Theme"
                            >
                                {theme === 'dark' ? (
                                    <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                                ) : (
                                    <svg className="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                                )}
                            </button>
                        </div>
                    </div>
                </nav>

                <main className="relative z-10 flex-1 flex flex-col items-center justify-center px-6 py-12">
                    <div className="max-w-4xl w-full text-center space-y-12">

                        {/* Hero Section */}
                        <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-card text-xs font-semibold tracking-wide text-indigo-600 dark:text-indigo-400 border-indigo-500/20 mb-4">
                                <span className="relative flex h-2 w-2">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                </span>
                                SUPPORT REDEFINED
                            </div>

                            <h1 className="text-5xl md:text-7xl font-black text-slate-900 dark:text-white leading-[1.1] tracking-tight">
                                Reliable support, <br />
                                <span className="bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-500 animate-gradient-x">
                                    at your fingertips.
                                </span>
                            </h1>

                            <p className="max-w-2xl mx-auto text-lg md:text-xl text-slate-600 dark:text-slate-400">
                                laradrug provides a streamlined, professional support for all your needs and inquiries.
                            </p>
                        </div>

                        <FlashHandler />

                        {/* Action Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                            <Link
                                href={route('submit-ticket')}
                                className="group relative p-10 rounded-[2.5rem] glass-card overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20"
                            >
                                <div className="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 blur-[60px] group-hover:bg-indigo-500/20 transition-colors" />
                                <div className="relative z-10 flex flex-col items-center text-center">
                                    <div className="w-16 h-16 mb-6 rounded-2xl bg-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/40">
                                        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">Open a Ticket</h2>
                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Describe your issue and our experts will get back to you within minutes.
                                    </p>
                                </div>
                            </Link>

                            <Link
                                href={auth.user ? route('dashboard') : route('check-status')}
                                className="group relative p-10 rounded-[2.5rem] glass-card overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-slate-500/10"
                            >
                                <div className="absolute top-0 right-0 w-32 h-32 bg-slate-500/5 blur-[60px] group-hover:bg-indigo-500/5 transition-colors" />
                                <div className="relative z-10 flex flex-col items-center text-center">
                                    <div className="w-16 h-16 mb-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 shadow-xl group-hover:border-indigo-500/50 transition-colors">
                                        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    </div>
                                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">Check Status</h2>
                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Track your existing inquiries and view historical support data instantly.
                                    </p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="relative z-10 px-6 py-12 mt-auto border-t border-slate-200/60 dark:border-slate-800/60">
                    <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                        <div className="flex items-center gap-2 opacity-60">
                            <ApplicationLogo className="w-5 h-5" />
                            <span className="text-sm font-semibold tracking-wide text-slate-900 dark:text-white uppercase">laradrug</span>
                        </div>
                        <p className="text-sm text-slate-500 dark:text-slate-500">
                            &copy; {new Date().getFullYear()} laradrug. All rights reserved.
                        </p>
                        <div className="flex gap-6">
                            <a href="#" className="text-xs font-semibold text-slate-400 hover:text-indigo-500 transition-colors uppercase tracking-widest">Privacy</a>
                            <a href="#" className="text-xs font-semibold text-slate-400 hover:text-indigo-500 transition-colors uppercase tracking-widest">Terms</a>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
