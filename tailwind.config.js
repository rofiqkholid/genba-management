/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Http/Controllers/**/*.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'sans-serif'],
            },
            borderRadius: {
                'DEFAULT': '2px',
                'sm': '2px',
                'md': '2px',
                'lg': '2px',
                'xl': '2px',
                '2xl': '2px',
                '3xl': '2px',
            },
        },
    },
    plugins: [],
};
