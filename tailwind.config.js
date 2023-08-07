import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                mono: ['Courier', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#4d7c0f",
                    "secondary": "#1f34f2",
                    "accent": "#2ad824",
                    "neutral": "#f3f4f6",
                    "base-100": "#f2f2f3",
                    "info": "#9accea",
                    "success": "#65a30d",
                    "warning": "#f5db56",
                    "error": "#dc2626",
                },
            },
        ],
    },

    plugins: [forms, require("daisyui"), require('@tailwindcss/typography')],
};
