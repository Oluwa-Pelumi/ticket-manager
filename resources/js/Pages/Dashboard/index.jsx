import { useTheme } from '@/Contexts/ThemeContext';
import { Head, Link, usePage, useForm } from '@inertiajs/react';
import { useState, useMemo, Fragment } from 'react';

const subjects = [
    {name: '1', value: '1'},
    {name: '2', value: '2'},
    {name: '3', value: '3'},
    {name: '4', value: '4'},
]

export default function Dashboard({ auth, tickets }) {
    const { flash } = usePage().props;
    const { theme, toggleTheme } = useTheme();

    const [selectedIds, setSelectedIds] = useState([]);
    const [expandedId, setExpandedId] = useState(null);
    const [sortConfig, setSortConfig] = useState({ key: 'id', direction: 'desc' });
    const [filters, setFilters] = useState({
        status: '',
        priority: '',
        subject: '',
    });

    // Editing State
    const [editingTicket, setEditingTicket] = useState(null);
    const [previewUrls, setPreviewUrls] = useState([]);
    const editForm = useForm({
        subject: '',
        content: '',
        priority: 'medium',
        images: [],
    });

    const commentForm = useForm({
        content: '',
        images: [],
    });

    const [commentPreviewUrls, setCommentPreviewUrls] = useState([]);

    const openEditModal = (ticket) => {
        setEditingTicket(ticket);
        editForm.setData({
            subject: ticket.subject,
            content: ticket.content,
            priority: ticket.priority,
            images: [],
        });
        setPreviewUrls(ticket.images ? ticket.images.map(img => `/storage/${img}`) : []);
    };

    const closeEditModal = () => {
        setEditingTicket(null);
        editForm.reset();
        setPreviewUrls([]);
    };

    const handleEditSubmit = (e) => {
        e.preventDefault();
        editForm.post(route('update-ticket', { ticket: editingTicket.id }), {
            _method: 'patch', // multipart/form-data doesn't support PATCH natively in some setups, we can spoof it
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => closeEditModal(),
        });
    };

    const handleImagesChange = (e) => {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            editForm.setData('images', files);
            setPreviewUrls(files.map(file => URL.createObjectURL(file)));
        }
    };

    const handleCommentSubmit = (e, ticketId) => {
        e.preventDefault();
        commentForm.post(route('add-comment', { ticket: ticketId }), {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset();
                setCommentPreviewUrls([]);
            },
        });
    };

    const filteredTickets = useMemo(() => {
        return tickets.filter(ticket => {
            const matchesStatus = !filters.status || ticket.status === filters.status;
            const matchesPriority = !filters.priority || ticket.priority === filters.priority;
            const matchesSubject = !filters.subject || ticket.subject === filters.subject;
            return matchesStatus && matchesPriority && matchesSubject;
        });
    }, [tickets, filters]);

    const sortedTickets = useMemo(() => {
        let sortableItems = [...filteredTickets];
        if (sortConfig.key !== null) {
            sortableItems.sort((a, b) => {
                let aValue = a[sortConfig.key];
                let bValue = b[sortConfig.key];

                // Handle nested fields
                if (sortConfig.key === 'user') aValue = a.user?.name;
                if (sortConfig.key === 'attendant') aValue = a.attendant?.name;

                if (aValue < bValue) {
                    return sortConfig.direction === 'asc' ? -1 : 1;
                }
                if (aValue > bValue) {
                    return sortConfig.direction === 'asc' ? 1 : -1;
                }
                return 0;
            });
        }
        return sortableItems;
    }, [filteredTickets, sortConfig]);

    const clearFilters = () => {
        setFilters({ status: '', priority: '', subject: '' });
    };

    const requestSort = (key) => {
        let direction = 'asc';
        if (sortConfig.key === key && sortConfig.direction === 'asc') {
            direction = 'desc';
        }
        setSortConfig({ key, direction });
    };

    const getSortIcon = (key) => {
        if (sortConfig.key !== key) {
            return (
                <svg className="w-3 h-3 ml-1 opacity-20 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
            );
        }
        return sortConfig.direction === 'asc'
            ? <svg className="w-3 h-3 ml-1 text-[#FF2D20]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 15l7-7 7 7"/></svg>
            : <svg className="w-3 h-3 ml-1 text-[#FF2D20]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"/></svg>;
    };

    const { patch, delete: destroy, processing } = useForm({});

    const handleStatusUpdate = (id, newStatus) => {
        patch(route('update-ticket-status', { id, status: newStatus }), {
            preserveScroll: true,
            onSuccess: () => setSelectedIds([]),
        });
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this ticket?')) {
            destroy(route('delete-ticket', { id }), {
                preserveScroll: true,
                onSuccess: () => setSelectedIds([]),
            });
        }
    };

    const handleBulkDelete = () => {
        if (confirm(`Are you sure you want to delete ${selectedIds.length} tickets?`)) {
            destroy(route('bulk-delete-tickets', { ids: selectedIds }), {
                preserveScroll: true,
                onSuccess: () => setSelectedIds([]),
            });
        }
    };

    const handleBulkStatusChange = (status) => {
        patch(route('bulk-update-ticket-status', { ids: selectedIds, status }), {
            preserveScroll: true,
            onSuccess: () => setSelectedIds([]),
        });
    };

    const toggleSelectAll = () => {
        if (selectedIds.length === tickets.length) {
            setSelectedIds([]);
        } else {
            setSelectedIds(tickets.map(t => t.id));
        }
    };

    const toggleSelect = (id) => {
        setSelectedIds(prev =>
            prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
        );
    };

    const toggleExpand = (id) => {
        setExpandedId(prev => prev === id ? null : id);
    };

    return (
        <div className="relative min-h-screen flex flex-col bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 transition-colors duration-500 overflow-x-hidden">
            <Head title="Ticket Dashboard" />

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
                <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/10 blur-[120px]" />
                <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/10 blur-[120px]" />
                <img
                    className="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 invert dark:invert-0 pointer-events-none transition-all duration-500"
                    src="https://laravel.com/assets/img/welcome/background.svg"
                    alt=""
                />
            </div>

            <nav className="relative z-10 w-full px-6 py-6 border-b border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl">
                <div className="max-w-7xl mx-auto flex justify-between items-center">
                    <Link href={route('home')} className="flex items-center space-x-3 group">
                        <div className="p-2 bg-[#FF2D20]/10 rounded-xl group-hover:bg-[#FF2D20] transition-colors duration-300">
                            <svg className="w-6 h-6 text-[#FF2D20] group-hover:text-white transition-colors duration-300" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z" fill="currentColor"/></svg>
                        </div>
                        <span className="text-xl font-bold text-slate-900 dark:text-white group-hover:text-[#FF2D20] transition-colors">{import.meta.env.VITE_APP_NAME || 'DrugTick'}</span>
                    </Link>

                    <div className="flex items-center space-x-6">
                        <span className="text-sm text-slate-600 dark:text-slate-400">
                            Welcome, <span className="font-bold text-slate-900 dark:text-white">{auth.user.name}</span>
                            {auth.user.role === 'admin' && <span className="ml-2 px-3 py-1 rounded-full bg-[#FF2D20]/10 text-[#FF2D20] text-xs font-bold uppercase">Admin</span>}
                        </span>
                        {auth.user.role === 'admin' && (
                            <Link href={route('admin.users')} className="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-[#FF2D20] transition-colors flex items-center">
                                <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Manage Users
                            </Link>
                        )}
                        <Link method="post" href={route('logout')} as="button" className="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-[#FF2D20] transition-colors">
                            Logout
                        </Link>
                    </div>
                </div>
            </nav>

            <main className="relative z-10 flex-grow py-12 px-6">
                <div className="max-w-7xl mx-auto">

                    <div className="flex justify-between items-end mb-8">
                        <div>
                            <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Ticket Management</h1>
                            <p className="text-slate-600 dark:text-slate-400 mt-1">
                                {auth.user.role === 'admin' ? 'Manage and resolve tickets from all users.' : 'Track and manage your submitted tickets.'}
                            </p>
                        </div>
                        <div className="flex items-center space-x-4">
                            {selectedIds.length > 0 && auth.user.role === 'admin' && (
                                <div className="flex items-center space-x-2 p-2 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in duration-300">
                                    <span className="text-xs font-bold text-slate-600 dark:text-slate-400 px-2">{selectedIds.length} Selected</span>
                                    <select
                                        onChange={(e) => handleBulkStatusChange(e.target.value)}
                                        className="text-xs font-bold bg-white dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-[#FF2D20]"
                                        defaultValue=""
                                    >
                                        <option value="" disabled>Update Status</option>
                                        <option value="open">Open</option>
                                        <option value="in-progress">In Progress</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                    <button
                                        onClick={handleBulkDelete}
                                        className="p-2 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all"
                                        title="Delete Selected"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            )}
                            {auth.user.role !== 'admin' && (
                                <Link href={route('submit-ticket')} className="px-6 py-3 bg-[#FF2D20] text-white rounded-xl font-bold shadow-lg shadow-[#FF2D20]/20 hover:scale-105 active:scale-95 transition-all">
                                    New Ticket
                                </Link>
                            )}
                        </div>
                    </div>

                    {/* Alert Notifications */}
                    {flash.success && (
                        <div className="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center shadow-lg animate-in slide-in-from-top duration-500">
                            <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                            {flash.success}
                        </div>
                    )}

                    {/* Filter Bar */}
                    <div className="flex flex-wrap items-center gap-4 mb-6 p-4 rounded-2xl bg-white/50 dark:bg-slate-900/50 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50">
                        <div className="flex items-center space-x-2">
                            <svg className="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span className="text-sm font-bold text-slate-500 dark:text-slate-400">Filters:</span>
                        </div>

                        <select
                            value={filters.status}
                            onChange={(e) => setFilters(prev => ({ ...prev, status: e.target.value }))}
                            className="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Statuses</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In-Progress</option>
                            <option value="closed">Closed</option>
                        </select>

                        <select
                            value={filters.priority}
                            onChange={(e) => setFilters(prev => ({ ...prev, priority: e.target.value }))}
                            className="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>

                        <select
                            value={filters.subject}
                            onChange={(e) => setFilters(prev => ({ ...prev, subject: e.target.value }))}
                            className="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Subjects</option>
                            {subjects.map((sub, idx) => <option key={idx} value={sub.value}>{sub.name}</option>)}
                        </select>

                        {(filters.status || filters.priority || filters.subject) && (
                            <button
                                onClick={clearFilters}
                                className="text-xs font-bold text-rose-500 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-rose-500/10 transition-all flex items-center"
                            >
                                <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear All
                            </button>
                        )}

                        <div className="ml-auto text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Showing {filteredTickets.length} of {tickets.length}
                        </div>
                    </div>

                    <div className="relative group overflow-hidden rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-3xl border border-white/50 dark:border-white/5 shadow-2xl transition-all duration-300 group-hover:shadow-[0_20px_50px_rgba(255,45,32,0.1)]">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left table-fixed">
                                <thead>
                                    <tr className="border-b border-slate-200 dark:border-slate-800">
                                        <th className="w-16 px-6 py-4">
                                            <input
                                                type="checkbox"
                                                className="rounded-lg border-slate-300 dark:border-slate-700 text-[#FF2D20] focus:ring-[#FF2D20] dark:bg-slate-800 transition-colors cursor-pointer"
                                                checked={tickets.length > 0 && selectedIds.length === tickets.length}
                                                onChange={toggleSelectAll}
                                            />
                                        </th>
                                        <th className="w-24 px-6 py-4">
                                            <button
                                                onClick={() => requestSort('id')}
                                                className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                            >
                                                ID {getSortIcon('id')}
                                            </button>
                                        </th>
                                        <th className="px-6 py-4">
                                            <button
                                                onClick={() => requestSort('subject')}
                                                className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                            >
                                                Information {getSortIcon('subject')}
                                            </button>
                                        </th>
                                        {auth.user.role === 'admin' && (
                                            <th className="px-6 py-4">
                                                <button
                                                    onClick={() => requestSort('user')}
                                                    className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                                >
                                                    User {getSortIcon('user')}
                                                </button>
                                            </th>
                                        )}
                                        <th className="w-32 px-6 py-4">
                                            <button
                                                onClick={() => requestSort('priority')}
                                                className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                            >
                                                Priority {getSortIcon('priority')}
                                            </button>
                                        </th>
                                        <th className="w-48 px-6 py-4">
                                            <button
                                                onClick={() => requestSort('status')}
                                                className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                            >
                                                Status {getSortIcon('status')}
                                            </button>
                                        </th>
                                        <th className="w-48 px-6 py-4">
                                            <button
                                                onClick={() => requestSort('attendant')}
                                                className="flex items-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 group"
                                            >
                                                Attended By {getSortIcon('attendant')}
                                            </button>
                                        </th>
                                        <th className="w-24 px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
                                    {sortedTickets.length > 0 ? sortedTickets.map((ticket, idx) => (
                                        <Fragment key={idx}>
                                            <tr
                                                className={`group hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-all duration-300 cursor-pointer ${expandedId === ticket.id ? 'bg-slate-50/80 dark:bg-slate-800/80' : ''}`}
                                                onClick={() => toggleExpand(ticket.id)}
                                            >
                                                <td className="px-6 py-4" onClick={(e) => e.stopPropagation()}>
                                                    <input
                                                        type="checkbox"
                                                        className="rounded-lg border-slate-300 dark:border-slate-700 text-[#FF2D20] focus:ring-[#FF2D20] dark:bg-slate-800 transition-colors cursor-pointer"
                                                        checked={selectedIds.includes(ticket.id)}
                                                        onChange={() => toggleSelect(ticket.id)}
                                                    />
                                                </td>
                                                <td className="px-6 py-4 text-sm font-medium text-slate-500 group-hover:text-[#FF2D20] transition-colors">#{ticket.id.substring(0, 8)}</td>
                                                <td className="px-6 py-4">
                                                    <div className="text-sm font-bold text-slate-900 dark:text-white group-hover:translate-x-1 transition-transform duration-300">{subjects.find(sb => sb.value === ticket.subject).name}</div>
                                                    <div className="text-xs text-slate-500 dark:text-slate-400 truncate max-w-xs">{ticket.content}</div>
                                                </td>
                                                {auth.user.role === 'admin' && (
                                                    <td className="px-6 py-4">
                                                        <div className="text-sm font-medium text-slate-900 dark:text-white">{ticket.user?.name}</div>
                                                        <div className="text-xs text-slate-500 dark:text-slate-400">{ticket.user?.email}</div>
                                                    </td>
                                                )}
                                                <td className="px-6 py-4">
                                                    <span className={`inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider ${
                                                        ticket.priority === 'high' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' :
                                                        ticket.priority === 'medium' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' :
                                                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                                    }`}>
                                                        {ticket.priority === 'high' && (
                                                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        )}
                                                        {ticket.priority === 'medium' && (
                                                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                        )}
                                                        {ticket.priority === 'low' && (
                                                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                                        )}
                                                        <span>{ticket.priority}</span>
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4" onClick={(e) => e.stopPropagation()}>
                                                    {auth.user.role === 'admin' ? (
                                                        <select
                                                            value={ticket.status}
                                                            onChange={(e) => handleStatusUpdate(ticket.id, e.target.value)}
                                                            className={`text-xs font-bold rounded-xl border-none focus:ring-2 focus:ring-[#FF2D20] cursor-pointer py-1.5 px-3 transition-all ${
                                                                ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                                                ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400' :
                                                                'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                                            }`}
                                                        >
                                                            <option value="open">Open</option>
                                                            <option value="in-progress">In-Progress</option>
                                                            <option value="closed">Closed</option>
                                                        </select>
                                                    ) : (
                                                        <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase ${
                                                            ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 ring-4 ring-emerald-500/10' :
                                                            ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 ring-4 ring-orange-500/10' :
                                                            'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                                        }`}>
                                                            {ticket.status.replace('-', ' ')}
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {ticket.attendant ? (
                                                        <div className="flex items-center space-x-2">
                                                            <div className="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                                {ticket.attendant.name.charAt(0)}
                                                            </div>
                                                            <span className="text-xs font-medium text-slate-900 dark:text-white">{ticket.attendant.name}</span>
                                                        </div>
                                                    ) : (
                                                        <span className="text-[10px] italic text-slate-400 uppercase tracking-widest">Unassigned</span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 text-right">
                                                    <div className="flex items-center justify-end space-x-1">
                                                        {auth.user.id === ticket.user_id && ticket.status !== 'closed' && (
                                                            <button
                                                                onClick={(e) => { e.stopPropagation(); openEditModal(ticket); }}
                                                                className="p-2 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm"
                                                                title="Edit Ticket"
                                                            >
                                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            </button>
                                                        )}
                                                        <button
                                                            onClick={(e) => { e.stopPropagation(); toggleExpand(ticket.id); }}
                                                            className={`p-2 rounded-lg transition-all ${expandedId === ticket.id ? 'bg-[#FF2D20] text-white rotate-180' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-[#FF2D20]'}`}
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"/></svg>
                                                        </button>
                                                        {auth.user.role === 'admin' && (
                                                            <button
                                                                onClick={(e) => { e.stopPropagation(); handleDelete(ticket.id); }}
                                                                className="p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                                                disabled={processing}
                                                            >
                                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        )}
                                                    </div>
                                                </td>
                                            </tr>
                                            {expandedId === ticket.id && (
                                                <tr className="bg-slate-50/50 dark:bg-slate-800/30 animate-in slide-in-from-top-2 duration-300">
                                                    <td colSpan={auth.user.role === 'admin' ? 8 : 7} className="px-12 py-8 border-l-4 border-[#FF2D20]">
                                                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                                            <div className="space-y-8">
                                                                <div>
                                                                    <h4 className="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center">
                                                                        <svg className="w-5 h-5 mr-2 text-[#FF2D20]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                        Ticket Details
                                                                    </h4>
                                                                    <div className="p-8 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                                                                        <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Subject</div>
                                                                        <div className="text-xl text-slate-900 dark:text-white font-bold mb-6">{ticket.subject}</div>

                                                                        <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Description</div>
                                                                        <div className="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-sm">{ticket.content}</div>
                                                                    </div>
                                                                </div>

                                                                {(ticket.images?.length > 0 || ticket.filename) && (
                                                                    <div>
                                                                        <h4 className="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-widest flex items-center">
                                                                            <svg className="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                            Attachments
                                                                        </h4>
                                                                        <div className="flex flex-wrap gap-4">
                                                                            {ticket.filename && (
                                                                                <a href={`/storage/${ticket.filename}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-slate-800 shadow-md">
                                                                                    <img src={`/storage/${ticket.filename}`} className="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                    <div className="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                                    </div>
                                                                                </a>
                                                                            )}
                                                                            {ticket.images?.map((img, i) => (
                                                                                <a key={i} href={`/storage/${img}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-slate-800 shadow-md">
                                                                                    <img src={`/storage/${img}`} className="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                    <div className="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                        <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                                    </div>
                                                                                </a>
                                                                            ))}
                                                                        </div>
                                                                    </div>
                                                                )}
                                                            </div>

                                                            <div className="space-y-8">
                                                                <div>
                                                                    <h4 className="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center">
                                                                        <svg className="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                                                        Conversation
                                                                    </h4>

                                                                    <div className="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar mb-6">
                                                                        {ticket.comments?.length > 0 ? ticket.comments.map((comment, ci) => (
                                                                            <div key={ci} className={`flex flex-col ${comment.user_id === auth.user.id ? 'items-end' : 'items-start'}`}>
                                                                                <div className={`max-w-[85%] p-4 rounded-2xl ${comment.user_id === auth.user.id ? 'bg-[#FF2D20] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white'}`}>
                                                                                    <div className="flex items-center space-x-2 mb-1">
                                                                                        <span className="text-[10px] font-black uppercase opacity-70">{comment.user?.name}</span>
                                                                                        <span className="text-[10px] opacity-50">{new Date(comment.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                                                                    </div>
                                                                                    <div className="text-sm">{comment.content}</div>
                                                                                    {comment.images?.length > 0 && (
                                                                                        <div className="flex flex-wrap gap-2 mt-3">
                                                                                            {comment.images.map((cimg, cii) => (
                                                                                                <a key={cii} href={`/storage/${cimg}`} target="_blank" className="w-16 h-16 rounded-lg overflow-hidden border border-white/20">
                                                                                                    <img src={`/storage/${cimg}`} className="w-full h-full object-cover" />
                                                                                                </a>
                                                                                            ))}
                                                                                        </div>
                                                                                    )}
                                                                                </div>
                                                                            </div>
                                                                        )) : (
                                                                            <div className="text-center py-8 opacity-40 italic text-sm">No comments yet. Start the conversation.</div>
                                                                        )}
                                                                    </div>

                                                                    {(auth.user.role === 'admin' || auth.user.id === ticket.user_id) && (
                                                                        <form onSubmit={(e) => handleCommentSubmit(e, ticket.id)} className="space-y-4">
                                                                            {console.log(auth.user.id, ticket.user_id)}

                                                                            <div className="relative group/comment">
                                                                                <textarea
                                                                                    value={commentForm.data.content}
                                                                                    onChange={e => commentForm.setData('content', e.target.value)}
                                                                                    placeholder="Type your message..."
                                                                                    rows="3"
                                                                                    className="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all resize-none shadow-inner"
                                                                                ></textarea>

                                                                                <div className="absolute bottom-4 right-4 flex items-center space-x-2">
                                                                                    <input
                                                                                        type="file"
                                                                                        id={`comment-images-${ticket.id}`}
                                                                                        className="hidden"
                                                                                        multiple
                                                                                        accept="image/*"
                                                                                        onChange={(e) => {
                                                                                            const files = Array.from(e.target.files);
                                                                                            commentForm.setData('images', files);
                                                                                            setCommentPreviewUrls(files.map(f => URL.createObjectURL(f)));
                                                                                        }}
                                                                                    />
                                                                                    <label htmlFor={`comment-images-${ticket.id}`} className="p-2 text-slate-400 hover:text-[#FF2D20] hover:bg-[#FF2D20]/10 rounded-xl cursor-pointer transition-all">
                                                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                                    </label>
                                                                                    <button
                                                                                        type="submit"
                                                                                        disabled={commentForm.processing || !commentForm.data.content.trim()}
                                                                                        className="p-2 bg-[#FF2D20] text-white rounded-xl shadow-lg shadow-[#FF2D20]/20 hover:scale-110 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100"
                                                                                    >
                                                                                        Send
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            {commentPreviewUrls.length > 0 && (
                                                                                <div className="flex flex-wrap gap-2 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                                                                    {commentPreviewUrls.map((url, i) => (
                                                                                        <div key={i} className="relative group/cp">
                                                                                            <img src={url} className="w-12 h-12 rounded-lg object-cover border-2 border-white dark:border-slate-800 shadow-md" />
                                                                                            <button
                                                                                                type="button"
                                                                                                onClick={() => {
                                                                                                    const nf = [...commentForm.data.images]; nf.splice(i, 1); commentForm.setData('images', nf);
                                                                                                    const nu = [...commentPreviewUrls]; nu.splice(i, 1); setCommentPreviewUrls(nu);
                                                                                                }}
                                                                                                className="absolute -top-1 -right-1 bg-red-500 text-white p-0.5 rounded-full opacity-0 group-hover/cp:opacity-100 transition-opacity"
                                                                                            >
                                                                                                <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    ))}
                                                                                </div>
                                                                            )}
                                                                        </form>
                                                                    )}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            )}
                                        </Fragment>
                                    )) : (
                                        <tr>
                                            <td colSpan={auth.user.role === 'admin' ? 8 : 7} className="px-6 py-20 text-center">
                                                <div className="flex flex-col items-center">
                                                    <div className="p-4 bg-slate-100 dark:bg-slate-800 rounded-3xl mb-4">
                                                        <svg className="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                    <h3 className="text-lg font-bold text-slate-900 dark:text-white">No tickets found</h3>
                                                    <p className="text-slate-600 dark:text-slate-400">There are no tickets to display at this time.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer className="relative z-10 w-full px-6 py-8 border-t border-slate-200 dark:border-slate-800">
                <div className="max-w-7xl mx-auto text-center">
                    <p className="text-sm text-slate-500 dark:text-slate-500">
                        &copy; {new Date().getFullYear()} {import.meta.env.VITE_APP_NAME || 'DrugTick'}. All rights reserved.
                    </p>
                </div>
            </footer>

            {/* Edit Ticket Modal */}
            {editingTicket && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
                    <div className="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 p-8 transform animate-in zoom-in-95 slide-in-from-bottom-4 duration-300">
                        <button
                            onClick={closeEditModal}
                            className="absolute top-6 right-6 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-6">Edit Ticket #{editingTicket.id.substring(0, 8)}</h2>

                        <form onSubmit={handleEditSubmit} className="space-y-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div className="space-y-2">
                                    <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Subject</label>
                                    <input
                                        type="text"
                                        value={editForm.data.subject}
                                        onChange={e => editForm.setData('subject', e.target.value)}
                                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all"
                                    />
                                    {editForm.errors.subject && <p className="text-rose-500 text-xs">{editForm.errors.subject}</p>}
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Priority</label>
                                    <select
                                        value={editForm.data.priority}
                                        onChange={e => editForm.setData('priority', e.target.value)}
                                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all"
                                    >
                                        <option value="low">⬇️ Low</option>
                                        <option value="medium">⚡ Medium</option>
                                        <option value="high">🚩 High</option>
                                    </select>
                                    {editForm.errors.priority && <p className="text-rose-500 text-xs">{editForm.errors.priority}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Content</label>
                                <textarea
                                    value={editForm.data.content}
                                    onChange={e => editForm.setData('content', e.target.value)}
                                    rows="4"
                                    className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all resize-none"
                                ></textarea>
                                {editForm.errors.content && <p className="text-rose-500 text-xs">{editForm.errors.content}</p>}
                            </div>

                            <div className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Attachments</label>
                                    <input
                                        type="file"
                                        id="edit-images"
                                        className="hidden"
                                        onChange={handleImagesChange}
                                        accept="image/*"
                                        multiple
                                    />
                                    <label
                                        htmlFor="edit-images"
                                        className="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-[#FF2D20] cursor-pointer transition-all border border-slate-200 dark:border-slate-700"
                                    >
                                        Add Images
                                    </label>
                                </div>

                                {previewUrls.length > 0 && (
                                    <div className="flex flex-wrap gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800">
                                        {previewUrls.map((url, idx) => (
                                            <div key={idx} className="relative group/edit-preview">
                                                <img src={url} className="w-20 h-20 rounded-xl object-cover border-2 border-white dark:border-slate-800 shadow-sm" />
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        const nf = [...editForm.data.images]; nf.splice(idx, 1); editForm.setData('images', nf);
                                                        const nu = [...previewUrls]; nu.splice(idx, 1); setPreviewUrls(nu);
                                                    }}
                                                    className="absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover/edit-preview:opacity-100 transition-opacity shadow-lg"
                                                >
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>

                            <div className="flex space-x-4 pt-4">
                                <button
                                    type="button"
                                    onClick={closeEditModal}
                                    className="flex-1 py-4 px-6 rounded-2xl bg-slate-100 items-center dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={editForm.processing}
                                    className="flex-[2] py-4 px-6 rounded-2xl bg-[#FF2D20] text-white font-bold shadow-xl shadow-[#FF2D20]/20 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50"
                                >
                                    {editForm.processing ? 'Saving...' : 'Update Ticket'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
