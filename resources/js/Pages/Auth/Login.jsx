import Checkbox from '@/Components/Checkbox';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
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

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Welcome Back</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">Log in to manage your tickets</p>
            </div>

            {status && (
                <div className="mb-4 text-sm font-medium text-emerald-500 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <InputLabel htmlFor="email" value="Email Address" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                        autoComplete="username"
                        isFocused={true}
                        placeholder="your@email.com"
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <div className="flex items-center justify-between">
                        <InputLabel htmlFor="password" value="Password" className="text-slate-700 dark:text-slate-300 font-semibold" />
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-xs font-bold text-[#FF2D20] hover:underline"
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
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                        autoComplete="current-password"
                        placeholder="••••••••"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="flex items-center">
                    <Checkbox
                        name="remember"
                        checked={data.remember}
                        className="rounded border-slate-300 dark:border-slate-700 text-[#FF2D20] focus:ring-[#FF2D20]"
                        onChange={(e) =>
                            setData('remember', e.target.checked)
                        }
                    />
                    <span className="ms-2 text-sm text-slate-600 dark:text-slate-400">
                        Remember me
                    </span>
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-[#FF2D20] text-white font-bold text-lg shadow-xl shadow-[#FF2D20]/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all"
                    >
                        {processing ? 'Logging in...' : 'Sign In'}
                    </button>
                </div>

                <div className="text-center pt-4">
                    <p className="text-sm text-slate-600 dark:text-slate-400">
                        Don't have an account?{' '}
                        <Link
                            href={route('register')}
                            className="font-bold text-[#FF2D20] hover:underline"
                        >
                            Create an account
                        </Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    );
}
