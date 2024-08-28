import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
		"./vendor/robsontenorio/mary/src/View/Components/**/*.php",
		"./resources/**/*.blade.php",
		"./resources/**/*.js",
		"./resources/**/*.vue",
		"./node_modules/flowbite/**/*.js",
	],
	darkMode: 'selector',
	theme: {
		extend: {
			utilities: {
				'.no-spinners': {
					'&::-webkit-outer-spin-button, &::-webkit-inner-spin-button': {
						'-webkit-appearance': 'none',
						'margin': '0',
					},
					'&[type=number]': {
						'-moz-appearance': 'textfield',
					},
				},
			},
			fontFamily: {
				sans: ['Figtree', ...defaultTheme.fontFamily.sans],
			},
		},
	},
	plugins: [
		forms,
		typography,
		require("daisyui"),
		require('flowbite/plugin')({
			charts: true,
		}),
	],
};
