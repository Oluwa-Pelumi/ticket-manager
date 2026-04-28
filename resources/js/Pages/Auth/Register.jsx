import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name                 : '',
        email                : '',
        password             : '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">Join us to start managing your tickets</p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div className="space-y-2">
                    <InputLabel htmlFor="name" value="Full Name" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="name"
                        isFocused={true}
                        placeholder="John Doe"
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />

                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <InputLabel htmlFor="email" value="Email Address" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="username"
                        placeholder="john@example.com"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <InputLabel htmlFor="password" value="Password" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="new-password"
                        placeholder="••••••••"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                        className="text-slate-700 dark:text-slate-300 font-semibold"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="new-password"
                        placeholder="••••••••"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        required
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div className="pt-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-indigo-500 text-white font-black text-lg shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all uppercase tracking-widest"
                    >
                        {processing ? 'Registering...' : 'Initialize Account'}
                    </button>
                </div>

                <div className="text-center pt-4">
                    <p className="text-sm text-slate-600 dark:text-slate-400">
                        Already have an account?{' '}
                        <Link
                            href={route('login')}
                            className="font-bold text-indigo-500 hover:underline"
                        >
                            Sign In
                        </Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    );
}
