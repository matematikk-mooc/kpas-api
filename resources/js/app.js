import Vue from 'vue';
import GroupEnrollView from "./views/GroupEnrollView";
import MergeUserView from "./views/MergeUserView";
import DiplomaView from "./views/DiplomaView";
import NoDiplomaView from "./views/NoDiplomaView";
import KpasEmbedView from "./views/KpasEmbedView";
import StatisticsView from "./views/StatisticsView";
import jQuery from "jquery";


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

try {
    window.$ = window.jQuery = jQuery;
    window.Vue = Vue;
} catch (e) {
    console.error(e, e.stack);
}

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
