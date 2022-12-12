
import GroupEnrollView from "./views/GroupEnrollView";
import MergeUserView from "./views/MergeUserView";
import DiplomaView from "./views/DiplomaView";
import NoDiplomaView from "./views/NoDiplomaView";
import KpasEmbedView from "./views/KpasEmbedView";
import QuizStatisticsView from "./views/QuizStatisticsView";
import BarChart from "./components/charts/BarChart";
import {createApp} from "vue/dist/vue.esm-bundler";
import './bootstrap';
import FloatingVue from 'floating-vue';
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

app.use(FloatingVue);
app.component('group-enroll-view', GroupEnrollView);
app.component('merge-user-view', MergeUserView);
app.component('diploma-view', DiplomaView);
app.component('no-diploma-view', NoDiplomaView);
app.component('kpas-embed-view', KpasEmbedView);
app.component('quiz-statistics-view', QuizStatisticsView);
app.component("v-select", vSelect);
app.component("bar-chart", BarChart);
app.mount("#app")
