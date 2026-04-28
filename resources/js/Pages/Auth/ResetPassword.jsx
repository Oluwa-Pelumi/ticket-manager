import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import { Head, useForm } from '@inertiajs/react';

export default function ResetPassword({ token, email }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        password             : '',
        password_confirmation: '',
        token                : token,
        email                : email,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('password.store'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Reset Password" />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">New Password</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">Almost there! Set your new password</p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div className="space-y-2">
                    <InputLabel htmlFor="email" value="Email Address" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <InputLabel htmlFor="password" value="New Password" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="new-password"
                        isFocused={true}
                        placeholder="••••••••"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="space-y-2">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm New Password"
                        className="text-slate-700 dark:text-slate-300 font-semibold"
                    />

                    <TextInput
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        autoComplete="new-password"
                        placeholder="••••••••"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
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
                        className="w-full py-4 rounded-2xl bg-indigo-500 text-white font-black text-lg shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest"
                    >
                        {processing ? 'Updating Credentials...' : 'Update Credentials'}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
