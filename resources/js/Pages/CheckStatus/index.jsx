import { useTheme } from "@/Contexts/ThemeContext";
import FlashHandler from "@/Components/FlashHandler";
import ApplicationLogo from "@/Components/ApplicationLogo";
import { Head, Link, useForm } from "@inertiajs/react";
import Footer from "@/Components/Footer";
import NavLink from "@/Components/NavLink";
import Dropdown from "@/Components/Dropdown";
import { useState } from "react";

export default function CheckStatus({ auth, tickets, searchedReference }) {
    const {user} = auth
    const { theme, toggleTheme } = useTheme();
    const { data, setData, post, processing, errors } = useForm({
        reference: searchedReference || "",
    });

    const submit = (e) => {
        e.preventDefault();
        post(route("search-tickets"));
    };

    const [showingNavigationDropdown, setShowingNavigationDropdown] =
            useState(false);

    return (
        <div className="fauna-shell relative min-h-screen flex flex-col selection:bg-lime-500 selection:text-teal-900 transition-colors duration-500 overflow-x-hidden">
            <Head title="Check Ticket Status" />

            {/* Background Layer */}
            <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10" />
            <nav className="relative z-50 border-b border-emerald-900/10 bg-white/90 shadow-sm backdrop-blur dark:border-[#1d3a34] dark:bg-[#102824]/90">
                <div className="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                    <div className="flex h-20 justify-between items-center">
                        {/* Left: Logo + Nav Links */}
                        <div className="flex items-center gap-8">
                            <Link
                                href="/"
                                className="flex items-center gap-3 group"
                            >
                                <div className="w-10 h-10 p-2 rounded-xl bg-white dark:bg-[#102824] shadow-xl border border-emerald-900/10/50 dark:border-[#1d3a34]/50 transition-transform group-hover:scale-110">
                                    <ApplicationLogo className="w-full h-full text-teal-900 dark:text-lime-400" />
                                </div>
                                <span className="hidden sm:block text-xl font-black tracking-tight text-slate-900 dark:text-white">
                                    laradrug
                                    <span className="text-lime-500">.</span>
                                </span>
                            </Link>

                            <div className="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#102824]/70 rounded-2xl border border-emerald-900/10/50 dark:border-[#1d3a34] backdrop-blur-md">
                                <NavLink
                                    href={route("home")}
                                    active={route().current("home")}
                                >
                                    Home
                                </NavLink>
                                <NavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    href={route("check-status")}
                                    active={route().current("check-status")}
                                >
                                    Check Status
                                </NavLink>
                                {user?.role === "admin" && (
                                    <NavLink
                                        href={route("admin.users")}
                                        active={route().current(
                                            "admin.users.*",
                                        )}
                                    >
                                        Users Management
                                    </NavLink>
                                )}
                            </div>
                        </div>

                        {/* Right: Theme Toggle + User Dropdown */}
                        <div className="hidden sm:flex sm:items-center gap-4">
                            {user?.role === "admin" && (
                                <div className="relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <button
                                                className="relative w-10 h-10 rounded-2xl flex items-center justify-center border border-emerald-900/10/50 bg-white text-slate-600 transition-all hover:text-teal-900 dark:border-[#1d3a34] dark:bg-[#102824] dark:text-slate-400 group"
                                                title={`${usePage().props.due_tickets.length} orders due for processing`}
                                            >
                                                <svg
                                                    className={`w-5 h-5 ${usePage().props.due_tickets.length > 0 ? "animate-bounce text-lime-500" : ""}`}
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                                    />
                                                </svg>
                                                {usePage().props.due_tickets
                                                    .length > 0 && (
                                                    <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white shadow-lg ring-2 ring-white dark:ring-[#102824]">
                                                        {
                                                            usePage().props
                                                                .due_tickets
                                                                .length
                                                        }
                                                    </span>
                                                )}
                                            </button>
                                        </Dropdown.Trigger>

                                        <Dropdown.Content
                                            width="120"
                                            align="right"
                                        >
                                            <div className="p-4 border-b border-slate-100 dark:border-[#1d3a34]">
                                                <h3 className="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                                    Due for Processing
                                                </h3>
                                            </div>
                                            <div className="max-h-96 overflow-y-auto custom-scrollbar">
                                                {usePage().props.due_tickets
                                                    .length > 0 ? (
                                                    usePage().props.due_tickets.map(
                                                        (ticket) => (
                                                            <div
                                                                key={ticket.id}
                                                                className="block p-4 hover:bg-emerald-50/50 dark:hover:bg-[#18342f] transition-colors border-b border-slate-100 dark:border-[#1d3a34] last:border-0"
                                                            >
                                                                <div className="flex justify-between items-start mb-1">
                                                                    <Link
                                                                        href={route(
                                                                            "dashboard",
                                                                        )}
                                                                        className="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 hover:text-teal-900 dark:hover:text-lime-400"
                                                                    >
                                                                        {ticket.subject.replace(
                                                                            /^./,
                                                                            (
                                                                                match,
                                                                            ) =>
                                                                                match.toUpperCase(),
                                                                        )}
                                                                    </Link>
                                                                    <span className="text-[10px] font-black text-lime-600 dark:text-lime-400 px-1.5 py-0.5 rounded shrink-0">
                                                                        {ticket.content.substring(
                                                                            0,
                                                                            32,
                                                                        )}
                                                                        ...
                                                                    </span>
                                                                    <span className="text-[9px] font-black uppercase bg-lime-500/10 text-lime-600 dark:text-lime-400 px-1.5 py-0.5 rounded shrink-0">
                                                                        {
                                                                            ticket.period
                                                                        }
                                                                    </span>
                                                                </div>
                                                                <div className="flex items-center justify-between gap-4">
                                                                    <div className="flex items-center gap-2">
                                                                        <span className="text-[9px] font-black text-slate-400 font-mono">
                                                                            {ticket.id.substring(
                                                                                0,
                                                                                8,
                                                                            )}
                                                                            ...
                                                                        </span>
                                                                        <button
                                                                            onClick={(
                                                                                e,
                                                                            ) => {
                                                                                e.stopPropagation();
                                                                                const textToCopy =
                                                                                    ticket.id;

                                                                                // Robust Copy Logic
                                                                                if (
                                                                                    navigator.clipboard &&
                                                                                    window.isSecureContext
                                                                                ) {
                                                                                    navigator.clipboard.writeText(
                                                                                        textToCopy,
                                                                                    );
                                                                                } else {
                                                                                    // Fallback to textarea
                                                                                    const textArea =
                                                                                        document.createElement(
                                                                                            "textarea",
                                                                                        );
                                                                                    textArea.value =
                                                                                        textToCopy;
                                                                                    textArea.style.position =
                                                                                        "fixed";
                                                                                    textArea.style.left =
                                                                                        "-999999px";
                                                                                    textArea.style.top =
                                                                                        "-999999px";
                                                                                    document.body.appendChild(
                                                                                        textArea,
                                                                                    );
                                                                                    textArea.focus();
                                                                                    textArea.select();
                                                                                    try {
                                                                                        document.execCommand(
                                                                                            "copy",
                                                                                        );
                                                                                    } catch (err) {
                                                                                        console.error(
                                                                                            "Fallback copy failed",
                                                                                            err,
                                                                                        );
                                                                                    }
                                                                                    document.body.removeChild(
                                                                                        textArea,
                                                                                    );
                                                                                }

                                                                                showAlert(
                                                                                    "Ticket ID copied to clipboard.",
                                                                                    "success",
                                                                                );
                                                                                const btn =
                                                                                    e.currentTarget;
                                                                                const originalInner =
                                                                                    btn.innerHTML;
                                                                                btn.innerHTML =
                                                                                    '<svg class="w-3 h-3 text-lime-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
                                                                                setTimeout(
                                                                                    () => {
                                                                                        btn.innerHTML =
                                                                                            originalInner;
                                                                                    },
                                                                                    2000,
                                                                                );
                                                                            }}
                                                                            className="p-1 rounded bg-slate-100 dark:bg-[#18342f] text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 transition-all"
                                                                            title="Copy full ID"
                                                                        >
                                                                            <svg
                                                                                className="w-3 h-3"
                                                                                fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24"
                                                                            >
                                                                                <path
                                                                                    strokeLinecap="round"
                                                                                    strokeLinejoin="round"
                                                                                    strokeWidth="2"
                                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                                                />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <div className="text-[10px] text-slate-500 dark:text-slate-400">
                                                                        Last
                                                                        Processed
                                                                        Order:{" "}
                                                                        {new Date(
                                                                            ticket.last_activation,
                                                                        ).toLocaleDateString()}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        ),
                                                    )
                                                ) : (
                                                    <div className="p-8 text-center">
                                                        <p className="text-xs text-slate-500 dark:text-slate-400">
                                                            No orders due for
                                                            processing.
                                                        </p>
                                                    </div>
                                                )}
                                            </div>
                                            {usePage().props.due_tickets
                                                .length > 0 && (
                                                <div className="p-3 bg-slate-50 dark:bg-[#0b1715]/50 text-center">
                                                    <Link
                                                        href={route(
                                                            "dashboard",
                                                        )}
                                                        className="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-widest hover:underline"
                                                    >
                                                        View all in Dashboard
                                                    </Link>
                                                </div>
                                            )}
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            )}

                            {/* Theme Toggle */}
                            <button
                                onClick={toggleTheme}
                                className="w-10 h-10 rounded-2xl flex items-center justify-center border border-emerald-900/10/50 bg-white text-slate-600 transition-all hover:text-teal-900 dark:border-[#1d3a34] dark:bg-[#102824] dark:text-slate-400"
                                title="Toggle theme"
                            >
                                {theme === "dark" ? (
                                    <svg
                                        className="w-5 h-5 text-yellow-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                                    </svg>
                                ) : (
                                    <svg
                                        className="w-5 h-5 text-slate-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                    </svg>
                                )}
                            </button>

                            {user?.role === "admin" && (
                                <div className="relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <button
                                                type="button"
                                                className="flex items-center gap-3 p-1.5 pr-4 rounded-2xl glass-card border-emerald-900/10/50 dark:border-[#1d3a34]/50 hover:border-lime-500/50 transition-all"
                                            >
                                                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white text-[10px] font-black shadow-lg">
                                                    {user?.name
                                                        ?.charAt(0)
                                                        .toUpperCase()}
                                                </div>
                                                <span className="text-xs font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                                    {user?.name}
                                                </span>
                                                <svg
                                                    className="h-4 w-4 text-slate-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </Dropdown.Trigger>

                                        <Dropdown.Content>
                                            <Dropdown.Link
                                                href={route("profile.edit")}
                                            >
                                                Profile Settings
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route(
                                                    "admin.categories.index",
                                                )}
                                            >
                                                Manage Categories
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route("admin.faqs.index")}
                                            >
                                                Manage FAQs
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route("logout")}
                                                method="post"
                                                as="button"
                                            >
                                                Sign Out
                                            </Dropdown.Link>
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            )}

                            {(user?.role === "user" ||
                                user?.role === "support") && (
                                <div className="relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <button
                                                type="button"
                                                className="flex items-center gap-3 p-1.5 pr-4 rounded-2xl glass-card border-emerald-900/10/50 dark:border-[#1d3a34]/50 hover:border-lime-500/50 transition-all"
                                            >
                                                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white text-[10px] font-black shadow-lg">
                                                    {user?.name
                                                        ?.charAt(0)
                                                        .toUpperCase()}
                                                </div>
                                                <span className="text-xs font-bold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                                    {user?.name}
                                                </span>
                                                <svg
                                                    className="h-4 w-4 text-slate-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </Dropdown.Trigger>

                                        <Dropdown.Content>
                                            <Dropdown.Link
                                                href={route("profile.edit")}
                                            >
                                                Profile Settings
                                            </Dropdown.Link>
                                            <Dropdown.Link
                                                href={route("logout")}
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
                                className="w-10 h-10 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 bg-white dark:bg-[#102824] border border-emerald-900/10 dark:border-[#1d3a34]"
                            >
                                {theme === "dark" ? (
                                    <svg
                                        className="w-5 h-5 text-yellow-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                                    </svg>
                                ) : (
                                    <svg
                                        className="w-5 h-5 text-slate-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                    </svg>
                                )}
                            </button>

                            <button
                                onClick={() =>
                                    setShowingNavigationDropdown(
                                        !showingNavigationDropdown,
                                    )
                                }
                                className="w-10 h-10 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 bg-white dark:bg-[#102824] border border-emerald-900/10 dark:border-[#1d3a34]"
                            >
                                <svg
                                    className="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        className={
                                            !showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={
                                            showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile Dropdown Menu - admin only */}
                {user?.role === "admin" && (
                    <div
                        className={
                            (showingNavigationDropdown ? "block" : "hidden") +
                            " sm:hidden glass-navbar !fixed !top-20 !inset-x-0 !border-t-0"
                        }
                    >
                        <div className="p-4 space-y-4">
                            <div className="space-y-1">
                                <ResponsiveNavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                >
                                    Dashboard
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    href={route("admin.users")}
                                    active={route().current("admin.users.*")}
                                >
                                    Users Management
                                </ResponsiveNavLink>
                            </div>

                            <div className="pt-4 border-t border-emerald-900/10 dark:border-[#1d3a34]">
                                <div className="flex items-center gap-3 mb-4">
                                    <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-900 to-teal-700 flex items-center justify-center text-white font-black">
                                        {user?.name?.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <div className="text-base font-bold text-slate-900 dark:text-white">
                                            {user?.name}
                                        </div>
                                        <div className="text-sm text-slate-600 dark:text-slate-400">
                                            {user?.email}
                                        </div>
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <ResponsiveNavLink
                                        href={route("profile.edit")}
                                    >
                                        Profile Settings
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        href={route("admin.categories.index")}
                                    >
                                        Manage Categories
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        method="post"
                                        href={route("logout")}
                                        as="button"
                                    >
                                        Sign Out
                                    </ResponsiveNavLink>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </nav>

            <main className="relative z-10 flex-grow py-2 px-6">
                <div className="max-w-3xl mx-auto">
                    {/* Standardized Header */}
                    <div className="fauna-panel mb-10 p-10 relative overflow-hidden text-left">
                        <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40" />
                        <div className="flex items-center gap-4">
                            <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                                <svg
                                    className="w-6 h-6 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth={2.5}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                            </div>
                            <div className="flex flex-col">
                                <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Search Tickets
                                </h1>
                                <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">
                                    Historical Inquiries Retrieval
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="text-center mb-10">
                        <p className="mt-4 text-sm md:text-lg text-slate-600 dark:text-slate-400 px-4">
                            Enter the 8-character reference code to view the
                            current status of your ticket.
                        </p>
                    </div>

                    <FlashHandler />

                    <form
                        onSubmit={submit}
                        className="fauna-panel group relative block p-8 rounded-3xl mb-12"
                    >
                        <div className="flex flex-col md:flex-row gap-4">
                            <div className="flex-grow">
                                <label className="sr-only" htmlFor="reference">
                                    Ticket Reference
                                </label>
                                <input
                                    id="reference"
                                    type="text"
                                    value={data.reference}
                                    onChange={(e) =>
                                        setData("reference", e.target.value)
                                    }
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-mono"
                                    placeholder="Enter your 8-character ticket reference..."
                                    required
                                    maxLength={8}
                                />
                                {errors.reference && (
                                    <div className="text-red-500 text-xs mt-1">
                                        {errors.reference}
                                    </div>
                                )}
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-8 py-4 rounded-2xl bg-teal-900 text-white font-black text-xs tracking-widest shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:hover:scale-100 transition-all whitespace-nowrap"
                            >
                                {processing
                                    ? "Searching Tickets..."
                                    : "Search Tickets"}
                            </button>
                        </div>
                    </form>

                    {tickets && (
                        <div className="animate-in fade-in slide-in-from-bottom-4 duration-500">
                            <h2 className="text-xl font-bold text-slate-900 dark:text-white mb-6">
                                Ticket status for:{" "}
                                <span className="font-mono text-teal-900 dark:text-lime-400">
                                    {searchedReference}
                                </span>
                            </h2>

                            {tickets.length > 0 ? (
                                <div className="space-y-4">
                                    {tickets.map((ticket) => (
                                        <Link
                                            key={ticket.id}
                                            href={route(
                                                "ticket.show",
                                                ticket.id,
                                            )}
                                            className="block p-8 bg-white dark:bg-[#102824] rounded-[2.5rem] border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm hover:shadow-2xl hover:shadow-lime-500/10 hover:border-teal-900/50 transition-all group"
                                        >
                                            <div className="flex flex-col sm:flex-row justify-between items-start gap-3 mb-6">
                                                <div>
                                                    <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 tracking-[0.2em] mb-1 font-mono uppercase">
                                                        #{ticket.hashid}
                                                    </div>
                                                    <h3 className="text-lg md:text-xl font-bold text-slate-900 dark:text-white group-hover:text-teal-900 dark:group-hover:text-lime-400 transition-colors line-clamp-1">
                                                        {ticket.category
                                                            ?.name ||
                                                            ticket.subject.replace(
                                                                /_/g,
                                                                " ",
                                                            )}
                                                    </h3>
                                                </div>
                                                <span
                                                    className={`inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest ${
                                                        ticket.status === "open"
                                                            ? "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 ring-4 ring-emerald-500/10"
                                                            : ticket.status ===
                                                                "in-progress"
                                                              ? "bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 ring-4 ring-orange-500/10"
                                                              : "bg-slate-100 text-slate-600 dark:bg-[#18342f] dark:text-slate-400"
                                                    }`}
                                                >
                                                    {ticket.status.replace(
                                                        "-",
                                                        " ",
                                                    )}
                                                </span>
                                            </div>
                                            <p className="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-6 leading-relaxed">
                                                {ticket.content}
                                            </p>
                                            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[10px] md:text-xs text-slate-600">
                                                <div className="flex flex-wrap items-center gap-3 md:gap-4">
                                                    <span className="font-black tracking-widest text-slate-400">
                                                        Priority:{" "}
                                                        <span className="text-slate-900 dark:text-white">
                                                            {ticket.priority}
                                                        </span>
                                                    </span>
                                                    <span className="font-black tracking-widest text-slate-400">
                                                        Date:{" "}
                                                        <span className="text-slate-900 dark:text-white">
                                                            {new Date(
                                                                ticket.created_at,
                                                            ).toLocaleDateString()}
                                                        </span>
                                                    </span>
                                                </div>
                                                <div className="flex items-center text-teal-900 dark:text-lime-400 font-black tracking-widest group-hover:translate-x-1 transition-transform self-end sm:self-auto">
                                                    Open Ticket
                                                    <svg
                                                        className="w-4 h-4 ml-1"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth="2.5"
                                                            d="M9 5l7 7-7 7"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-12 text-center bg-white dark:bg-[#102824] rounded-3xl border border-emerald-900/10 dark:border-[#1d3a34]">
                                    <svg
                                        className="w-16 h-16 mx-auto text-slate-300 dark:text-slate-700 mb-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2">
                                        No ticket found
                                    </h3>
                                    <p className="text-slate-600">
                                        We couldn't find a ticket associated
                                        with that reference code.
                                    </p>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </main>

            {/* Footer */}
            <Footer />
        </div>
    );
}
