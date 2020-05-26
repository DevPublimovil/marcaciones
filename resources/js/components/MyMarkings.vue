<template>
    <div class="mis-marcaciones ">
        <div class="bg-white rounded overflow-hidden shadow-md px-6 py-4">
            <div class="flex w-full justify-between mb-2">
                <div class="flex items-center border-b border-b-2 border-blue-700 py-2" >
                    <template v-if="searchDay.length > 0">
                        <span><i class="fa fa-search" aria-hidden="true"></i></span>
                        <input class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Buscar" v-model="date" aria-label="Busqueda">
                    </template>
                </div>
                <!--<input v-model="date" class="form-input" name="search" id="search" type="search" placeholder="Buscar">-->
                <div class="flex ">
                    <div class="flex-initial pl-1">
                        <span class="cursor-pointer transition duration-500 ease-in-out button bg-gray-300 text-blue-700 border-b-4 border-gray-400 transform hover:-translate-y-1 hover:scale-100 " :class="{'union-btn-active' : period == 'semanal', 'text-white' : period == 'semanal'}" @click="showModal = true">{{ action }}</span>
                    </div>
                    <div class="flex-initial">
                        <a href="/actions/create" class="transition duration-500 ease-in-out button bg-blue-600 text-white border-b-4 border-blue-700 transform hover:-translate-y-1 hover:scale-100 " >
                            Crear acción de personal
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-1/2 mx-auto" v-if="searchDay.length == 0">
                <img src="/images/empty.svg">
                <p class="text-gray-500 font-bold text-center mb-4">No se encontró ningún registro</p>
            </div>
            <table class="w-full table-auto rounded border-collapse  border-gray-500 mt-4" v-else>
                <thead>
                    <tr class="border-b border-gray-400 border-t border-gray-400">
                        <th class="px-4 py-2 text-gray-700 ">Día</th>
                        <th class="px-4 py-2 text-gray-700">Entrada</th>
                        <th class="px-4 py-2 text-gray-700">Salida</th>
                        <th class="px-4 py-2 text-gray-700">Minutos tarde</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center text-gray-600 border-b border-gray-400" v-for="(item, index) in searchDay" :key="index">
                        <td class="px-4 py-2">{{ item.date }}</td>
                        <td class="px-4 py-2">{{ item.in }}</td>
                        <td class="px-4 py-2">{{ item.out }}</td>
                        <td class="px-4 py-2">{{ item.minutes }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
         <my-modal-component v-if="showModal" @close="showModal = false">
            <template v-slot:header-modal>
                <p class="text-2xl font-bold">Elige un rango de fechas</p>
            </template>
            <template v-slot:body-modal>
                <div class="flex justify-between">
                    <div class="flex-1 px-1">
                        <span class="font-bold">Desde:</span>
                        <input type="date" class="form-input w-full" name="" id="" v-model="startDate" min="2019-12-31">
                    </div>
                    <div class="flex-1 px-1">
                    <span class="font-bold text-left">Hasta:</span>
                        <input type="date" class="form-input w-full" name="" id="" v-model="endDate" value="2020-05-21">
                    </div>
                </div>
            </template>
            <template v-slot:footer-modal>
                <button class="focus:outline-none btn border border-gray-600 mx-1 hover:bg-gray-200" @click="showModal = false">Cancelar</button>
                <button
                    class="focus:outline-none btn border border-blue-700 bg-blue-600 text-white hover:bg-blue-500 mx-1" @click="changePeriod()">Aceptar</button>
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
            period: 1,
            date: '',
            action:'Periodo',
            en: en,
            es: es,
            showModal:false,
            startDate: '',
            endDate: ''
        }
    },
    computed: {
            searchDay(){
               return this.markings.filter((day) => day.date.toLowerCase().includes(this.date));
            }
    },
    methods: {
        getMarkingsWeekly(){
            this.period = 1
            axios.get('/markings-weekly/' + this.employee).then(response => {
                this.markings = response.data.data
            })
        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        changePeriod(){
            this.period = 2
            let vm = this
            axios.get('/markings/period/' + this.employee,{
                params:{
                    startDate: vm.startDate,
                    endDate: vm.endDate
                }
            }).then(response => {
                this.markings = response.data.data
                this.showModal = false
            })
        }
    },
    mounted() {
        this.getMarkingsWeekly();
        this.startDate = moment().startOf('week').format('YYYY-MM-DD');
        this.endDate = moment().endOf('week').format('YYYY-MM-DD');
    },
}
</script>