/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.bus = new Vue();
var $ = require( "jquery" );
window.moment = require('moment');
import Multiselect from 'vue-multiselect';


import VModal from 'vue-js-modal'
Vue.component('multiselect', Multiselect);
Vue.use(VModal)
window.toastr = require('toastr');

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
Vue.component('my-markings', require('./components/MyMarkings.vue').default);
Vue.component('bad-progress', require('./components/BadProgress.vue').default);
Vue.component('my-modal-component', require('./components/MyModal.vue').default);
Vue.component('details-markings-component', require('./components/DetailsMarkings.vue').default);
Vue.component('personal-action-component', require('./components/PersonalAction.vue').default);
Vue.component('status-action-component', require('./components/StatusAction.vue').default);
Vue.component('notification-component', require('./components/NotificationComponent.vue').default);
Vue.component('data-viewer', require('./components/DataViewer.vue').default);
Vue.component('time-component', require('./components/TimeComponent.vue').default);
Vue.component('form-report', require('./components/FormReport.vue').default);
Vue.component('signature-component', require('./components/SignatureComponent.vue').default);
Vue.component('action-index-component', require('./components/ActionIndexComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import InfiniteLoading from 'vue-infinite-loading';
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';
const app = new Vue({
    components: {
        InfiniteLoading, Datepicker
      },
    el: '#app',
    data:{
        showBar: true,
        showModal: false,
        employee:'',
        page: 1,
        list: [],
        en: en,
        es: es,
        startDate: '',
        endDate: '',
        infiniteId: +new Date(),
    },
    methods: {
        infiniteHandler($state) {
          axios.get('/marcaciones/employees/', {
            params: {
              page: this.page,
              employee: this.employee
            },
          }).then(({ data }) => {
            if (data.data.length) {
              this.page += 1;
              this.list.push(...data.data);
              $state.loaded();
            } else {
              $state.complete();
            }
          });
        },

        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },

        changeDate(){
            this.page = 1;
            this.list = [];
            this.infiniteId += 1;
            localStorage.setItem('time-vue', JSON.stringify({start: this.startDate, end: this.endDate}));
        },
      },
    mounted() {
        setTimeout(function() {
            $("#notification").fadeOut(1500);
        },3000);

        let datosDB = JSON.parse(localStorage.getItem('time-vue'));
        if(datosDB == null){
            this.startDate = moment().startOf("week").format('YYYY-MM-DD')
            this.endDate = moment().endOf("week").format('YYYY-MM-DD')
        }else{
            this.startDate = datosDB.start
            this.endDate = datosDB.end
        }
    },
});
