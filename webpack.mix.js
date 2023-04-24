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

mix.styles([
    './public/assets/front/css/bootstrap.min.css',
    './public/assets/front/css/plugin.css',
    './public/assets/front/css/animate.css',
    './public/assets/front/css/toastr.css',
    './public/assets/front/jquery-ui/jquery-ui.min.css',
    './public/assets/front/jquery-ui/jquery-ui.structure.min.css',
    './public/assets/front/css/rtl/style.css',
    './public/assets/front/css/rtl/custom.css',
    './public/assets/front/css/common.css',
    './public/assets/front/css/rtl/responsive.css',
    './public/assets/front/css/common-responsive.css',
], './public/assets/front/css/rtl/all.css');