import { useState } from 'react';
import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';


// Categories will be passed from the backend


export default function SubmitTicket({ auth, categories = [] }) {
    // Group categories by their group field
    const groupedCategories = categories.reduce((acc, category) => {
        const group = category.group || 'General';
        if (!acc[group]) acc[group] = [];
        acc[group].push(category);
        return acc;
    }, {});
    const { theme, toggleTheme }                             = useTheme();
    const [previewUrls, setPreviewUrls]                      = useState([]);
    const { data, setData, post, processing, errors, reset } = useForm({
        images         : [],
        subject        : '',
        content        : '',
        priority       : 'low',
        name           : auth.user?.name || '',
        email          : auth.user?.email || '',
        whatsapp_number: auth.user?.whatsapp_number || '',
        category_id    : '',
        order_type     : '',
        recurrence_period: '',
        custom_recurrence_date: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('submit-ticket'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <>
            <Head title="Submit Ticket" />

            <div className="fauna-shell relative min-h-screen flex flex-col items-center overflow-x-hidden transition-colors duration-500 selection:bg-lime-500 selection:text-teal-900">

                {/* Background Layer */}
                <div className="fixed inset-0 mesh-gradient pointer-events-none opacity-20 dark:opacity-10" />

                {/* Navbar */}
                <nav className="relative z-50 w-full px-6 py-4 border-b border-emerald-900/10 bg-white/90 dark:border-[#1d3a34] dark:bg-[#102824]/90 backdrop-blur">
                    <div className="max-w-7xl mx-auto flex justify-between items-center">
                        <Link href={route('home')} className="flex items-center space-x-3 group">
                            <div className="w-10 h-10 p-2 rounded-xl bg-white dark:bg-[#102824] shadow-xl border border-emerald-900/20 dark:border-[#1d3a34]/50 transition-transform group-hover:scale-110">
                                <ApplicationLogo className="w-full h-full text-teal-900 dark:text-lime-400" />
                            </div>
                            <span className="text-xl font-black text-slate-900 dark:text-white tracking-tight">laradrug<span className="text-lime-500">.</span></span>
                        </Link>
                        <div className="flex items-center gap-3">
                            <div className="hidden sm:flex items-center gap-1 p-1 bg-slate-200/40 dark:bg-[#18342f]/40 rounded-2xl border border-emerald-900/20 dark:border-[#1d3a34]/50 backdrop-blur-md">
                                <Link href={route('home')} className="inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-600 dark:text-slate-400 hover:text-teal-900 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                    Home
                                </Link>
                                {auth.user?.role === 'admin' ? (
                                    <Link href={route('dashboard')} className="inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-600 dark:text-slate-400 hover:text-teal-900 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                        Dashboard
                                    </Link>
                                ) : !auth.user ? (
                                    <Link href={route('login')} className="inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest transition duration-200 ease-in-out rounded-xl text-slate-600 dark:text-slate-400 hover:text-teal-900 hover:bg-slate-300/20 dark:hover:bg-slate-700/20">
                                        Sign In
                                    </Link>
                                ) : null}
                            </div>
                            <button
                                onClick={toggleTheme}
                                className="w-10 h-10 rounded-2xl flex items-center justify-center border border-emerald-900/10 bg-white text-slate-600 dark:border-[#1d3a34] dark:bg-[#102824] dark:text-slate-400 hover:text-teal-900 transition-all"
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

                <div className="relative z-10 w-full max-w-3xl mx-auto px-6 pt-20 pb-16">

                    {/* Standardized Header */}
                    <div className="fauna-panel mb-10 p-10 relative overflow-hidden">
                        <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40" />
                        <div className="flex items-center gap-4">
                            <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                                <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div className="flex flex-col">
                                <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Support Portal
                                </h1>
                                <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">Submit Request</span>
                            </div>
                        </div>
                    </div>

                    <div className="text-center mb-10">
                        <Link href={route('home')} className="inline-flex items-center text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-teal-900 transition-colors mb-4 tracking-widest">
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Homepage
                        </Link>
                    </div>

                    <FlashHandler />

                    <form onSubmit={submit} className="fauna-panel relative block p-8 md:p-12 space-y-8 overflow-hidden">
                        <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40" />

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {/* Name */}
                            <div className="space-y-3">
                                <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" htmlFor="name">Name *</label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                                    placeholder="Enter your name"
                                    required
                                />
                                {errors.name && <div className="text-red-500 text-xs mt-1 font-semibold">{errors.name}</div>}
                            </div>

                            {/* Email */}
                            <div className="space-y-3">
                                <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" htmlFor="email">Email Address *</label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                                    placeholder="email@example.com"
                                    required
                                />
                                {errors.email && <div className="text-red-500 text-xs mt-1 font-semibold">{errors.email}</div>}
                            </div>

                            {/* WhatsApp */}
                            <div className="space-y-3">
                                <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" htmlFor="whatsapp">WhatsApp Contact</label>
                                <input
                                    id="whatsapp"
                                    type="tel"
                                    value={data.whatsapp_number}
                                    onChange={e => setData('whatsapp_number', e.target.value)}
                                    className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium shadow-sm"
                                    placeholder="+234..."
                                />
                                {errors.whatsapp_number && <div className="text-red-500 text-xs mt-1 font-semibold">{errors.whatsapp_number}</div>}
                            </div>

                            {/* Priority */}
                            <div className="space-y-3">
                                <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1">Priority *</label>
                                <div className="grid grid-cols-3 gap-3">
                                    {[
                                        {
                                            value: 'low',
                                            label: 'Low',
                                            icon: (
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            ),
                                            active: 'bg-blue-50 dark:bg-blue-900/20 border-blue-400 text-blue-600 dark:text-blue-400',
                                            inactive: 'bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-slate-400',
                                        },
                                        {
                                            value: 'medium',
                                            label: 'Medium',
                                            icon: (
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 12h14" />
                                                </svg>
                                            ),
                                            active: 'bg-amber-50 dark:bg-amber-900/20 border-amber-400 text-amber-600 dark:text-amber-400',
                                            inactive: 'bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-slate-400',
                                        },
                                        {
                                            value: 'high',
                                            label: 'High',
                                            icon: (
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                            ),
                                            active: 'bg-red-50 dark:bg-red-900/20 border-red-400 text-red-600 dark:text-red-400',
                                            inactive: 'bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#1d3a34] text-slate-400',
                                        },
                                    ].map(({ value, label, icon, active, inactive }) => (
                                        <button
                                            key={value}
                                            type="button"
                                            onClick={() => setData('priority', value)}
                                            className={`flex flex-col items-center justify-center gap-2 py-4 rounded-2xl border-2 font-bold text-xs tracking-widest transition-all shadow-sm ${data.priority === value ? active : inactive}`}
                                        >
                                            {icon}
                                            {label}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Subject */}
                        <div className="space-y-3">
                            <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" htmlFor="subject">Support Category *</label>
                            <select
                                id="subject"
                                value={data.subject}
                                onChange={(e) => {
                                    const selectedCat = categories.find(c => c.slug === e.target.value);
                                    setData({
                                        ...data,
                                        subject: e.target.value,
                                        category_id: selectedCat?.id || '',
                                        order_type: '',
                                        recurrence_period: '',
                                        custom_recurrence_date: '',
                                    });
                                }}
                                className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold shadow-sm"
                                required
                            >
                                <option value="" disabled>Select Department / Topic</option>
                                {Object.entries(groupedCategories).map(([groupLabel, items]) => (
                                    <optgroup key={groupLabel} label={groupLabel} className="font-bold text-teal-900 dark:text-lime-400">
                                        {items.map((sub, idx) => (
                                            <option key={idx} value={sub.slug} className="text-slate-900 dark:text-white font-medium">{sub.name}</option>
                                        ))}
                                    </optgroup>
                                ))}
                            </select>
                            {errors.subject && <div className="text-red-500 text-xs mt-1">{errors.subject}</div>}
                        </div>

                        {/* Conditional Order Fields */}
                        {data.subject === 'order' && (
                            <div className="space-y-6 p-6 rounded-2xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] animate-in fade-in slide-in-from-top-2">
                                <div className="space-y-3">
                                    <label className="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Order Type</label>
                                    <div className="grid grid-cols-2 gap-4">
                                        {[
                                            { id: 'one-time', label: 'One Time' },
                                            { id: 'recurrent', label: 'Recurrent Order' }
                                        ].map((type) => (
                                            <label
                                                key={type.id}
                                                className={`flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all ${
                                                    data.order_type === type.id
                                                        ? 'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400'
                                                        : 'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'
                                                }`}
                                            >
                                                <input
                                                    type="radio"
                                                    name="order_type"
                                                    value={type.id}
                                                    className="hidden"
                                                    onChange={(e) => setData({
                                                        ...data,
                                                        order_type: e.target.value,
                                                        recurrence_period: '',
                                                        custom_recurrence_date: '',
                                                    })}
                                                />
                                                <span className="text-xs font-black tracking-widest uppercase">{type.label}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                {data.order_type === 'recurrent' && (
                                    <div className="space-y-3 animate-in fade-in slide-in-from-top-2">
                                        <label className="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Recurrence Period</label>
                                        <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            {[
                                                { id: 'one-week', label: 'One Week' },
                                                { id: 'two-weeks', label: 'Two Weeks' },
                                                { id: 'monthly', label: 'Monthly' },
                                                { id: 'custom', label: 'Custom' }
                                            ].map((period) => (
                                                <label
                                                    key={period.id}
                                                    className={`flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all ${
                                                        data.recurrence_period === period.id
                                                            ? 'border-lime-500 bg-lime-500/10 text-teal-900 dark:text-lime-400'
                                                            : 'border-emerald-900/10 dark:border-[#1d3a34] hover:border-emerald-900/20'
                                                    }`}
                                                >
                                                    <input
                                                        type="radio"
                                                        name="recurrence_period"
                                                        value={period.id}
                                                        className="hidden"
                                                        onChange={(e) => setData('recurrence_period', e.target.value)}
                                                    />
                                                    <span className="text-[10px] font-black tracking-tight uppercase text-center">{period.label}</span>
                                                </label>
                                            ))}
                                        </div>

                                        {data.recurrence_period === 'custom' && (
                                            <div className="pt-2 animate-in fade-in slide-in-from-top-2">
                                                <input
                                                    type="date"
                                                    value={data.custom_recurrence_date}
                                                    onChange={(e) => setData('custom_recurrence_date', e.target.value)}
                                                    className="w-full px-5 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-bold"
                                                    min={new Date().toISOString().split('T')[0]}
                                                />
                                            </div>
                                        )}
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Content */}
                        <div className="space-y-3">
                            <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1" htmlFor="content">Support Specification *</label>
                            <textarea
                                id="content"
                                value={data.content}
                                onChange={e => setData('content', e.target.value)}
                                rows="6"
                                className="w-full px-5 py-4 rounded-2xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none resize-none font-medium shadow-sm"
                                placeholder="Describe the problem or inquiry with as much detail as possible..."
                                required
                            ></textarea>
                            {errors.content && <div className="text-red-500 text-xs mt-1 font-semibold">{errors.content}</div>}
                        </div>

                        {/* Image Upload */}
                        <div className="space-y-4">
                            <label className="text-xs font-bold tracking-widest text-slate-600 dark:text-slate-400 ml-1 block">Attachments</label>
                            <div className="relative group/upload">
                                <input
                                    type="file"
                                    onChange={(e) => {
                                        const files = Array.from(e.target.files);
                                        setData('images', files);
                                        const urls = files.map(file => URL.createObjectURL(file));
                                        setPreviewUrls(urls);
                                    }}
                                    className="hidden"
                                    id="image-upload"
                                    accept="image/*"
                                    multiple
                                />
                                <label
                                    htmlFor="image-upload"
                                    className="flex flex-col items-center justify-center border-2 border-dashed border-emerald-900/20 dark:border-[#1d3a34] rounded-3xl p-10 hover:border-teal-900 dark:hover:border-lime-500 hover:bg-lime-500/5 transition-all cursor-pointer group"
                                >
                                    <div className="w-12 h-12 mb-4 rounded-2xl bg-slate-100 dark:bg-[#18342f] flex items-center justify-center text-slate-400 group-hover:bg-teal-900 group-hover:text-white transition-all shadow-sm">
                                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    </div>
                                    <span className="text-slate-900 dark:text-white font-bold text-sm">Upload System Snapshots</span>
                                    <span className="text-slate-600 text-xs mt-1">PNG, JPG up to 5MB each. Drag and drop supported.</span>
                                </label>
                            </div>

                            {/* Previews */}
                            {previewUrls.length > 0 && (
                                <div className="mt-6 animate-in fade-in zoom-in duration-500">
                                    <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-4 tracking-[0.2em]">Ready for Ticket ({previewUrls.length})</div>
                                    <div className="flex flex-wrap gap-4">
                                        {previewUrls.map((url, idx) => (
                                            <div key={idx} className="relative group/preview">
                                                <img
                                                    src={url}
                                                    className="w-28 h-28 object-cover rounded-2xl border-2 border-white dark:border-[#1d3a34] shadow-2xl transition-transform group-hover/preview:scale-110"
                                                    alt={`Preview ${idx + 1}`}
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        const newFiles = [...data.images];
                                                        newFiles.splice(idx, 1);
                                                        setData('images', newFiles);

                                                        const newUrls = [...previewUrls];
                                                        newUrls.splice(idx, 1);
                                                        setPreviewUrls(newUrls);
                                                    }}
                                                    className="absolute -top-3 -right-3 bg-red-500 text-white p-2 rounded-xl shadow-xl hover:bg-red-600 transition-all opacity-0 group-hover/preview:opacity-100 scale-75 group-hover/preview:scale-100"
                                                >
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Submit */}
                        <div className="pt-6">
                            <button
                                type="submit"
                                disabled={processing}
                                className="group w-full py-5 rounded-[2rem] bg-teal-900 text-white font-black text-xl shadow-2xl hover:bg-lime-500 hover:text-teal-900 hover:-translate-y-1 active:translate-y-0 active:shadow-none disabled:opacity-50 disabled:hover:translate-y-0 transition-all"
                            >
                                <span className="flex items-center justify-center gap-2">
                                    {processing ? 'Submitting Ticket...' : (
                                        <>
                                            Submit Ticket
                                            <svg className="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </>
                                    )}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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
        </>
    );
}


