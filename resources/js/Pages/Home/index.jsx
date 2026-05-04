import { Head, Link } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import ApplicationLogo from '@/Components/ApplicationLogo';

const ticketingSteps = [
    {
        step: 1,
        title: 'Submit',
        description:
            'Create a ticket with the right category, your contact details, and what happened. Attach images if they help our team understand faster.',
    },
    {
        step: 2,
        title: 'Review',
        description:
            'Our team sees your ticket on the dashboard, filters by status or priority, and updates progress—including assigning someone when needed.',
    },
    {
        step: 3,
        title: 'Reply in thread',
        description:
            'Conversation stays on the ticket page: add comments or files anytime it is open. Check the same ticket for staff replies.',
    },
    {
        step: 4,
        title: 'Resolved',
        description:
            'When your request is complete, the ticket is marked closed. You can still open past tickets from your dashboard or by status search with your email.',
    },
];

export default function Home({ auth, stats }) {
    const { theme, toggleTheme } = useTheme();

    return (
        <>
            <Head title="laradrug | Support System" />

            <div className="fauna-shell min-h-screen">
                <section className="relative overflow-hidden bg-teal-900 dark:bg-[#102824]">
                    <div className="container mx-auto px-4">
                        <nav className="py-6">
                            <div className="flex items-center justify-between">
                                <div className="inline-flex items-center gap-3 text-white">
                                    <ApplicationLogo className="h-8 w-8" />
                                    <span className="text-xl font-semibold tracking-tight">laradrug</span>
                                </div>

                                <div className="flex items-center gap-3">
                                    {!auth.user ? (
                                        <Link href={route('login')} className="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-lime-500/50 dark:hover:!bg-lime-500 dark:hover:!text-[#102824]">
                                            Login
                                        </Link>
                                    ) : (
                                        <Link href={route('dashboard')} className="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-lime-500/50 dark:hover:!bg-lime-500 dark:hover:!text-[#102824]">
                                            Dashboard
                                        </Link>
                                    )}
                                    <button
                                        onClick={toggleTheme}
                                    className="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/50 text-white transition hover:border-lime-500 hover:text-lime-400 dark:border-lime-500/40"
                                        aria-label="Toggle Theme"
                                    >
                                        {theme === 'dark' ? (
                                            <svg className="w-5 h-5 text-lime-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                                        ) : (
                                            <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                                        )}
                                    </button>
                                </div>
                            </div>
                        </nav>

                        <div className="pb-20 pt-16 text-center">
                            <h1 className="mx-auto mb-8 max-w-3xl text-5xl font-medium tracking-tight text-white md:text-7xl">
                                Energizing a Green Future
                            </h1>
                            <p className="mx-auto mb-10 max-w-2xl text-lg text-white/80">
                                Our commitment to green energy is paving the way for a cleaner, healthier planet.
                            </p>
                            <div className="flex flex-col items-center justify-center gap-3 sm:flex-row">
                                <Link href={route('submit-ticket')} className="fauna-btn-primary">
                                    Create Ticket
                                </Link>
                                <Link
                                    href={auth.user ? route('dashboard') : route('check-status')}
                                    className="fauna-btn-secondary !border-white !text-white hover:!bg-white hover:!text-teal-900 dark:!border-lime-500/50 dark:hover:!bg-lime-500 dark:hover:!text-[#102824]"
                                >
                                    View Ticket
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                <section className="py-14">
                    <div className="container mx-auto px-4">
                        <div className="grid grid-cols-1 gap-8 text-center md:grid-cols-4">
                            {[
                                { label: 'Total Tickets', value: stats?.totalTickets ?? 0 },
                                { label: 'Open Tickets', value: stats?.openTickets ?? 0 },
                                { label: 'In Progress', value: stats?.inProgressTickets ?? 0 },
                                { label: 'Resolved Tickets', value: stats?.resolvedTickets ?? 0 },
                            ].map((stat) => (
                                <div key={stat.label} className="fauna-panel p-8 dark:bg-[#102824] dark:border-[#1d3a34]">
                                    <h3 className="text-3xl font-semibold">{stat.value}</h3>
                                    <p className="mt-2 text-slate-600 dark:text-slate-400">{stat.label}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="p-4 bg-white dark:bg-[#0b1715]">
                    <div className="rounded-3xl bg-lime-500 px-6 py-16 dark:bg-[#102824] dark:border dark:border-[#1d3a34]">
                        <div className="container mx-auto px-4">
                            <p className="mb-4 text-sm font-medium text-teal-900 dark:text-lime-400">How it works</p>
                            <h2 className="mb-4 text-4xl font-semibold text-teal-900 dark:text-white">How ticketing works</h2>
                            <p className="mb-12 max-w-2xl text-teal-900/90 dark:text-slate-300">
                                From your first message to a closed ticket—here is what happens in laradrug.
                            </p>
                            <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
                                {ticketingSteps.map((item) => (
                                    <div
                                        key={item.step}
                                        className="rounded-2xl bg-white p-8 dark:bg-[#18342f] dark:border dark:border-[#28524a]"
                                    >
                                        <span className="mb-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-teal-900 text-sm font-semibold text-white dark:bg-lime-500 dark:text-[#102824]">
                                            {item.step}
                                        </span>
                                        <h3 className="text-2xl font-medium text-teal-900 dark:text-white">{item.title}</h3>
                                        <p className="mt-3 text-slate-600 dark:text-slate-300">{item.description}</p>
                                    </div>
                                ))}
                            </div>
                            <div className="mt-12 flex flex-wrap items-center justify-center gap-4 border-t border-teal-900/20 pt-10 dark:border-[#28524a]">
                                <Link href={route('submit-ticket')} className="fauna-btn-primary text-sm">
                                    Create ticket
                                </Link>
                                <Link
                                    href={route('check-status')}
                                    className="fauna-btn-secondary text-sm dark:!border-lime-500/50 dark:!text-lime-400 dark:hover:!bg-lime-500/10"
                                >
                                    Check status
                                </Link>
                                {auth.user && (
                                    <Link
                                        href={route('dashboard')}
                                        className="fauna-btn-secondary text-sm dark:!border-lime-500/50 dark:!text-lime-400 dark:hover:!bg-lime-500/10"
                                    >
                                        Dashboard
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </section>

                <section className="py-16">
                    <div className="container mx-auto px-4 text-center">
                        <h2 className="mx-auto mb-10 max-w-5xl text-4xl font-semibold">
                            Need help with a prescription, refill, or medication concern?
                        </h2>
                        <Link href={route('submit-ticket')} className="fauna-btn-primary">Create Ticket</Link>
                        <div className="mt-12 grid grid-cols-2 gap-4 md:grid-cols-4">
                            {[1, 2, 3, 4].map((n) => (
                                <div key={n} className="h-40 rounded-2xl bg-slate-200 dark:bg-[#18342f] dark:border dark:border-[#28524a]" />
                            ))}
                        </div>
                    </div>
                </section>

                <section className="py-12">
                    <div className="container mx-auto px-4">
                        <div className="mb-12 text-center">
                            <h2 className="text-5xl font-semibold">FAQ</h2>
                            <p className="mt-3 text-slate-600 dark:text-slate-400">Here you will find answers to frequently asked questions.</p>
                        </div>
                        <div className="mx-auto max-w-4xl space-y-4">
                            {[
                                {
                                    q: 'How do I create a support ticket?',
                                    a: 'Click "Create Ticket", fill in your contact details, select the support category, describe your issue clearly, and submit.',
                                },
                                {
                                    q: 'Can I check ticket status without logging in?',
                                    a: 'Yes. Use the "View Ticket" option and search with the same email address used when the ticket was submitted.',
                                },
                                {
                                    q: 'What should I include in my ticket description?',
                                    a: 'Include medication name, dosage, when the issue happened, what you expected, and what happened instead. Add images when relevant.',
                                },
                                {
                                    q: 'How do updates and replies work?',
                                    a: 'Our support/admin team responds in the ticket conversation thread. You can return to the ticket page and continue the discussion.',
                                },
                                {
                                    q: 'Can I edit or add more information after submission?',
                                    a: 'Yes. Open your ticket to add comments and attachments. If the ticket is still open, you can provide additional context for faster resolution.',
                                },
                            ].map((item) => (
                                <details key={item.q} className="fauna-panel p-6 dark:bg-[#102824] dark:border-[#1d3a34]">
                                    <summary className="cursor-pointer font-medium">{item.q}</summary>
                                    <p className="mt-3 text-slate-600 dark:text-slate-400">{item.a}</p>
                                </details>
                            ))}
                        </div>
                    </div>
                </section>


                <section className="bg-orange-50 py-16 dark:bg-[#0b1715]">
                    <div className="container mx-auto px-4">
                        <FlashHandler />
                        <div className="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-4">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2">
                                    <ApplicationLogo className="h-6 w-6" />
                                    <span className="font-semibold">laradrug</span>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-8 md:grid-cols-3 lg:col-span-2">
                                <div>
                                    <h4 className="mb-4 font-bold">Platform</h4>
                                    <ul className="space-y-2 text-slate-600 dark:text-slate-400">
                                        <li>Solutions</li>
                                        <li>How it works</li>
                                        <li>Pricing</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 className="mb-4 font-bold">Resources</h4>
                                    <ul className="space-y-2 text-slate-600 dark:text-slate-400">
                                        <li>Blog</li>
                                        <li>Help Center</li>
                                        <li>Support</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 className="mb-4 font-bold">Company</h4>
                                    <ul className="space-y-2 text-slate-600 dark:text-slate-400">
                                        <li>About</li>
                                        <li>Mission</li>
                                        <li>Careers</li>
                                    </ul>
                                </div>
                            </div>
                            <div className="rounded-2xl bg-teal-900 p-6 dark:bg-[#102824] dark:border dark:border-[#1d3a34]">
                                <h4 className="mb-3 text-xl font-medium text-white">Open a new support request</h4>
                                <p className="mb-6 text-white/80">Use the ticket system to report issues, request updates, or ask for help.</p>
                                <Link href={route('submit-ticket')} className="fauna-btn-primary w-full">Create Ticket</Link>
                            </div>
                        </div>
                    </div>
                </section>

                <footer className="border-t border-emerald-900/10 px-6 py-16 dark:border-[#1d3a34] bg-white dark:bg-[#0b1715]">
                    <div className="container mx-auto flex flex-col items-center justify-between gap-6 md:flex-row">
                        <div className="flex items-center gap-2 opacity-60">
                            <ApplicationLogo className="w-5 h-5" />
                            <span className="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">laradrug</span>
                        </div>
                        <p className="text-sm text-slate-600 dark:text-slate-400">
                            &copy; {new Date().getFullYear()} laradrug. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}




