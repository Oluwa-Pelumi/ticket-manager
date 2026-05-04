import {useState} from 'react'
import { useAlert } from '@/Contexts/AlertContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Users({ auth, users }) {
    const { showAlert, showConfirm }  = useAlert();
    const [processing, setProcessing] = useState(false);

    const handleRoleUpdate = async (user, newRole) => {
        const confirmed = await showConfirm({
            title      : 'Update Role',
            confirmText: 'Update Role',
            message    : `Are you sure you want to change ${user.name}'s role to ${newRole}?`,
        });

        if (confirmed) {
            setProcessing(true);
            router.patch(route('admin.users.update-role', { user: user.id }), { role: newRole }, {
                preserveScroll: true,
                onFinish      : () => setProcessing(false),
            });
        }
    };

    const handleDeleteUser = async (user) => {
        const confirmed = await showConfirm({
            type       : 'danger',
            title      : 'Delete User',
            confirmText: 'Delete User',
            message    : `Are you sure you want to delete ${user.name}? This action cannot be undone.`,
        });

        if (confirmed) {
            setProcessing(true);
            router.delete(route('admin.users.destroy', { user: user.id }), {
                preserveScroll: true,
                onFinish      : () => setProcessing(false),
            });
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                        <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div className="flex flex-col">
                        <h2 className="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            Identity Portal
                        </h2>
                        <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">User Management</span>
                    </div>
                </div>
            }
        >
            <Head title="User Management" />

            <div className="max-w-7xl mx-auto py-12 px-6">

                <div className="mb-8">
                    <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Staff Management</h1>
                    <p className="text-sm text-slate-600 mt-1">Manage user accounts and roles across the platform.</p>
                </div>

                <FlashHandler />

                <div className="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#102824]/70 backdrop-blur-md border border-emerald-900/10/50 dark:border-[#1d3a34] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="border-b border-emerald-900/10 dark:border-[#1d3a34]">
                                    <th className="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">ID</th>
                                    <th className="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">User Information</th>
                                    <th className="hidden sm:table-cell px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Activity</th>
                                    <th className="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Role</th>
                                    <th className="hidden lg:table-cell px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Member Since</th>
                                    <th className="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
                                {users.map((user) => (
                                    <tr key={user.id} className="hover:bg-emerald-50/50 dark:hover:bg-[#18342f]/70 transition-colors">
                                        <td className="px-4 md:px-6 py-4 text-xs md:text-sm font-medium text-slate-900 dark:text-white">#{user.id}</td>
                                        <td className="px-4 md:px-6 py-4">
                                            <div className="text-xs md:text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{user.name}</div>
                                            <div className="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 line-clamp-1">{user.email}</div>
                                        </td>
                                        <td className="hidden sm:table-cell px-6 py-4 font-medium">
                                            <div className="flex items-center space-x-2">
                                                <span className="text-sm text-slate-900 dark:text-white">{user.tickets_count}</span>
                                                <span className="text-xs text-slate-600 dark:text-slate-400">Tickets</span>
                                            </div>
                                        </td>
                                        <td className="px-4 md:px-6 py-4">
                                            <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest ${
                                                user.role === 'admin' ? 'bg-lime-500/10 text-teal-900 dark:text-lime-400 ring-4 ring-lime-500/10' : 'bg-slate-100 text-slate-600 dark:bg-[#18342f]/60 dark:text-slate-400'
                                            }`}>
                                                {user.role}
                                            </span>
                                        </td>
                                        <td className="hidden lg:table-cell px-6 py-4 text-xs text-slate-600 dark:text-slate-400">
                                            {new Date(user.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-4 md:px-6 py-4 text-right">
                                            <div className="flex flex-col items-end gap-1 md:gap-2">
                                                <div className="flex items-center space-x-1 md:space-x-2">
                                                    <select
                                                        disabled={user.id === auth.user.id || processing}
                                                        value={user.role}
                                                        onChange={(e) => handleRoleUpdate(user, e.target.value)}
                                                        className="text-[10px] md:text-xs font-black bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] rounded-xl focus:ring-2 focus:ring-lime-500 disabled:opacity-50 transition-all cursor-pointer py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10 tracking-widest"
                                                    >
                                                        <option value="user">User</option>
                                                        <option value="admin">Admin</option>
                                                    </select>
                                                    <button
                                                        onClick={() => handleDeleteUser(user)}
                                                        disabled={user.id === auth.user.id || processing}
                                                        className="p-1.5 md:p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all disabled:opacity-50 shadow-sm"
                                                        title="Delete User"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                                {user.id === auth.user.id && (
                                                    <div className="text-[9px] md:text-[10px] text-slate-400 font-black tracking-widest">Self</div>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}




