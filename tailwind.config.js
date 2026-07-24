import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'fauna-rose': 'var(--color-fauna-rose)',
                'fauna-rose-hover': 'var(--color-fauna-rose-hover)',
                'fauna-rose-disabled': 'var(--color-fauna-rose-disabled)',
            },
        },
    },
    plugins: [forms],
};
