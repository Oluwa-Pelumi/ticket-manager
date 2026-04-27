import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function CheckStatus({ auth, tickets, searchedEmail }) {
    const { theme, toggleTheme } = useTheme();
    const { data, setData, post, processing, errors } = useForm({
        email: searchedEmail || '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('search-tickets'));
    };

    return (
        <div className="relative min-h-screen flex flex-col bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 transition-colors duration-500 overflow-x-hidden">
            <Head title="Check Ticket Status" />

            {/* Theme Switcher FAB */}
            <button
                onClick={toggleTheme}
                className="fixed bottom-8 right-8 md:top-8 md:bottom-auto z-50 p-3 rounded-2xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 group"
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
                <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/10 blur-[120px]" />
                <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/10 blur-[120px]" />
                <img
                    className="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 invert dark:invert-0 pointer-events-none transition-all duration-500"
                    src="https://laravel.com/assets/img/welcome/background.svg"
                    alt=""
                />
            </div>

            <nav className="relative z-10 w-full px-4 md:px-6 py-4 md:py-6 border-b border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl">
                <div className="max-w-7xl mx-auto flex justify-between items-center">
                    <Link href={route('home')} className="flex items-center space-x-3 group">
                        <div className="p-2 bg-[#FF2D20]/10 rounded-xl group-hover:bg-[#FF2D20] transition-colors duration-300">
                            <svg className="w-6 h-6 text-[#FF2D20] group-hover:text-white transition-colors duration-300" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z" fill="currentColor"/></svg>
                        </div>
                        <span className="text-xl font-bold text-slate-900 dark:text-white group-hover:text-[#FF2D20] transition-colors">{import.meta.env.VITE_APP_NAME || 'MedTick'}</span>
                    </Link>
                </div>
            </nav>

            <main className="relative z-10 flex-grow py-12 px-6">
                <div className="max-w-3xl mx-auto">
                    <div className="text-center mb-10">
                        <h1 className="text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                            Check Ticket <span className="text-[#FF2D20]">Status</span>
                        </h1>
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
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                                    placeholder="Enter your email address..."
                                    required
                                />
                                {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-8 py-4 rounded-2xl bg-[#FF2D20] text-white font-bold text-lg shadow-xl shadow-[#FF2D20]/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:hover:scale-100 transition-all whitespace-nowrap"
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
                                            className="block p-5 md:p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-[#FF2D20]/50 transition-all group"
                                        >
                                            <div className="flex flex-col sm:flex-row justify-between items-start gap-3 mb-4">
                                                <div>
                                                    <div className="text-[10px] font-black text-[#FF2D20] uppercase tracking-wider mb-1">
                                                        #{ticket.id.substring(0, 8)}...
                                                    </div>
                                                    <h3 className="text-base md:text-lg font-bold text-slate-900 dark:text-white group-hover:text-[#FF2D20] transition-colors line-clamp-1">
                                                        {ticket.subject.replace(/_/g, ' ')}
                                                    </h3>
                                                </div>
                                                <span className={`inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${
                                                    ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                                    ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400' :
                                                    'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                                }`}>
                                                    {ticket.status.replace('-', ' ')}
                                                </span>
                                            </div>
                                            <p className="text-[13px] md:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">
                                                {ticket.content}
                                            </p>
                                            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[10px] md:text-xs text-slate-500">
                                                <div className="flex flex-wrap items-center gap-3 md:gap-4">
                                                    <span className="font-bold uppercase tracking-widest">Priority: {ticket.priority}</span>
                                                    <span>Created: {new Date(ticket.created_at).toLocaleDateString()}</span>
                                                </div>
                                                <div className="flex items-center text-[#FF2D20] font-black uppercase tracking-widest group-hover:translate-x-1 transition-transform self-end sm:self-auto">
                                                    Details 
                                                    <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"/></svg>
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
