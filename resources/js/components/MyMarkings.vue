<template>
    <div class="mis-marcaciones ">
        <div class="bg-white rounded overflow-x-auto shadow-md px-6 py-4">
            <div class="flex w-full justify-between mb-2">
                <div class="flex items-center border-b-2 border-blue-700 py-2" >
                    <template v-if="markings.length > 0">
                        <span><i class="fa fa-search" aria-hidden="true"></i></span>
                        <input class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Buscar" v-model="date" aria-label="Busqueda">
                    </template>
                </div>
                <!--<input v-model="date" class="form-input" name="search" id="search" type="search" placeholder="Buscar">-->
                <div class="flex ">
                    <div class="flex-initial pl-1">
                        <span class="cursor-pointer transition duration-500 ease-in-out button bg-gray-300 text-blue-700 border-b-4 border-gray-400 transform hover:-translate-y-1 hover:scale-100 " @click="showModal = true">
                            <i class="fa fa-calendar-o block md:hidden" aria-hidden="true"></i> <span class="hidden md:block">{{ action }}</span>
                        </span>
                    </div>
                    <div class="flex-initial" v-if="employee.role_id != 2">
                        <a href="/actions/create" class="transition duration-500 ease-in-out button bg-blue-600 text-white border-b-4 border-blue-700 transform hover:-translate-y-1 hover:scale-100 " >
                            <i class="fa fa-file block md:hidden" aria-hidden="true"></i>
                            <span class="hidden md:block">Crear acción de personal</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-1/2 mx-auto" v-if="searchDay.length == 0">
                <img src="/images/empty.svg">
                <p class="text-black font-bold text-center mb-4">No se encontró ningún registro</p>
            </div>
            <table class="w-full table-auto rounded border-collapse  border-gray-500 mt-4" v-else>
                <thead>
                    <tr class="border-b border-gray-400 border-t border-gray-400">
                        <th ></th>
                        <th class="px-4 py-2 text-gray-700 ">Día</th>
                        <th class="px-4 py-2 text-gray-700">Entrada</th>
                        <th class="px-4 py-2 text-gray-700">Salida</th>
                        <template v-if="employee.role_id != 2 ">
                            <th class="px-4 py-2 text-gray-700">Minutos tarde</th>
                            <th ></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center border-b border-gray-400"
                        :class="[item.minutes >= '20' ? 'text-red-500' : 'text-gray-600']"
                        :title="[item.minutes >= '20' ? 'Es probable que desees crear una acción de personal para este día' : '']"
                         v-for="(item, index) in searchDay" :key="index">
                        <td class="px-4 py-2 w-16">
                            <img :src="item.photo" class="rounded-full cursor-pointer" style="width:100% height:100%" @mouseover="showPhoto = item.id" @mouseleave="showPhoto = ''" alt="">
                            <div class="absolute w-40 h-40 p-2 border bg-white rounded z-10"  :class="showPhoto == item.id ? 'block' : 'hidden'"  >
                                <img :src="item.photo" class="cursor-pointer w-full h-full" alt="">
                            </div>
                        </td>
                        <td >{{ item.date }}</td>
                        <td  :class="[item.in >= 'Sin marcación' ? 'text-red-500' : 'text-gray-600']">{{ item.in  }}</td>
                        <td  :class="[item.out >= 'Sin marcación' ? 'text-red-500' : 'text-gray-600']">{{ item.out }}</td>
                        <template v-if="employee.role_id != 2">
                            <td >{{ item.minutes }}</td>
                            <td title="Crear acción de personal">
                                <form :action="'/action/create/date'" method="GET">
                                    <input type="hidden" name="date" :value="item.id">
                                    <button type="submit" class="focus:outline-none"><i class="fa fa-file-text" aria-hidden="true"></i>+</button>
                                </form>
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
         <my-modal-component v-if="showModal" @close="showModal = false">
            <template v-slot:header-modal>
                <p class="text-base md:text-2xl font-bold">Mostrar marcaciones</p>
            </template>
            <template v-slot:body-modal>
                <div class="flex flex-wrap justify-between mb-4">
                    <div class="flex-1 px-1 mb-2">
                        <span class="font-bold">Desde:</span>
                        <input type="date" class="form-input w-full" name="" id="" v-model="startDate" min="2019-12-31">
                    </div>
                    <div class="flex-1 px-1 mb-2">
                    <span class="font-bold text-left">Hasta:</span>
                        <input type="date" class="form-input w-full" name="" id="" v-model="endDate" value="2020-05-21">
                    </div>
                </div>
                <p class="text-center text-red-400">{{ message }}</p>
            </template>
            <template v-slot:footer-modal>
                <button class="focus:outline-none btn border border-gray-600 mx-1 hover:bg-gray-200" @click="showModal = false">Cancelar</button>
                <button class="focus:outline-none btn border border-blue-700 bg-blue-600 text-white hover:bg-blue-500 mx-1" @click="changePeriod()">
                    <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="loading"></i>
                    Aceptar
                </button>
            </template>
         </my-modal-component>
    </div>
</template>

<script>
import EventBus from '../eventbus.js';
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';

export default {
    props:['employee'],
    components: {
        Datepicker
    },
    data(){
        return{
            markings: [],
            date: '',
            action:'Historial',
            en: en,
            es: es,
            showModal:false,
            showPhoto: '',
            startDate: '',
            endDate: '',
            loading:false,
            message:'',
            weekly: true
        }
    },
    computed: {
            searchDay(){
               return this.markings.filter((day) => day.date.toLowerCase().includes(this.date));
            }
    },
    methods: {
        getMarkingsWeekly(){
            axios.get('/markings-weekly/' + this.employee.employee.id).then(response => {
                this.markings = response.data.data
            })
        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        showPhotoPre(){
            this.showPhoto = true
        },
        hidePhoto(){
            this.showPhoto = false
        },
        changePeriod(){
            let vm = this
            if(moment(vm.endDate) > moment(vm.startDate))
            {
                vm.loading = true
                axios.get('/markings/period/' + this.employee.employee.id,{
                    params:{
                        startDate: vm.startDate,
                        endDate: vm.endDate
                    }
                }).then(response => {
                    this.markings = response.data.data
                    this.showModal = false
                    if(this.startDate == moment().startOf('week').format('YYYY-MM-DD') && this.endDate == moment().endOf('week').format('YYYY-MM-DD'))
                    {
                        this.$root.showBar = true
                    }else{
                        this.$root.showBar = false
                    }
                }).catch(error => {
                    vm.message = 'No se encontro ningún registro'
                    vm.loading = false
                }).finally(()=>{
                    vm.loading = false
                })
            }else{
                vm.message = 'La fecha fin debe ser mayor'
            }
        }
    },
    mounted() {
        this.getMarkingsWeekly();
        this.startDate = moment().startOf('week').format('YYYY-MM-DD');
        this.endDate = moment().endOf('week').format('YYYY-MM-DD');
    },
}
</script>
