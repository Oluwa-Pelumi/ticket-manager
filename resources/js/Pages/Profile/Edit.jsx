import { Head } from '@inertiajs/react';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';

export default function Edit({ mustVerifyEmail, status }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-2xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 border border-white/20">
                        <svg className="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div className="flex flex-col">
                        <h2 className="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            Identity Portal
                        </h2>
                        <span className="text-[9px] font-black tracking-[0.3em] text-slate-400">Personal Management</span>
                    </div>
                </div>
            }
        >
            <Head title="Profile" />

            <div className="py-10 px-4 sm:px-0">
                <div className="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">

                    {/* Update Profile Information */}
                    <div className="glass-card p-6 sm:p-10 border-white/20 dark:border-slate-800/50">
                        <UpdateProfileInformationForm
                            mustVerifyEmail={mustVerifyEmail}
                            status={status}
                            className="max-w-xl"
                        />
                    </div>

                    {/* Update Password */}
                    <div className="glass-card p-6 sm:p-10 border-white/20 dark:border-slate-800/50">
                        <UpdatePasswordForm className="max-w-xl" />
                    </div>

                    {/* Delete Account */}
                    <div className="glass-card p-6 sm:p-10 border-rose-200/50 dark:border-rose-900/40">
                        <DeleteUserForm className="max-w-xl" />
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
