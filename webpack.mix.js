const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.extract(['jquery','select2','bootstrap','datatables.net','datatables.net-bs4']);

mix.copyDirectory('resources/theme','public/theme');
mix.copyDirectory('resources/img','public/img');
mix.copy('resources/img/placeholder.jpg','storage/app/public/locandine/placeholder.jpg');

mix.disableNotifications();
