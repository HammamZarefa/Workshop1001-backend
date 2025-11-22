/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Main views
        './resources/views/**/*.blade.php',

        // Main JS / Vue / TS
        './resources/js/**/*.{js,jsx,ts,tsx,vue}',


    ],

    theme: {
        extend: {},
    },

    plugins: [],
};
