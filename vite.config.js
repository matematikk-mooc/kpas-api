import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path'


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
    build: {
        lib: {
          entry: resolve(__dirname, 'resourses'),
          name: 'Kpas',
          fileName: 'kpas',
          formats: ['cjs']
        },
        rollupOptions: {
          output: {
            inlineDynamicImports: false,
   
          }
        }
    },
    plugins: [
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
    ], 
    resolve: {
        alias: [
            {
                // this is required for the SCSS modules
                find: /^~(.*)$/,
                replacement: '$1',
            },
        ],
    },
});