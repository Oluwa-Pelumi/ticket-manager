import { useTheme } from '@/Contexts/ThemeContext';
import { useAlert } from '@/Contexts/AlertContext';
import { useState, useMemo, Fragment } from 'react';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

// Categories will be passed from the backend



export default function Dashboard({ auth, tickets, categories = [] }) {
    const { theme, toggleTheme }                     = useTheme();
    const { showAlert, showConfirm }                 = useAlert();
    const [selectedIds, setSelectedIds]              = useState([]);
    const [expandedId, setExpandedId]                = useState(null);
    const [filters, setFilters]                      = useState({
        status  : '',
        subject : '',
        priority: '',
    });
    const [copiedId, setCopiedId]                    = useState(null);
    // Editing State
    const [editingTicket, setEditingTicket]          = useState(null);
    const [previewUrls, setPreviewUrls]              = useState([]);
    const editForm                                   = useForm({
        subject: '',
        category_id: '',
        content: '',
        priority: 'medium',
        images  : [],
    });
    const commentForm                                 = useForm({
        content: '',
        images : [],
    });
    const [commentPreviewUrls, setCommentPreviewUrls] = useState([]);
    const [sortConfig, setSortConfig]                 = useState({ key: 'id', direction: 'desc' });

    const handleCopy = async (id) => {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(id);
            } else {
                // Fallback for non-secure contexts
                const textArea                = document.createElement("textarea");
                      textArea.value          = id;
                      textArea.style.position = "fixed";
                      textArea.style.left     = "-9999px";
                      textArea.style.top      = "0";

                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }

            setCopiedId(id);
            showAlert(`Ticket ID #${id.substring(0, 8)} copied to clipboard!`, 'success');
            setTimeout(() => setCopiedId(null), 2000);
        } catch (err) {
            console.error('Failed to copy: ', err);
            showAlert('Failed to copy Ticket ID', 'error');
        }
    };

    const openEditModal = (ticket) => {
        setEditingTicket(ticket);
        editForm.setData({
            images  : [],
            subject : ticket.subject,
            category_id: ticket.category_id || '',
            content : ticket.content,
            priority: ticket.priority,
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
            _method       : 'patch',                  // multipart/form-data doesn't support PATCH natively in some setups, we can spoof it
            forceFormData : true,
            preserveScroll: true,
            onSuccess     : () => closeEditModal(),
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
            const matchesStatus   = !filters.status   || ticket.status   === filters.status;
            const matchesPriority = !filters.priority || ticket.priority === filters.priority;
            const matchesSubject  = !filters.subject  || 
                                   (ticket.category?.slug === filters.subject) || 
                                   (ticket.subject === filters.subject);
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
            ? <svg className="w-3 h-3 ml-1 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7"/></svg>
            : <svg className="w-3 h-3 ml-1 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7"/></svg>;
    };

    const { patch, delete: destroy, processing } = useForm({});

    const handleStatusUpdate = (id, newStatus) => {
        patch(route('update-ticket-status', { id, status: newStatus }), {
            preserveScroll: true,
            onSuccess     : () => setSelectedIds([]),
        });
    };

    const handleDelete = async (id) => {
        const confirmed = await showConfirm({
            type       : 'danger',
            title      : 'Delete Ticket',
            confirmText: 'Delete Ticket',
            message    : 'Are you sure you want to delete this ticket? This action cannot be undone.',
        });

        if (confirmed) {
            destroy(route('delete-ticket', { id }), {
                preserveScroll: true,
                onSuccess     : () => setSelectedIds([]),
            });
        }
    };

    const handleBulkDelete = async () => {
        const confirmed = await showConfirm({
            type       : 'danger',
            title      : 'Bulk Delete',
            confirmText: `Delete ${selectedIds.length} Tickets`,
            message    : `Are you sure you want to delete ${selectedIds.length} tickets? This action cannot be undone.`,
        });

        if (confirmed) {
            destroy(route('bulk-delete-tickets', { ids: selectedIds }), {
                preserveScroll: true,
                onSuccess     : () => setSelectedIds([]),
            });
        }
    };

    const handleBulkStatusChange = (status) => {
        patch(route('bulk-update-ticket-status', { ids: selectedIds, status }), {
            preserveScroll: true,
            onSuccess     : () => setSelectedIds([]),
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
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                        <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div className="flex flex-col">
                        <h2 className="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            Command Center
                        </h2>
                        <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">Overview & Management</span>
                    </div>
                    {selectedIds.length > 0 && (
                        <div className="ml-auto flex items-center px-4 py-2 rounded-xl bg-teal-900 text-white text-[10px] font-black tracking-widest shadow-lg animate-in zoom-in duration-300">
                            Selected: {selectedIds.length} tickets
                        </div>
                    )}
                </div>
            }
        >
            <Head title="Dashboard" />

            <div className="max-w-7xl mx-auto py-12 px-6">

                <div className="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8">
                    <div>
                        <h1 className="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">Ticket Management</h1>
                        <p className="text-sm md:text-base text-slate-600 dark:text-slate-400 mt-1">
                            {auth.user.role === 'admin' ? 'Manage and resolve tickets from all users.' : 'Track and manage your submitted tickets.'}
                        </p>
                    </div>
                    <div className="flex w-full md:w-auto items-center justify-between md:justify-end gap-4">
                        {selectedIds.length > 0 && auth.user.role === 'admin' && (
                            <div className="flex items-center space-x-2 p-1.5 md:p-2 bg-slate-100 dark:bg-[#102824] rounded-2xl border border-emerald-900/10 dark:border-[#1d3a34] animate-in fade-in zoom-in duration-300">
                                <span className="hidden sm:inline text-xs font-bold text-slate-600 dark:text-slate-400 px-2">{selectedIds.length} Selected</span>
                                <select
                                    onChange={(e) => handleBulkStatusChange(e.target.value)}
                                    className="text-[10px] md:text-xs font-black bg-white dark:bg-[#18342f] text-slate-600 dark:text-slate-300 border-none rounded-xl focus:ring-2 focus:ring-lime-500 py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10"
                                    defaultValue=""
                                >
                                    <option value="" disabled>Status</option>
                                    <option value="open">Open</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <button
                                    onClick={handleBulkDelete}
                                    className="p-1.5 md:p-2 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all"
                                    title="Delete Selected"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        )}
                        {auth.user.role !== 'admin' && (
                            <Link href={route('submit-ticket')} className="w-full md:w-auto text-center px-6 py-3 bg-teal-900 text-white rounded-2xl font-black text-xs tracking-widest shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-105 active:scale-95 transition-all">
                                Submit Ticket
                            </Link>
                        )}
                    </div>
                </div>

                <FlashHandler />

                {/* Filter Bar */}
                <div className="flex flex-wrap items-center gap-3 md:gap-4 mb-6 p-4 rounded-2xl bg-white/50 dark:bg-[#102824]/70 backdrop-blur-md border border-emerald-900/10/50 dark:border-[#1d3a34]">
                    <div className="flex items-center space-x-2 w-full sm:w-auto">
                        <svg className="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span className="text-sm font-bold text-slate-600 dark:text-slate-400">Filters:</span>
                    </div>

                    <div className="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3 w-full sm:w-auto">
                        <select
                            value={filters.status}
                            onChange={(e) => setFilters(prev => ({ ...prev, status: e.target.value }))}
                            className="bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-lime-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Statuses</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In-Progress</option>
                            <option value="closed">Closed</option>
                        </select>

                        <select
                            value={filters.priority}
                            onChange={(e) => setFilters(prev => ({ ...prev, priority: e.target.value }))}
                            className="bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-medium text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-lime-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>

                        <select
                            value={filters.subject}
                            onChange={(e) => setFilters(prev => ({ ...prev, subject: e.target.value }))}
                            className="col-span-2 sm:col-span-1 bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#28524a] rounded-xl pl-3 pr-8 py-2 text-[10px] md:text-xs font-medium text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-lime-500 outline-none transition-all cursor-pointer"
                        >
                            <option value="">All Subjects</option>
                            {categories.map((sub, idx) => <option key={idx} value={sub.slug}>{sub.name}</option>)}
                        </select>

                        {(filters.status || filters.priority || filters.subject) && (
                            <button
                                onClick={clearFilters}
                                className="col-span-2 sm:col-span-1 text-[10px] md:text-xs font-bold text-rose-500 hover:text-rose-600 px-3 py-2 rounded-xl hover:bg-rose-500/10 transition-all flex items-center justify-center"
                            >
                                <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear All
                            </button>
                        )}
                    </div>

                    <div className="w-full sm:w-auto sm:ml-auto text-right text-[10px] font-black tracking-[0.2em] text-slate-400">
                        Showing {filteredTickets.length} of {tickets.length}
                    </div>
                </div>

                <div className="relative group overflow-hidden rounded-[2.5rem] fauna-panel transition-all duration-500 hover:shadow-lime-500/10">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left table-fixed">
                            <thead>
                                <tr className="border-b border-emerald-900/10 dark:border-[#1d3a34]">
                                    <th className="w-12 md:w-16 px-4 md:px-6 py-4">
                                        <input
                                            type="checkbox"
                                            className="rounded-lg border-emerald-900/20 dark:border-[#1d3a34] text-teal-900 focus:ring-lime-500 dark:bg-[#18342f] transition-colors cursor-pointer"
                                            checked={tickets.length > 0 && selectedIds.length === tickets.length}
                                            onChange={toggleSelectAll}
                                        />
                                    </th>
                                    <th className="w-20 md:w-24 px-4 md:px-6 py-4">
                                        <button
                                            onClick={() => requestSort('id')}
                                            className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                        >
                                            ID {getSortIcon('id')}
                                        </button>
                                    </th>
                                    <th className="px-4 md:px-6 py-4">
                                        <button
                                            onClick={() => requestSort('subject')}
                                            className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                        >
                                            Info {getSortIcon('subject')}
                                        </button>
                                    </th>
                                    {auth.user.role === 'admin' && (
                                        <th className="hidden lg:table-cell px-6 py-4">
                                            <button
                                                onClick={() => requestSort('user')}
                                                className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                            >
                                                User {getSortIcon('user')}
                                            </button>
                                        </th>
                                    )}
                                    <th className="hidden sm:table-cell w-32 px-6 py-4">
                                        <button
                                            onClick={() => requestSort('priority')}
                                            className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                        >
                                            Priority {getSortIcon('priority')}
                                        </button>
                                    </th>
                                    <th className="w-32 md:w-48 px-4 md:px-6 py-4">
                                        <button
                                            onClick={() => requestSort('status')}
                                            className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                        >
                                            Status {getSortIcon('status')}
                                        </button>
                                    </th>
                                    <th className="hidden lg:table-cell w-48 px-6 py-4">
                                        <button
                                            onClick={() => requestSort('attendant')}
                                            className="flex items-center text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 group"
                                        >
                                            Attendant {getSortIcon('attendant')}
                                        </button>
                                    </th>
                                    <th className="w-20 md:w-24 px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
                                {sortedTickets.length > 0 ? sortedTickets.map((ticket, idx) => (
                                    <Fragment key={idx}>
                                        <tr
                                            className={`group hover:bg-emerald-50/50 dark:hover:bg-[#18342f]/70 transition-all duration-300 cursor-pointer ${expandedId === ticket.id ? 'bg-emerald-50/50/80 dark:bg-[#18342f]/80' : ''}`}
                                            onClick={() => toggleExpand(ticket.id)}
                                        >
                                            <td className="px-4 md:px-6 py-4" onClick={(e) => e.stopPropagation()}>
                                                <input
                                                    type="checkbox"
                                                    className="rounded-lg border-emerald-900/20 dark:border-[#1d3a34] text-teal-900 focus:ring-lime-500 dark:bg-[#18342f] transition-colors cursor-pointer"
                                                    checked={selectedIds.includes(ticket.id)}
                                                    onChange={() => toggleSelect(ticket.id)}
                                                />
                                            </td>
                                            <td className="px-4 md:px-6 py-4" onClick={(e) => e.stopPropagation()}>
                                                <div className="flex items-center gap-2 group/id-cell">
                                                    <span className="text-[10px] md:text-sm font-bold text-slate-600 group-hover:text-teal-900 dark:group-hover:text-lime-400 transition-colors tracking-tight">#{ticket.id.substring(0, 4)}...</span>
                                                </div>
                                            </td>
                                            <td className="px-4 md:px-6 py-4">
                                                <div className="text-[11px] md:text-sm font-bold text-slate-900 dark:text-white group-hover:translate-x-1 transition-transform duration-300 line-clamp-1">
                                                    {ticket.category?.name || ticket.subject.replace(/_/g, ' ')}
                                                </div>
                                                <div className="text-[10px] text-slate-600 dark:text-slate-400 truncate max-w-[80px] md:max-w-xs">{ticket.content}</div>
                                            </td>
                                            {auth.user.role === 'admin' && (
                                                <td className="hidden lg:table-cell px-6 py-4">
                                                    <div className="text-sm font-medium text-slate-900 dark:text-white">{ticket.name || ticket.user?.name}</div>
                                                    <div className="text-xs text-slate-600 dark:text-slate-400">{ticket.email || ticket.user?.email}</div>
                                                </td>
                                            )}
                                            <td className="hidden sm:table-cell px-6 py-4">
                                                <span className={`inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider ${
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
                                                    <span className="hidden md:inline">{ticket.priority}</span>
                                                </span>
                                            </td>
                                            <td className="px-4 md:px-6 py-4" onClick={(e) => e.stopPropagation()}>
                                                {auth.user.role === 'admin' ? (
                                                    <select
                                                        value={ticket.status}
                                                        onChange={(e) => handleStatusUpdate(ticket.id, e.target.value)}
                                                        className={`text-[10px] md:text-xs font-black tracking-widest rounded-xl border-none focus:ring-2 focus:ring-lime-500 cursor-pointer py-1 md:py-2 pl-2 pr-8 md:pl-4 md:pr-10 transition-all ${
                                                            ticket.status === 'open' ? 'bg-teal-900 text-white shadow-lg' :
                                                            ticket.status === 'in-progress' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20' :
                                                            'bg-slate-200 dark:bg-[#18342f] text-slate-600 dark:text-slate-400'
                                                        }`}
                                                    >
                                                        <option value="open">Open</option>
                                                        <option value="in-progress">Processing</option>
                                                        <option value="closed">Resolved</option>
                                                    </select>
                                                ) : (
                                                    <span className={`inline-flex items-center px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold ${
                                                        ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 ring-4 ring-emerald-500/10' :
                                                        ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 ring-4 ring-orange-500/10' :
                                                        'bg-slate-100 text-slate-600 dark:bg-[#18342f] dark:text-slate-400'
                                                    }`}>
                                                        {ticket.status.replace('-', ' ')}
                                                    </span>
                                                )}
                                            </td>
                                            <td className="hidden lg:table-cell px-6 py-4">
                                                {ticket.attendant ? (
                                                    <div className="flex items-center space-x-2">
                                                        <div className="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                            {ticket.attendant.name.charAt(0)}
                                                        </div>
                                                        <span className="text-xs font-medium text-slate-900 dark:text-white">{ticket.attendant.name}</span>
                                                    </div>
                                                ) : (
                                                    <span className="text-[10px] italic text-slate-400 tracking-widest">Unassigned</span>
                                                )}
                                            </td>
                                            <td className="px-4 md:px-6 py-4 text-right">
                                                <div className="flex items-center justify-end space-x-1 md:space-x-2">
                                                    {auth.user.id === ticket.user_id && ticket.status !== 'closed' && (
                                                        <button
                                                            onClick={(e) => { e.stopPropagation(); openEditModal(ticket); }}
                                                            className="p-1.5 md:p-2 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm"
                                                            title="Edit Ticket"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                    )}
                                                    <button
                                                        onClick={(e) => { e.stopPropagation(); toggleExpand(ticket.id); }}
                                                    className={`p-1.5 md:p-2 rounded-lg transition-all ${expandedId === ticket.id ? 'bg-teal-900 text-white rotate-180' : 'bg-slate-100 dark:bg-[#18342f] text-slate-600 hover:text-teal-900 dark:hover:text-lime-400'}`}
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                    {auth.user.role === 'admin' && (
                                                        <button
                                                            onClick={(e) => { e.stopPropagation(); handleDelete(ticket.id); }}
                                                            className="p-1.5 md:p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                                            disabled={processing}
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                        {expandedId === ticket.id && (
                                            <tr className="bg-emerald-50/50 dark:bg-[#18342f]/30 animate-in slide-in-from-top-2 duration-300">
                                                <td colSpan={auth.user.role === 'admin' ? 8 : 7} className="px-4 md:px-12 py-6 md:py-8 border-l-4 border-lime-500">
                                                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                                        <div className="space-y-8">
                                                            <div>
                                                                <h4 className="text-xl font-black text-slate-900 dark:text-white mb-4 flex items-center tracking-tight">
                                                                    <svg className="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                    Specifications
                                                                </h4>

                                                                <div className="p-8 rounded-[2.5rem] bg-white dark:bg-[#102824] border border-emerald-900/10 dark:border-[#1d3a34] shadow-sm relative overflow-hidden">
                                                                    <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-30" />
                                                                    <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.3em]">Control Reference</div>
                                                                    <div className="flex items-center gap-3 mb-8 group/id">
                                                                        <div className="text-2xl text-slate-900 dark:text-white font-black tracking-tight">{ticket.id}</div>
                                                <button
                                                    onClick={(e) => { e.stopPropagation(); handleCopy(ticket.id); }}
                                                    className="flex items-center gap-2 px-2 py-1 rounded-lg bg-slate-100 dark:bg-[#18342f] text-slate-600 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20"
                                                    title="Copy ID"
                                                >
                                                                            {copiedId === ticket.id ? (
                                                                                <>
                                                                                    <svg className="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                                                                                    <span className="text-[10px] font-bold text-emerald-500 tracking-wider">Copied!</span>
                                                                                </>
                                                                            ) : (
                                                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                                                            )}
                                                                        </button>
                                                                    </div>

                                                                    <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Subject</div>
                                                                    <div className="text-xl text-slate-900 dark:text-white font-bold mb-6">{ticket.category?.name || ticket.subject.replace(/_/g, ' ')}</div>

                                                                    <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Description</div>
                                                                    <div className="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-sm">{ticket.content}</div>

                                                                    {ticket.order_type && (
                                                                        <div className="mt-8 pt-8 border-t border-slate-100 dark:border-[#1d3a34]/50">
                                                                            <h4 className="text-xs font-black text-teal-900 dark:text-lime-400 mb-4 tracking-[0.2em] uppercase">Order Configuration</h4>
                                                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                                                <div>
                                                                                    <div className="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">Frequency</div>
                                                                                    <div className="text-sm font-bold text-slate-900 dark:text-white capitalize">{ticket.order_type.replace('-', ' ')}</div>
                                                                                </div>
                                                                                {ticket.order_type === 'recurrent' && (
                                                                                    <div>
                                                                                        <div className="text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest">Interval / Period</div>
                                                                                        <div className="text-sm font-bold text-slate-900 dark:text-white capitalize">
                                                                                            {ticket.recurrence_period === 'custom' 
                                                                                                ? `Custom: ${ticket.custom_recurrence_date}` 
                                                                                                : ticket.recurrence_period.replace('-', ' ')}
                                                                                        </div>
                                                                                    </div>
                                                                                )}
                                                                            </div>
                                                                        </div>
                                                                    )}
                                                                </div>
                                                            </div>

                                                            {(ticket.images?.length > 0 || ticket.filename) && (
                                                                <div>
                                                                    <h4 className="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center">
                                                                        <svg className="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                        Attachments
                                                                    </h4>
                                                                    <div className="flex flex-wrap gap-4">
                                                                        {ticket.filename && (
                                                                            <a href={`/storage/${ticket.filename}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
                                                                                <img src={`/storage/${ticket.filename}`} className="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                                                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                                </div>
                                                                            </a>
                                                                        )}
                                                                        {ticket.images?.map((img, i) => (
                                                                            <a key={i} href={`/storage/${img}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-[#1d3a34] shadow-md">
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
                                                                            <div className={`max-w-[85%] p-5 rounded-[2rem] ${comment.user_id === auth.user.id ? 'bg-teal-900 text-white shadow-xl' : 'fauna-panel text-slate-900 dark:text-white'}`}>
                                                                                <div className="flex items-center space-x-3 mb-2">
                                                                                    <span className="text-[9px] font-black tracking-widest opacity-80">{comment.user?.name}</span>
                                                                                    <span className="text-[9px] opacity-40">{new Date(comment.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                                                                                </div>
                                                                                <div className="text-sm font-medium leading-relaxed">{comment.content}</div>
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
                                                                            <div className="relative group/comment">
                                                                                <textarea
                                                                                    value={commentForm.data.content}
                                                                                    onChange={e => commentForm.setData('content', e.target.value)}
                                                                                    placeholder="Secure communication channel..."
                                                                                    rows="4"
                                                                                    className="w-full px-6 py-5 rounded-[2rem] bg-slate-100 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all resize-none shadow-inner font-medium"
                                                                                ></textarea>

                                                                                <div className="absolute bottom-5 right-5 flex items-center space-x-3">
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
                                                                                    <label htmlFor={`comment-images-${ticket.id}`} className="p-3 text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 hover:bg-lime-500/10 rounded-2xl cursor-pointer transition-all">
                                                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                                    </label>
                                                                                    <button
                                                                                        type="submit"
                                                                                        disabled={commentForm.processing || !commentForm.data.content.trim()}
                                                                                        className="px-6 py-3 bg-teal-900 text-white rounded-2xl shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-110 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100 font-black text-xs tracking-widest"
                                                                                    >
                                                                                        {commentForm.processing ? 'Sending...' : 'Send'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                        {commentPreviewUrls.length > 0 && (
                                                                            <div className="flex flex-wrap gap-2 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                                                                {commentPreviewUrls.map((url, i) => (
                                                                                    <div key={i} className="relative group/cp">
                                                                                        <img src={url} className="w-12 h-12 rounded-lg object-cover border-2 border-white dark:border-[#1d3a34] shadow-md" />
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
                                                <div className="p-4 bg-slate-100 dark:bg-[#18342f] rounded-3xl mb-4">
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

            {/* Edit Ticket Modal */}
            {editingTicket && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
                    <div className="relative w-full max-w-2xl bg-white dark:bg-[#102824] rounded-3xl shadow-2xl border border-emerald-900/10 dark:border-[#1d3a34] p-8 transform animate-in zoom-in-95 slide-in-from-bottom-4 duration-300">
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
                                    <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Subject / Category</label>
                                    <select
                                        value={editForm.data.category_id || editForm.data.subject}
                                        onChange={e => {
                                            const category = categories.find(c => c.id == e.target.value);
                                            if (category) {
                                                editForm.setData({
                                                    ...editForm.data,
                                                    category_id: category.id,
                                                    subject: category.name
                                                });
                                            } else {
                                                editForm.setData('subject', e.target.value);
                                            }
                                        }}
                                        className="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all"
                                    >
                                        <option value="" disabled>Select Category</option>
                                        {categories.map((cat) => (
                                            <option key={cat.id} value={cat.id}>{cat.name}</option>
                                        ))}
                                    </select>
                                    {editForm.errors.subject && <p className="text-rose-500 text-xs">{editForm.errors.subject}</p>}
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-semibold text-slate-700 dark:text-slate-300">Priority</label>
                                    <select
                                        value={editForm.data.priority}
                                        onChange={e => editForm.setData('priority', e.target.value)}
                                        className="w-full pl-4 pr-10 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all"
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
                                    className="w-full px-4 py-3 rounded-xl bg-emerald-50/50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all resize-none"
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
                                        className="px-4 py-2 rounded-xl bg-slate-100 dark:bg-[#18342f] text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 cursor-pointer transition-all border border-emerald-900/10 dark:border-[#1d3a34]"
                                    >
                                        Add Images
                                    </label>
                                </div>

                                {previewUrls.length > 0 && (
                                    <div className="flex flex-wrap gap-4 p-4 rounded-2xl bg-emerald-50/50 dark:bg-[#18342f]/50 border border-emerald-900/10 dark:border-[#1d3a34]">
                                        {previewUrls.map((url, idx) => (
                                            <div key={idx} className="relative group/edit-preview">
                                                <img src={url} className="w-20 h-20 rounded-xl object-cover border-2 border-white dark:border-[#1d3a34] shadow-sm" />
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
                                    className="flex-1 py-4 px-6 rounded-2xl bg-slate-100 items-center dark:bg-[#18342f] text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={editForm.processing}
                                    className="flex-[2] py-4 px-6 rounded-2xl bg-teal-900 text-white font-black text-xs tracking-widest shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50"
                                >
                                    {editForm.processing ? 'Synchronizing Ticket...' : 'Synchronize Ticket'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}




