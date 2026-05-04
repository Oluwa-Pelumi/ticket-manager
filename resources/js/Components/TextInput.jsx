import { forwardRef, useEffect, useImperativeHandle, useRef } from 'react';

export default forwardRef(function TextInput(
    { type = 'text', className = '', isFocused = false, ...props },
    ref,
) {
    const localRef = useRef(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, [isFocused]);

    return (
        <input
            {...props}
            type={type}
            className={
                'rounded-2xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:focus:border-lime-500 dark:focus:ring-lime-500 transition-all duration-300 placeholder:text-slate-400 dark:placeholder:text-slate-600 ' +
                className
            }
            ref={localRef}
        />
    );
});
