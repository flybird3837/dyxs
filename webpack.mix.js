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
    .sass('resources/assets/sass/media.select.scss', 'public/css/media.select.css')
    .js('resources/assets/js/media.select.js', 'public/js/media.select.js')
    .js('resources/assets/js/cax.js', 'public/js/cax.js');

mix.copyDirectory('resources/assets/images', 'public/images');
mix.copyDirectory('resources/assets/fonts', 'public/fonts');
mix.copyDirectory('resources/assets/js/ie', 'public/js/ie');
mix.copyDirectory('resources/assets/js/summernote', 'public/js/summernote');
// mix.copyDirectory('resources/assets/js/layer', 'public/js/layer');
mix.copy('resources/assets/css/app.v2.css', 'public/css/note.css');
mix.copy('resources/assets/js/app.v2.js', 'public/js/note.js');
// mix.copy('resources/assets/css/media.select.css', 'public/css/media.select.css');
mix.scripts([
    'resources/assets/js/parsley/parsley.min.js',
    'resources/assets/js/parsley/parsley.extend.js',
    'resources/assets/js/fuelux/fuelux.js',
    'resources/assets/js/select2/select2.min.js'
], 'public/js/plugins.js');

mix.styles([
    'resources/assets/js/fuelux/fuelux.css',
    'resources/assets/js/select2/select2.css',
    'resources/assets/js/select2/theme.css'
], 'public/css/plugins.css');