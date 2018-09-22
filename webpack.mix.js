let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.disableNotifications();

mix      .js('resources/js/scripts.js',  'public/js')
    .postCss('resources/pcss/main.pcss', 'public/css', [
        tailwindcss('tailwind.js')
    ])
    .purgeCss()
    .version();
