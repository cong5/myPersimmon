const {mix} = require('laravel-mix');

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

mix.disableNotifications();

/**
 * App
 */

mix.js('resources/assets/app/js/common.js', 'public/js/app.js')
    .sass('resources/assets/app/sass/style.scss', 'public/css/app.css');

/**
 * Backend
 */

mix.js('resources/assets/backend/js/app.js', 'public/backend/js/app.js')
    .sass('resources/assets/backend/sass/app.scss', 'public/backend/css/app.css');

if (mix.config.inProduction) {
    mix.version();
}