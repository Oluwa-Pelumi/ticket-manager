import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import { Head, useForm } from '@inertiajs/react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Confirm Password" />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Security Check</h1>
                <p className="text-sm text-slate-500 dark:text-slate-400 mt-2">Please confirm your identity</p>
            </div>

            <div className="mb-6 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <InputLabel htmlFor="password" value="Password" className="text-slate-700 dark:text-slate-300 font-semibold" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                        isFocused={true}
                        placeholder="••••••••"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-4 rounded-2xl bg-indigo-500 text-white font-black text-lg shadow-xl shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 transition-all tracking-widest"
                    >
                        {processing ? 'Validating Identity...' : 'Validate Identity'}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
