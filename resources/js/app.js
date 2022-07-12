
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

Vue.component('group-enroll-view', require('./views/GroupEnrollView').default);
Vue.component('merge-user-view', require('./views/MergeUserView').default);
Vue.component('diploma-view', require('./views/DiplomaView').default);
Vue.component('no-diploma-view', require('./views/NoDiplomaView').default);
Vue.component('kpas-embed-view', require('./views/KpasEmbedView').default);
Vue.component('statistics-view', require('./views/StatisticsView').default);
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
