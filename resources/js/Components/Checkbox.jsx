export default function Checkbox({ className = '', ...props }) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                'rounded-lg border-emerald-900/20 dark:border-[#1d3a34] text-teal-900 shadow-sm focus:ring-lime-500 dark:bg-[#18342f] ' +
                className
            }
        />
    );
}




