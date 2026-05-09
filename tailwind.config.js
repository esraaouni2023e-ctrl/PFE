import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Sora', 'sans-serif'],
                mono: ['IBM Plex Mono', 'monospace'],
            },
            colors: {
                navy: {
                    900: '#0A1428',
                    800: '#001122',
                    700: '#0D1B36',
                },
                cyan: {
                    400: '#00D4FF',
                    500: '#0099FF',
                    900: '#003366',
                },
                gold: {
                    400: '#FFCC00',
                    500: '#FFD700',
                },
                violet: {
                    900: '#3F1A7A',
                }
            },
            boxShadow: {
                'neon-cyan': '0 0 10px rgba(0, 212, 255, 0.3), 0 0 20px rgba(0, 212, 255, 0.1)',
                'neon-gold': '0 0 10px rgba(255, 204, 0, 0.3), 0 0 20px rgba(255, 204, 0, 0.1)',
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
            },
            animation: {
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                }
            }
        },
    },

    plugins: [forms],
};
