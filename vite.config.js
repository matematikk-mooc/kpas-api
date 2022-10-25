import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { createVuePlugin as vue } from "vite-plugin-vue2";

export default defineConfig({
    css: {
        loaderOptions: {
          sass: {
            sassOptions: {
               prependData:
               `
              @import "./resourses/sass/_variables.scss";
              @import "./resourses/sass/app.scss";
              @import "./resourses/sass/statistics.scss"; 
            `
            }
          }
        }
    },
    plugins: [
       laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
        vue([vue()])
    ],
    resolve: {
        alias: [
            {
                // this is required for the SCSS modules
                find: /^~(.*)$/,
                replacement: '$1',
            },
        ],
        extensions: [
          ".js",
          ".vue",
          ".scss",
        ],
    },
});