import { createApp } from "vue/dist/vue.esm-bundler";
import * as Sentry from "@sentry/vue";

import AdminDashboardView from "./views/AdminDashboardView";
import BarChart from "./components/charts/BarChart";
import CourseSettignsView from "./views/CourseSettingsView";
import DashboardGroupSelect from "./components/DashboardGroupSelect";
import DashboardView from "./views/DashboardView";
import DiplomaView from "./views/DiplomaView";
import GroupEnrollView from "./views/GroupEnrollView";
import GroupedBarChart from "./components/charts/GroupedBarChart";
import HealthMonitorView from "./views/HealthMonitorView";
import ActivityView from "./views/ActivityView";
import HorizontalBarChart from "./components/charts/HorizontalBarChart";
import KpasEmbedView from "./views/KpasEmbedView";
import LineChart from "./components/charts/LineChart";
import MergeUserView from "./views/MergeUserView";
import UserDeletionView from "./views/UserDeletionView";
import NoCookies from "./views/NoCookies.vue";
import NoDiplomaView from "./views/NoDiplomaView";
import OpenAnswer from "./components/OpenAnswer";
import SurveyView from "./views/SurveyView.vue";

import vSelect from "vue-select";

//import jqueryExports from "jquery";
//import 'select2';
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//window.$ = jqueryExports.default;

const app = createApp({});

Sentry.init({
  app,
  debug: false,
  environment: import.meta.env.VITE_APP_ENV,
  release: import.meta.env.VITE_APP_VERSION,
  dsn: import.meta.env.VITE_SENTRY_DSN,
  replaysSessionSampleRate: 0,
  replaysOnErrorSampleRate: 1,
  tracesSampleRate: 1,
  integrations: [
    Sentry.browserTracingIntegration(),
    Sentry.replayIntegration({
      maskAllText: true,
      blockAllMedia: true,
    }),

    Sentry.feedbackIntegration({
      enableScreenshot: true,
      showBranding: false,
      colorScheme: "dark",

      isNameRequired: false,
      isEmailRequired: false,

      triggerLabel: "",
      triggerAriaLabel: "Gi tilbakemelding",
      submitButtonLabel: "Send",
      cancelButtonLabel: "Avbryt",
      confirmButtonLabel: "Send",
      addScreenshotButtonLabel: "Legg til skjermbilde",
      removeScreenshotButtonLabel: "Fjern skjermbilde",

      formTitle: "Tilbakemelding",
      nameLabel: "Navn",
      namePlaceholder: "Ditt navn",
      emailLabel: "E-post",
      emailPlaceholder: "Din e-post",
      isRequiredLabel: "(påkrevd)",
      messageLabel: "Melding",
      messagePlaceholder: "Skriv inn din tilbakemelding her",
      successMessageText: "Takk for tilbakemeldingen!",

      themeLight: {
        background: "#ffffff",
      },
      themeDark: {
        background: "#303030",
      },
    }),
  ],
});

app.component("group-enroll-view", GroupEnrollView);
app.component("merge-user-view", MergeUserView);
app.component("user-deletion-view", UserDeletionView);
app.component("diploma-view", DiplomaView);
app.component("no-diploma-view", NoDiplomaView);
app.component("kpas-embed-view", KpasEmbedView);
app.component("dashboard-view", DashboardView);
app.component("admin-dashboard-view", AdminDashboardView);
app.component("survey-view", SurveyView);
app.component("v-select", vSelect);
app.component("bar-chart", BarChart);
app.component("open-answer", OpenAnswer);
app.component("grouped-bar-chart", GroupedBarChart);
app.component("horizontal-bar-chart", HorizontalBarChart);
app.component("dashboard-select", DashboardGroupSelect);
app.component("no-cookies-view", NoCookies);
app.component("line-chart", LineChart);
app.component("course-settings-view", CourseSettignsView);
app.component("health-monitor-view", HealthMonitorView);
app.component("activity-view", ActivityView);

app.mount("#app");
