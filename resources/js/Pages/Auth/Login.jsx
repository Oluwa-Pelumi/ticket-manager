import Checkbox from '@/Components/Checkbox';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email   : '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <div className="text-center mb-10">
                <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Access Portal</h1>
                <p className="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400 mt-2 opacity-70">Credentials Required</p>
            </div>

            {status && (
                <div className="mb-6 text-sm font-bold text-emerald-500 bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20 backdrop-blur-md">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <InputLabel htmlFor="email" value="Email Address" className="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 ms-1" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full"
                        autoComplete="username"
                        isFocused={true}
                        placeholder="your@email.com"
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <div className="flex items-center justify-between px-1">
                        <InputLabel htmlFor="password" value="Password" className="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400" />
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-400 transition-colors"
                            >
                                Recover Access?
                            </Link>
                        )}
                    </div>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="w-full"
                        autoComplete="current-password"
                        placeholder="••••••••"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="flex items-center px-1">
                    <Checkbox
                        name="remember"
                        checked={data.remember}
                        className="rounded-lg bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-indigo-500 shadow-sm focus:ring-indigo-500 transition-all"
                        onChange={(e) =>
                            setData('remember', e.target.checked)
                        }
                    />
                    <span className="ms-3 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Remember Session
                    </span>
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-indigo-500 text-white font-black text-lg shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all uppercase tracking-widest"
                    >
                        {processing ? 'Authenticating...' : 'Sign In'}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
