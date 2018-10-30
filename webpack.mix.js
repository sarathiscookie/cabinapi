let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .version();

/* Js and css for each page */
mix.styles([
    'resources/assets/sass/calendar.css',
    'resources/assets/sass/msNewBooking.css'
], 'public/css/all.css').version();

mix.scripts([
    'resources/assets/js/calendar.js',
    'resources/assets/js/msNewBooking.js'
], 'public/js/all.js').version();