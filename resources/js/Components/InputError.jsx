export default function InputError({ message, className = '', ...props }) {
    return message ? (
        <p
            {...props}
            className={'text-xs font-bold text-rose-500 animate-in fade-in slide-in-from-top-1 duration-300 ' + className}
        >
            {message}
        </p>
    ) : null;
}
