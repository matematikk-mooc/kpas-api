import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

const appEnv = process.env.APP_ENV ?? "development";
const appVersion = process.env.APP_VERSION ?? "1.0.0-dev";
let sentryDSN = process.env.VITE_SENTRY_DSN ?? "";

if (appEnv !== "local" && sentryDSN === "") {
  sentryDSN =
    "https://308bdd098bf3be5533639cd7285eb555@o4507468577701888.ingest.de.sentry.io/4508453633720400";
}

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
    preprocessorOptions: {
      scss: {
        silenceDeprecations: [
          "import",
          "legacy-js-api",
          "mixed-decls",
          "color-functions",
          "global-builtin",
        ],
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
    "import.meta.env.VITE_APP_ENV": JSON.stringify(appEnv),
    "import.meta.env.VITE_APP_VERSION": JSON.stringify(appVersion),
    "import.meta.env.VITE_SENTRY_DSN": JSON.stringify(sentryDSN),
  },
});
