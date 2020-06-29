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
Vue.component('gte-personal-action-component', require('./components/GtePersonalAction.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import InfiniteLoading from 'vue-infinite-loading';
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';
import swal from 'sweetalert';
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
        sendNotification(){
          let vm = this;
          var input = document.createElement("input")
          input.setAttribute("type","text")
          input.className = "form-input w-full"
          input.placeholder = "Agrega la fecha limite."
          swal({
              title: '¿Estás seguro que deseas enviar una notificación de acciones de personal a tus empleados?',
              icon: 'warning',
              buttons: true,
              buttons: ['Cancelar', 'Aceptar'],
              dangerMode: true,
          }).then(willDelete =>{
              if(willDelete){
                  swal({
                      title: "Debes ingresar la fecha limite",
                      closeOnClickOutside: false,
                      content: input
                  }).then(value=>{
                      if(input.value.length == 0)
                      {
                          swal({
                              icon: "warning",
                              text: '¡Debes ingresar la fecha limite!'
                              })
                      }else{
                        toastr.info('Espera un momento tu notificación se esta procesando')
                        axios.post('/reports/notification',{
                          limitday: input.value
                        }).then(response =>{
                          swal({text:response.data.message,icon:response.data.type})
                        }).catch(error =>{
                          swal({text:'Ocurrió un problema por favor intentalo de nuevo', icon:'warning'})
                        })
                      }
                  })
              }else{
                swal({text:'La notificación ha sido cancelada'})
              }
          })
        }
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
