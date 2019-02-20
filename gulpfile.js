const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.notifications = false;

elixir(mix => {
    mix
        .sass([
            'app.scss',
            './node_modules/vue-animate/dist/vue-animate.min.css',
            './node_modules/bootstrap-daterangepicker/daterangepicker.scss',
            './node_modules/select2/dist/css/select2.css',
            './node_modules/bootstrap-slider/dist/css/bootstrap-slider.css'
        ])
        // .sass([
        //     'app.scss',
        //     'admin.scss',
        //     './node_modules/vue-animate/dist/vue-animate.min.css',
        //     './node_modules/admin-lte/dist/css/AdminLTE.css',
        //     './node_modules/admin-lte/dist/css/skins/skin-blue.css',
        //     './node_modules/bootstrap-daterangepicker/daterangepicker.scss',
        //     './node_modules/select2/dist/css/select2.css'
        // ], 'public/css/admin.css')
        //  .copy([
        //      'node_modules/font-awesome/fonts/**',
        //       'node_modules/bootstrap-sass/assets/fonts/**'
        //  ], 'public/fonts/')

        .webpack('register.js')
        // .webpack('collect.js')
        // .webpack('admin.js')
        // .webpack('app.js')
        // .webpack('rating.js')

        .browserSync({
            proxy: 'cleaning.dev'
        });
});
