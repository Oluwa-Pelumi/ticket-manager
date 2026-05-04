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

            <div className="mb-10 text-center">
                <h1 className="text-4xl font-medium text-slate-900 dark:text-white">Login</h1>
                <p className="mt-3 text-sm text-slate-600 dark:text-slate-400">Use your support account credentials to continue.</p>
            </div>

            {status && (
                <div className="mb-6 text-sm font-bold text-emerald-500 bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20 backdrop-blur-md">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <InputLabel htmlFor="email" value="Email" className="pl-4 text-sm font-medium text-slate-700 dark:text-slate-300" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full rounded-full border-slate-300 px-4 py-3 shadow focus:border-lime-500 focus:ring-lime-500 dark:border-slate-700 dark:bg-slate-800"
                        autoComplete="username"
                        isFocused={true}
                        placeholder="your@email.com"
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <div className="flex items-center justify-between px-1">
                        <InputLabel htmlFor="password" value="Password" className="pl-3 text-sm font-medium text-slate-700 dark:text-slate-300" />
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-sm font-medium underline hover:text-lime-600 transition-colors"
                            >
                                Forgot password?
                            </Link>
                        )}
                    </div>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="w-full rounded-full border-slate-300 px-4 py-3 shadow focus:border-lime-500 focus:ring-lime-500 dark:border-slate-700 dark:bg-slate-800"
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
                        className="rounded-lg bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-teal-900 shadow-sm focus:ring-lime-500 transition-all"
                        onChange={(e) =>
                            setData('remember', e.target.checked)
                        }
                    />
                    <span className="ms-3 text-[11px] font-bold tracking-widest text-slate-500 dark:text-slate-400">
                        Remember me
                    </span>
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="fauna-btn-primary w-full !py-3.5 text-lg disabled:opacity-50"
                    >
                        {processing ? 'Signing In...' : 'Login'}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
