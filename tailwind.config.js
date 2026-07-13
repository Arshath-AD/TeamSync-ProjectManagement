import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ts: {
                    bg:        'var(--bg)',
                    surface:   'var(--surface)',
                    surface2:  'var(--surface-2)',
                    elevated:  'var(--elevated)',
                    border:    'var(--border)',
                    text:      'var(--text)',
                    text2:     'var(--text-2)',
                    text3:     'var(--text-3)',
                    accent:    'var(--accent)',
                    success:   'var(--success)',
                    warning:   'var(--warning)',
                    danger:    'var(--danger)',
                },
            },
            boxShadow: {
                sm:  'var(--shadow-sm)',
                md:  'var(--shadow-md)',
                lg:  'var(--shadow-lg)',
                xl:  'var(--shadow-xl)',
            },
            borderRadius: {
                DEFAULT: '8px',
            },
            spacing: {
                '4.5': '1.125rem',
            },
        },
    },

    plugins: [forms],
};
