const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/statistics.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js');

mix.webpackConfig({
    output: {
        path: path.resolve(__dirname, 'public')
    }
});
