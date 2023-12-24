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
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#6c00ff",
                    "secondary": "#eab308",
                    "accent": "#00a600",
                    "neutral": "#000608",
                    "base-100": "#f3fdff",
                    "info": "#00aed0",
                    "success": "#00d27b",
                    "warning": "#f97316",
                    "error": "#dc2626",
                },
            },
        ],
    },

    plugins: [forms, require("daisyui"), require('@tailwindcss/typography')],
};
