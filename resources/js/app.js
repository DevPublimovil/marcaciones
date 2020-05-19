/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.bus = new Vue();
window.moment = require('moment');

import VModal from 'vue-js-modal'
Vue.use(VModal)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('menu-component', require('./components/MenuComponent.vue').default);
Vue.component('my-markings', require('./components/MyMarkings.vue').default);
Vue.component('bad-progress', require('./components/BadProgress.vue').default);
Vue.component('my-modal-component', require('./components/MyModal.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import DataViewer from './components/DataViewer.vue'
const app = new Vue({
    el: '#app',
    components:{
        DataViewer
    },
});
