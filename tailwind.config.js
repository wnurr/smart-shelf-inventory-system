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

            /*
            |--------------------------------------------------------------------------
            | Font
            |--------------------------------------------------------------------------
            */

            fontFamily: {
                sans: [
                    'Figtree',
                    ...defaultTheme.fontFamily.sans,
                ],
            },


            /*
            |--------------------------------------------------------------------------
            | Smart Shelf Color System
            |--------------------------------------------------------------------------
            |
            | Industrial Steel:
            | Main application identity.
            |
            | Safety:
            | Construction/warehouse accent and warning states.
            |
            | Success:
            | Available, healthy, online, in-stock.
            |
            | Danger:
            | Low stock, errors, offline, destructive actions.
            |
            | Info:
            | Informational states and secondary information.
            |
            */

            colors: {

                /*
                |--------------------------------------------------------------------------
                | Industrial Steel
                |--------------------------------------------------------------------------
                */

                industrial: {
                    50:  '#F5F7F9',
                    100: '#EAF0F5',
                    200: '#DCE3E8',
                    300: '#BCCCDC',
                    400: '#829AB1',
                    500: '#627D98',
                    600: '#486581',
                    700: '#334E68',
                    800: '#243B53',
                    900: '#102A43',
                },


                /*
                |--------------------------------------------------------------------------
                | Safety Amber
                |--------------------------------------------------------------------------
                */

                safety: {
                    50:  '#FFFBEB',
                    100: '#FEF3C7',
                    200: '#FDE68A',
                    300: '#FCD34D',
                    400: '#FBBF24',
                    500: '#F59E0B',
                    600: '#D97706',
                    700: '#B45309',
                    800: '#92400E',
                    900: '#78350F',
                },


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                success: {
                    50:  '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    300: '#6EE7B7',
                    400: '#34D399',
                    500: '#10B981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065F46',
                    900: '#064E3B',
                },


                /*
                |--------------------------------------------------------------------------
                | Danger
                |--------------------------------------------------------------------------
                */

                danger: {
                    50:  '#FEF2F2',
                    100: '#FEE2E2',
                    200: '#FECACA',
                    300: '#FCA5A5',
                    400: '#F87171',
                    500: '#EF4444',
                    600: '#DC2626',
                    700: '#B91C1C',
                    800: '#991B1B',
                    900: '#7F1D1D',
                },


                /*
                |--------------------------------------------------------------------------
                | Information
                |--------------------------------------------------------------------------
                */

                info: {
                    50:  '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },


                /*
                |--------------------------------------------------------------------------
                | Legacy Colors
                |--------------------------------------------------------------------------
                |
                | TEMPORARY.
                |
                | Existing pages still use these colors. Do not remove them
                | until we finish migrating the old Blade components/pages.
                |
                */

                graphite: '#353535',
                teal: '#3c6e71',
                grey: '#d9d9d9',
                'yale-blue': '#284b63',
                red: '#CC3232',

            },


            /*
            |--------------------------------------------------------------------------
            | Reusable Shadows
            |--------------------------------------------------------------------------
            */

            boxShadow: {

                'app-card':
                    '0 1px 2px rgba(15, 23, 42, 0.03), 0 6px 18px rgba(15, 23, 42, 0.04)',

                'app-card-hover':
                    '0 10px 30px rgba(15, 23, 42, 0.08)',

                'app-primary':
                    '0 6px 18px rgba(51, 78, 104, 0.20)',

                'app-safety':
                    '0 6px 18px rgba(245, 158, 11, 0.18)',

            },


            /*
            |--------------------------------------------------------------------------
            | Reusable Border Radius
            |--------------------------------------------------------------------------
            */

            borderRadius: {

                'app-sm': '0.5rem',

                'app': '0.75rem',

                'app-lg': '1rem',

                'app-xl': '1.25rem',

            },

        },

    },

    plugins: [
        forms,
    ],

};