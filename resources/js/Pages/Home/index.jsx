import { Head, Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';

export default function Home({ auth }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <>
            <Head title="Drug Ticketing System" />

            <div className="relative flex min-h-screen flex-col items-center justify-center bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 overflow-hidden transition-colors duration-500">
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
                    <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/20 blur-[120px] animate-pulse" />
                    <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/20 blur-[120px]" />

                    <img
                        id="background"
                        className="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 invert dark:invert-0 pointer-events-none transition-all duration-500"
                        src="https://laravel.com/assets/img/welcome/background.svg"
                        alt=""
                    />
                </div>

                <div className="relative z-10 w-full max-w-4xl px-6 py-12">
                    <div className="text-center mb-16 space-y-4">
                        <div className="inline-flex items-center justify-center p-3 mb-6 bg-white dark:bg-slate-900 rounded-2xl shadow-xl shadow-[#FF2D20]/10 border border-slate-200 dark:border-slate-800">
                            <svg
                                className="h-12 w-auto text-[#FF2D20]"
                                viewBox="0 0 62 65"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </div>

                        <h1 className="text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-6xl">
                            How can we <span className="text-[#FF2D20]">help</span> today?
                        </h1>
                        <p className="max-w-xl mx-auto text-lg text-slate-600 dark:text-slate-400">
                            Our support team is ready to assist you. Choose an option below to get started.
                        </p>
                    </div>

                    <div className="mt-16 flex flex-wrap items-center justify-center gap-6 mb-10">
                        {!auth.user ? (
                            <>
                                <Link
                                    href={route('login')}
                                    className="text-sm font-semibold text-slate-900 dark:text-white hover:text-[#FF2D20] transition-colors"
                                >
                                    Log in
                                </Link>
                                <span className="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700" />
                                <Link
                                    href={route('register')}
                                    className="text-sm font-semibold text-slate-900 dark:text-white hover:text-[#FF2D20] transition-colors"
                                >
                                    Register
                                </Link>
                            </>
                        ) : (
                            <Link
                                href={route('dashboard')}
                                className="text-sm font-semibold text-[#FF2D20] hover:underline"
                            >
                                Back to Dashboard
                            </Link>
                        )}
                    </div>

                    <FlashHandler />

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                        {/* Submit Ticket Card */}
                        <Link
                            href={auth.user ? route('submit-ticket') : route('login')}
                            className="group relative block p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-[#FF2D20]/20 hover:-translate-y-1"
                        >
                            <div className="absolute inset-0 rounded-3xl bg-gradient-to-br from-[#FF2D20]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity" />

                            <div className="relative z-10">
                                <div className="w-14 h-14 mb-6 rounded-2xl bg-[#FF2D20]/10 flex items-center justify-center text-[#FF2D20] group-hover:bg-[#FF2D20] group-hover:text-white transition-colors duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">Submit a new ticket</h2>
                                <p className="text-slate-600 dark:text-slate-400">
                                    Need help with a technical issue or have a general inquiry? Describe your problem and we'll get back to you shortly.
                                </p>
                            </div>
                        </Link>

                        {/* View Status Card */}
                        <Link
                            href={auth.user ? route('dashboard') : route('login')}
                            className="group relative block p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-500/20 hover:-translate-y-1"
                        >
                            <div className="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity" />

                            <div className="relative z-10">
                                <div className="w-14 h-14 mb-6 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-3">View ticket status</h2>
                                <p className="text-slate-600 dark:text-slate-400">
                                    Already have a ticket? Check the current status and read updates from our support representatives.
                                </p>
                            </div>
                        </Link>
                    </div>


                </div>

                {/* Footer aesthetic */}
                <footer className="absolute bottom-8 left-0 right-0 text-center py-4">
                    <p className="text-sm text-slate-500 dark:text-slate-500">
                        &copy; {new Date().getFullYear()} {import.meta.env.VITE_APP_NAME || 'Ticketing System'}. All rights reserved.
                    </p>
                </footer>
            </div>
        </>
    );
}
