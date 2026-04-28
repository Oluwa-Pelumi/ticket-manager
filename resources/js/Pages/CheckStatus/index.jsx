import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Head, Link, useForm } from '@inertiajs/react';

export default function CheckStatus({ auth, tickets, searchedEmail }) {
    const { theme, toggleTheme }                      = useTheme();
    const { data, setData, post, processing, errors } = useForm({
        email: searchedEmail || '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('search-tickets'));
    };

    return (
        <div className="relative min-h-screen flex flex-col bg-slate-50 selection:bg-indigo-500 selection:text-white dark:bg-slate-950 transition-colors duration-500 overflow-x-hidden">
            <Head title="Check Ticket Status" />



            {/* Background Layer */}
            <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-60 dark:opacity-40" />

            <nav className="relative z-50 w-full px-6 py-4 glass-navbar border-b border-slate-200/50 dark:border-slate-800/50">
                <div className="max-w-7xl mx-auto flex justify-between items-center">
                    <Link href={route('home')} className="flex items-center space-x-3 group">
                        <div className="w-10 h-10 p-2 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200/50 dark:border-slate-800/50 transition-transform group-hover:scale-110">
                            <ApplicationLogo className="w-full h-full text-indigo-500" />
                        </div>
                        <span className="text-xl font-black text-slate-900 dark:text-white tracking-tight">laradrug<span className="text-indigo-500">.</span></span>
                    </Link>

                    <div className="flex items-center gap-3">
                        <div className="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-slate-800/40 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 backdrop-blur-md">
                            <Link href={route('home')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-500 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                Home
                            </Link>
                            {auth.user ? (
                                <Link href={route('dashboard')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl bg-white dark:bg-slate-900 text-indigo-500 shadow-sm border border-slate-200/50 dark:border-slate-800/50">
                                    Dashboard
                                </Link>
                            ) : (
                                <Link href={route('login')} className="inline-flex items-center px-4 py-2 text-[10px] font-black uppercase tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-500 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                    Sign In
                                </Link>
                            )}
                        </div>
                        <button
                            onClick={toggleTheme}
                            className="w-10 h-10 rounded-2xl flex items-center justify-center glass-card text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-all"
                            title="Toggle Theme"
                        >
                            {theme === 'dark' ? (
                                <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                            ) : (
                                <svg className="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                            )}
                        </button>
                    </div>
                </div>
            </nav>

            <main className="relative z-10 flex-grow py-12 px-6">
                <div className="max-w-3xl mx-auto">
                    
                    {/* Standardized Header */}
                    <div className="mb-10 p-10 rounded-[2.5rem] glass-card border-white/20 dark:border-slate-800/50 relative overflow-hidden text-left">
                        <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-30" />
                        <div className="flex items-center gap-4">
                            <div className="w-12 h-12 rounded-2xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 border border-white/20">
                                <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div className="flex flex-col">
                                <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Search Tickets
                                </h1>
                                <span className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Historical Inquiries Retrieval</span>
                            </div>
                        </div>
                    </div>

                    <div className="text-center mb-10">
                        <p className="mt-4 text-sm md:text-lg text-slate-600 dark:text-slate-400 px-4">
                            Enter the email address used to submit your ticket to view its current status.
                        </p>
                    </div>

                    <FlashHandler />

                    <form onSubmit={submit} className="group relative block p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl mb-12">
                        <div className="flex flex-col md:flex-row gap-4">
                            <div className="flex-grow">
                                <label className="sr-only" htmlFor="email">Email Address</label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                                    placeholder="Enter your email address..."
                                    required
                                />
                                {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-8 py-4 rounded-2xl bg-indigo-500 text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:hover:scale-100 transition-all whitespace-nowrap"
                            >
                                {processing ? 'Searching...' : 'Search Tickets'}
                            </button>
                        </div>
                    </form>

                    {tickets && (
                        <div className="animate-in fade-in slide-in-from-bottom-4 duration-500">
                            <h2 className="text-xl font-bold text-slate-900 dark:text-white mb-6">
                                Tickets for {searchedEmail}
                            </h2>
                            
                            {tickets.length > 0 ? (
                                <div className="space-y-4">
                                    {tickets.map((ticket) => (
                                        <Link
                                            key={ticket.id}
                                            href={route('ticket.show', ticket.id)}
                                            className="block p-8 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-500/50 transition-all group"
                                        >
                                            <div className="flex flex-col sm:flex-row justify-between items-start gap-3 mb-6">
                                                <div>
                                                    <div className="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">
                                                        #{ticket.id.substring(0, 8)}
                                                    </div>
                                                    <h3 className="text-lg md:text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-500 transition-colors line-clamp-1">
                                                        {ticket.subject.replace(/_/g, ' ')}
                                                    </h3>
                                                </div>
                                                <span className={`inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ${
                                                    ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 ring-4 ring-emerald-500/10' :
                                                    ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 ring-4 ring-orange-500/10' :
                                                    'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                                }`}>
                                                    {ticket.status.replace('-', ' ')}
                                                </span>
                                            </div>
                                            <p className="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-6 leading-relaxed">
                                                {ticket.content}
                                            </p>
                                            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[10px] md:text-xs text-slate-500">
                                                <div className="flex flex-wrap items-center gap-3 md:gap-4">
                                                    <span className="font-black uppercase tracking-widest text-slate-400">Priority: <span className="text-slate-900 dark:text-white">{ticket.priority}</span></span>
                                                    <span className="font-black uppercase tracking-widest text-slate-400">Date: <span className="text-slate-900 dark:text-white">{new Date(ticket.created_at).toLocaleDateString()}</span></span>
                                                </div>
                                                <div className="flex items-center text-indigo-500 font-black uppercase tracking-widest group-hover:translate-x-1 transition-transform self-end sm:self-auto">
                                                    Open Ticket
                                                    <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 5l7 7-7 7"/></svg>
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-12 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                                    <svg className="w-16 h-16 mx-auto text-slate-300 dark:text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2">No tickets found</h3>
                                    <p className="text-slate-500">We couldn't find any tickets associated with that email address.</p>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </main>
        </div>
    );
}
