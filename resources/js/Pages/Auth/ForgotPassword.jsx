import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import { Head, Link, useForm } from '@inertiajs/react';

export default function ForgotPassword({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('password.email'));
    };

    return (
        <GuestLayout>
            <Head title="Forgot Password" />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Recover Password</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">We'll send you a recovery link</p>
            </div>

            <div className="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            {status && (
                <div className="mb-6 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div>
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        isFocused={true}
                        placeholder="your@email.com"
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-indigo-500 text-white font-black text-lg shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all uppercase tracking-widest"
                    >
                        {processing ? 'Transmitting...' : 'Request Recovery'}
                    </button>
                </div>

                <div className="text-center pt-4">
                    <Link
                        href={route('login')}
                        className="text-sm font-black uppercase tracking-widest text-slate-500 hover:text-indigo-500 transition-colors"
                    >
                        Return to Access
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
