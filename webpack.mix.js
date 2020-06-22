const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')
const path        = require('path');
require('laravel-mix-purgecss');

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

//mix.browserSync(process.env.APP_URL || 'http://localhost:8000');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .options({
   processCssUrls: false,
   postCss: [ tailwindcss('./tailwind.config.js') ],
   }).webpackConfig({
      resolve: {
         alias: {
            "@":     path.resolve(__dirname, "resources/assets/js"),
         }
      }
   }).purgeCss();