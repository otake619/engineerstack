const mix = require('laravel-mix');

 mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/navbar.js', 'public/js')
    .js('resources/js/input_memo.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .autoload({
    jquery: ['$', 'window.jQuery']
});

