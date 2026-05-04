import { useState } from 'react';
import NavLink from '@/Components/NavLink';
import Dropdown from '@/Components/Dropdown';
import { Link, usePage } from '@inertiajs/react';
import { useTheme } from '@/Contexts/ThemeContext';
import ApplicationLogo from '@/Components/ApplicationLogo';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';

export default function AuthenticatedLayout({ header, children }) {
    const user                   = usePage().props.auth.user;
    const { theme, toggleTheme } = useTheme();

    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="fauna-shell min-h-screen transition-colors duration-500 selection:bg-lime-500 selection:text-teal-900">

            {/* Background Layer */}
            <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10" />

            {/* Navigation Bar */}
            <nav className="relative z-50 border-b border-slate-200 bg-white/90 shadow-sm backdrop-blur dark:border-[#1d3a34] dark:bg-[#0b1715]/90">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-20 justify-between items-center">

                        {/* Left: Logo + Nav Links */}
                        <div className="flex items-center gap-8">
                            <Link href="/" className="flex items-center gap-3 group">
                                <div className="w-10 h-10 p-2 rounded-xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200/50 dark:border-slate-800/50 transition-transform group-hover:scale-110">
                                    <ApplicationLogo className="w-full h-full text-teal-900 dark:text-lime-400" />
                                </div>
                                <span className="hidden sm:block text-xl font-black tracking-tight text-slate-900 dark:text-white">
                                    laradrug<span className="text-lime-500">.</span>
                                </span>
                            </Link>

                            {user?.role === 'admin' && (
                                <div className="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#102824]/70 rounded-2xl border border-slate-200/50 dark:border-[#1d3a34] backdrop-blur-md">
                                    <NavLink
                                        href={route('dashboard')}
                                        active={route().current('dashboard')}
                                    >
                                        Dashboard
                                    </NavLink>
                                    <NavLink
                                        href={route('admin.users')}
                                        active={route().current('admin.users.*')}
                                    >
                                        Users Management
                                    </NavLink>
                                </div>
                            )}
                        </div>

                        {/* Right: Theme Toggle + User Dropdown */}
                        <div className="hidden sm:flex sm:items-center gap-4">

                             {/* Theme Toggle */}
                            <button
                                onClick={toggleTheme}
                                className="w-10 h-10 rounded-2xl flex items-center justify-center border border-slate-200/50 bg-white text-slate-500 transition-all hover:text-teal-900 dark:border-[#1d3a34] dark:bg-[#102824] dark:text-slate-400"
                                title="Toggle theme"
                            >
                                {theme === 'dark' ? (
                                    <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                                ) : (
                                    <svg className="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                                )}
                            </button>

                            {user?.role === 'admin' && (
                                <div className="relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                             <button
                                                type="button"
                                                className="flex items-center gap-3 p-1.5 pr-4 rounded-2xl glass-card border-slate-200/50 dark:border-slate-800/50 hover:border-lime-500/50 transition-all"
                                            >
                                                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white text-[10px] font-black shadow-lg">
                                                    {user?.name?.charAt(0).toUpperCase()}
                                                </div>
                                                <span className="text-xs font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                                    {user?.name}
                                                </span>
                                                <svg className="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                                </svg>
                                            </button>
                                        </Dropdown.Trigger>

                                        <Dropdown.Content>
                                            <Dropdown.Link href={route('profile.edit')}>
                                                Profile Settings
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route('logout')}
                                                method="post"
                                                as="button"
                                            >
                                                Sign Out
                                            </Dropdown.Link>
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            )}
                        </div>

                        {/* Mobile Controls */}
                        <div className="flex items-center gap-2 sm:hidden">
                            <button
                                onClick={toggleTheme}
                                className="w-10 h-10 rounded-xl flex items-center justify-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800"
                            >
                                {theme === 'dark' ? (
                                    <svg className="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" /></svg>
                                ) : (
                                    <svg className="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                                )}
                            </button>

                            <button
                                onClick={() => setShowingNavigationDropdown(!showingNavigationDropdown)}
                                className="w-10 h-10 rounded-xl flex items-center justify-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800"
                            >
                                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'} strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path className={showingNavigationDropdown ? 'inline-flex' : 'hidden'} strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile Dropdown Menu - admin only */}
                {user?.role === 'admin' && (
                    <div className={(showingNavigationDropdown ? 'block' : 'hidden') + ' sm:hidden glass-navbar !fixed !top-20 !inset-x-0 !border-t-0'}>
                        <div className="p-4 space-y-4">
                            <div className="space-y-1">
                                <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>
                                    Dashboard
                                </ResponsiveNavLink>
                                <ResponsiveNavLink href={route('admin.users')} active={route().current('admin.users.*')}>
                                    Users Management
                                </ResponsiveNavLink>
                            </div>

                            <div className="pt-4 border-t border-slate-200 dark:border-slate-800">
                                <div className="flex items-center gap-3 mb-4">
                                    <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white font-black">
                                        {user?.name?.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <div className="text-base font-bold text-slate-900 dark:text-white">{user?.name}</div>
                                        <div className="text-sm text-slate-500 dark:text-slate-400">{user?.email}</div>
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <ResponsiveNavLink href={route('profile.edit')}>Profile Settings</ResponsiveNavLink>
                                    <ResponsiveNavLink method="post" href={route('logout')} as="button">Sign Out</ResponsiveNavLink>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </nav>

            {/* Page Header */}
            {header && (
                <header className="relative z-10 py-10">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="fauna-panel relative overflow-hidden p-10">
                            <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40" />
                            {header}
                        </div>
                    </div>
                </header>
            )}

            {/* Main Content */}
            <main className="relative z-10 py-6">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {children}
                </div>
            </main>
        </div>
    );
}
