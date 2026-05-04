export default function Checkbox({ className = '', ...props }) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                'rounded-lg border-slate-300 dark:border-slate-700 text-teal-900 shadow-sm focus:ring-lime-500 dark:bg-slate-800 ' +
                className
            }
        />
    );
}
