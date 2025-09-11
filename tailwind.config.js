/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ['class'],
  content: [
    "./assets/js/frontend/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
			sans: ["'DM Sans Variable'", "'Segoe UI'", "sans-serif"],
			heading: ["Gotham", "'DM Sans Variable'", "'Segoe UI'", "sans-serif"],
		}, container: {
			center: true,
			padding: {
				DEFAULT: "1rem",
				md: "1.5rem",
				lg: "2rem",
			},
			screens: {
				"xl": "1280px",
			},
		},
		extend: {
			colors: {
				"picton-blue-50": "#effaff",
				"picton-blue-100": "#def5ff",
				"picton-blue-200": "#b6edff",
				"picton-blue-300": "#75e1ff",
				"picton-blue-400": "#2cd2ff",
				"picton-blue-500": "#00b7ef",
				"picton-blue-600": "#0098d4",
				"picton-blue-700": "#0079ab",
				"picton-blue-800": "#00668d",
				"picton-blue-900": "#065474",
				"picton-blue-950": "#04364d",
				"big-stone-50": "#eff8ff",
				"big-stone-100": "#def1ff",
				"big-stone-200": "#b5e5ff",
				"big-stone-300": "#73d2ff",
				"big-stone-400": "#29bcff",
				"big-stone-500": "#00a4fa",
				"big-stone-600": "#0082d7",
				"big-stone-700": "#0067ae",
				"big-stone-800": "#00578f",
				"big-stone-900": "#044876",
				"big-stone-950": "#022037",
			}
		}
  },
  plugins: [require("tailwindcss-animate"), require("@tailwindcss/typography")],
}
