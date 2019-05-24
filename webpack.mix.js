const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
    output: {
        path: path.resolve(__dirname, 'public')
    }
});
