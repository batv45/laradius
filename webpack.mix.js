/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const mix = require('laravel-mix');
const path = require("path");
const webpackConfig = require('./webpack.config')

mix.js('resources/js/app.js', '/js')
    .js('resources/js/hotspot.js', '/js')
    .sass('resources/scss/app.scss','/css')
    .sass('resources/scss/hotspot.scss','/css')
    .vue()
    .disableNotifications()
    .webpackConfig(webpackConfig);


if (mix.inProduction()) {
    mix.version();
}
