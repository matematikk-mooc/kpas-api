
import GroupEnrollView from "./views/GroupEnrollView.vue";
import MergeUserView from "./views/MergeUserView.vue";
import DiplomaView from "./views/DiplomaView.vue"
import NoDiplomaView from "./views/NoDiplomaView.vue";
import KpasEmbedView from "./views/KpasEmbedView";
import StatisticsView from "./views/StatisticsView"

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

try {
    window.$ = window.jQuery = require('jquery');
    window.Vue = require('vue');
    require('select2');
} catch (e) {}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('group-enroll-view', GroupEnrollView);
Vue.component('merge-user-view', MergeUserView);
Vue.component('diploma-view', DiplomaView);
Vue.component('no-diploma-view', NoDiplomaView);
Vue.component('kpas-embed-view', KpasEmbedView);
Vue.component('statistics-view', StatisticsView);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    computed: {
      window() {
        return window;
      }
    }
});

