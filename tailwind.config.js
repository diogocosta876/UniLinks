/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            keyframes: {
                press: {
                    "0%, 100%": { transform: "scale-95" },
                    "50%": { transform: "scale-100" },
                },
            },
            animation: {
                press: "press 1s ease-in-out infinite",
            },
        },
    },
    plugins: [],
};
