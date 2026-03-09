import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            // ─── Palet Warna Utama: "Hutan & Hujan" ──────────────────
            colors: {
                forest: {
                    50:  '#f0f7f3',
                    100: '#d6ebdf',
                    200: '#afd8bf',
                    300: '#7eba99',
                    400: '#52997a',
                    500: '#3D7A5A', // primary
                    600: '#2e6047',
                    700: '#264d39',
                    800: '#1E3A2F', // deep
                    900: '#162a22',
                    950: '#0d1a15',
                },
                sage: {
                    50:  '#f4f8f3',
                    100: '#e4ede1',
                    200: '#c8dcc4',
                    300: '#a3c29c',
                    400: '#8FAF8B', // sage
                    500: '#6d9268',
                    600: '#547551',
                    700: '#435d41',
                    800: '#374b36',
                    900: '#2e3e2d',
                },
                mist: {
                    50:  '#f5f9f4',
                    100: '#D6E8D0', // mist
                    200: '#b8d4b0',
                    300: '#90b888',
                    400: '#6a9b61',
                    500: '#527f49',
                    600: '#3f663a',
                    700: '#34522f',
                    800: '#2c4328',
                    900: '#243722',
                },
                rain: {
                    50:  '#f0f5f8',
                    100: '#dce9ef',
                    200: '#B0BEC5', // rain grey
                    300: '#8aa9b6',
                    400: '#7AA3B8', // rain drop blue
                    500: '#5f8fa6',
                    600: '#4a738a',
                    700: '#3d5e71',
                    800: '#344f5f',
                    900: '#2d4350',
                },
                cream: '#F5F7F2',
            },

            // ─── Tipografi ────────────────────────────────────────────
            fontFamily: {
                display: ['"Instrument Serif"', ...defaultTheme.fontFamily.serif],
                sans:    ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                mono:    ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },

            // ─── Spacing & Sizing ────────────────────────────────────
            maxWidth: {
                'prose-lg': '72ch',
            },

            // ─── Box Shadow ──────────────────────────────────────────
            boxShadow: {
                'soft':  '0 2px 16px 0 rgba(30, 58, 47, 0.07)',
                'card':  '0 4px 24px 0 rgba(30, 58, 47, 0.10)',
                'hover': '0 8px 32px 0 rgba(30, 58, 47, 0.15)',
            },

            // ─── Border Radius ───────────────────────────────────────
            borderRadius: {
                'xl2': '1rem',
                'xl3': '1.5rem',
            },

            // ─── Animasi ─────────────────────────────────────────────
            keyframes: {
                'fade-up': {
                    '0%':   { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
            animation: {
                'fade-up': 'fade-up 0.5s ease-out both',
                'fade-in': 'fade-in 0.4s ease-out both',
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/typography'),
    ],
};
