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
    .extract(['vue','bootbox','axios','daterangepicker','@fortawesome/fontawesome-free','vue-upload-component','ionicons','vue-router','@tinymce/tinymce-vue','vue-bootstrap4-table','vuex'])
    .sass('resources/sass/app.scss', 'public/css')
    .styles([
    'node_modules/daterangepicker/daterangepicker.css',
],'public/dist/css/all.min.css');
// .scripts([
//     'node_modules/jquery/dist/jquery.min.js',
//     'node_modules/daterangepicker/daterangepicker.js',
//     'node_modules/@fortawesome/fontawesome-free/js/all.min.js',
//     'node_modules/@tinymce/tinymce-vue/lib/browser/tinymce-vue.min.js',
//     'node_modules/bootstrap/dist/js/bootstrap.min.js',
// ], 'public/dist/css/all.min.js');
