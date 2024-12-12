import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  css: {
    loaderOptions: {
      sass: {
        sassOptions: {
          prependData: `
              @import "./resourses/sass/_variables.scss";
              @import "./resourses/sass/app.scss";
              @import "./resourses/sass/statistics.scss";
            `,
        },
      },
    },
  },
  plugins: [laravel(["resources/sass/app.scss", "resources/js/app.js"]), vue()],
  envDir: "./vite-config/",
  resolve: {
    alias: [
      {
        // this is required for the SCSS  modules
        find: /^~(.*)$/,
        replacement: "$1",
      },
    ],
    extensions: [".js", ".vue", ".scss"],
  },
  define: {
    "import.meta.env.VITE_APP_ENV": JSON.stringify(
      process.env.VITE_APP_ENV || "development"
    ),
    "import.meta.env.VITE_APP_VERSION": JSON.stringify(
      process.env.VITE_APP_VERSION || "ci_not_found"
    ),
    "import.meta.env.VITE_SENTRY_DSN": JSON.stringify(
      process.env.VITE_SENTRY_DSN || ""
    ),
  },
});
