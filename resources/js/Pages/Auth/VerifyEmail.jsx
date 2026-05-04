import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification" />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Verify Email</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">Almost there! Check your inbox</p>
            </div>

            <div className="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-6 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                    A new verification link has been sent to the email address
                    you provided during registration.
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-teal-900 text-white font-black text-lg shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest"
                    >
                        {processing ? 'Re-verifying Identity...' : 'Re-verify Identity'}
                    </button>
                </div>

                <div className="text-center">
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="text-sm font-black tracking-widest text-slate-500 hover:text-teal-900 dark:hover:text-lime-400 transition-colors"
                    >
                        Terminate Session
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
