export default function Checkbox({ className = '', ...props }) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                'rounded-lg border-slate-300 dark:border-slate-700 text-indigo-500 shadow-sm focus:ring-indigo-500 dark:bg-slate-800 ' +
                className
            }
        />
    );
}
