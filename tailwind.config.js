import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    base: '#11667B', hover: '#0E5668', pressed: '#0B4856',
                    light: '#D9E9EE', 'extra-light': '#EAF3F6', border: '#9AC4CF',
                    disabled: '#D9E7EB', text: '#FFFFFF', 'text-disabled': '#86A7B0',
                    focus: '#9AC4CF',
                },
                secondary: {
                    base: '#F59F1F', hover: '#E18D10', pressed: '#C67C0D',
                    light: '#FDE7C3', 'extra-light': '#FFF4E1', border: '#F7C978',
                    disabled: '#FBE9C8', text: '#111111', 'text-disabled': '#B7975F',
                    focus: '#F9C96D',
                },
                'soft-teal': {
                    base: '#9AC4CF', light: '#CDE1E8', 'extra-light': '#EAF3F6',
                    border: '#BFD9E1', text: '#11667B',
                },
                neutral: {
                    900: '#111111', 800: '#222222', 700: '#333333', 600: '#4B4B4B',
                    500: '#6F6F6F', 400: '#9A9A9A', 300: '#D5D5D5', 200: '#E6E6E6',
                    100: '#F3F3F3', 50: '#F8F8F8', 0: '#FFFFFF',
                },
                success: { base: '#1B8A5A', hover: '#157A4A', background: '#E7F4EE', text: '#157A4A', border: '#A8D8BF' },
                warning: { base: '#F59F1F', hover: '#E18D10', pressed: '#C67C0D', background: '#FFF4E1', text: '#8A5300', border: '#F7C978' },
                danger:  { base: '#C94444', hover: '#B23A3A', background: '#FBEAEA', text: '#B23A3A', border: '#E3AFAF' },
                info:    { base: '#11667B', background: '#EAF3F6', text: '#11667B', border: '#9AC4CF' },
            },
        },
    },

    plugins: [forms],
};