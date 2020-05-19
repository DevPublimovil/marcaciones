<template>
    <div class="dv text-base">
        <div class="flex w-11/12 mx-auto">
            <div class="flex-1 text-gray-700 text-left  px-2 py-2">
                <p class="left-0 inline-block">{{ title }}</p>
            </div>
            <div class="flex-1 text-gray-700 text-right  px-4 py-2">
               <div class="flex items-center">
                   <div>
                       <span class="mx-1">Desde: </span>
                   </div>
                   <div>
                        <datepicker :language="es" input-class="form-input h-6" v-model="start_date"  placeholder="Selecciona la fecha"></datepicker>
                   </div>
                   <div>
                       <span class="mx-1">Hasta: </span>
                   </div>
                   <div>
                        <datepicker :language="es" input-class="form-input h-6 " v-model="end_date"  placeholder="Selecciona la fecha"></datepicker>
                   </div>
                   <div>
                       <button class="text-xs bg-primary hover:bg-primaryhover ml-1 h-6 text-white font-semibold py-1 px-4 border border-gray-400 rounded shadow">Ir</button>
                   </div>
               </div>
            </div>
        </div>
        <section class="w-11/12 mx-auto rounded overflow-hidden shadow-lg bg-white">
            <div class="px-2 py-4">
                <div class="dv-header flex justify-between p-0">
                    <div>
                        <span class="inline-block align-middle">Mostrar</span>
                        <select class="ml-2 mr-2 border border-gray-400 cursor-pointer h-6 w-12 align-bottom rounded" v-model="query.per_page" @change="fetchIndexData()">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="inline-block align-middle">empleados</span>
                    </div>
                    <div>
                        <select class="inline-block border border-gray-400 cursor-pointer h-6 w-auto align-bottom rounded" v-model="query.search_column"> 
                            <option v-for="(column, index) in columns" :key="index">{{ column }}</option>
                        </select>
                        <input type="text" class="inline-block h-6 border border-gray-400 rounded p-1" placeholder="Buscar" 
                        v-model="query.search_input" @keyup.enter="fetchIndexData()">
                        <button type="button" class="inline-block align-top text-xs h-6 bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded" @click="fetchIndexData()">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="dv-body flex">
                    <table class="table-auto w-full text-center">
                        <thead>
                            <th></th>
                            <th class="cursor-pointer" v-for="(column, index) in columns" :key="index" @click="toggleOrder(column)">
                                <span>{{ column }}</span>
                                <span class="dv-table-column" v-if="column === query.column">
                                    <span v-if="query.direction == 'asc'">&uarr;</span>
                                    <span v-if="query.direction == 'desc'">&darr;</span>
                                </span>
                            </th>
                        </thead>
                        <tbody class="text-gray-700 shadow-inner">
                            <template v-for="(row, index) in model">
                                <tr class="py-2 hover:bg-primaryhover hover:text-white cursor-pointer" :class="{ 'bg-primary text-white': opened.includes(index) }" @click="details(index)" :key="index">
                                    <td v-if="opened.includes(index)"><i class="fa fa-minus-circle" aria-hidden="true"></i></td>
                                    <td v-else><i class="fa fa-plus-circle" aria-hidden="true"></i></td>
                                    <td>{{ row.first_name }}</td>
                                    <td>{{ row.last_name }}</td>
                                    <td>{{ row.cod }}</td>
                                    <td>{{ row.position }}</td>
                                    <td>{{ row.type }}</td>
                                    <td>{{ row.company }}</td>
                                    <td>{{ row.departament }}</td>
                                </tr>
                                <tr class="text-left text-xs" v-if="opened.includes(index)" :key="row.id+index">
                                    <td colspan="8">
                                        <span><b>Horas trabajadas: </b>{{ row.hoursworked }}</span><br>
                                        <span><b>Horas extras: </b>{{ row.extrahours }}</span><br>
                                        <span><b>llegadas tarde: </b>{{ row.latearrivals }}</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="dv-footer flex justify-between mt-5">
                    <div>
                        <span>Mostrando  {{ meta.from }} - {{ meta.to }} de {{ meta.total }} empleados</span>
                    </div>
                    <div>
                        <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="prev()"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="next()"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';
export default {
    props:['title','source','columns'],
    components: {
        Datepicker
    },
    data(){
        return{
            start_date: moment().startOf('week').toString(),
            end_date: moment().endOf('week').toString(),
            en: en,
            es: es,
            model: [],
            meta:[],
            links:[],
            opened: [],
            query:{
                page: 1,
                column: 'nombre',
                direction: 'desc',
                per_page: 10,
                search_column: 'nombre',
                search_operator: 'equal',
                search_input: ''
            },
            /* operators:{
                equal: '=',
                not_equal: '<>',
                less_than: '<',
                grater_than: '>',
                less_than_or_equal_to: '<=',
                grater_than_or_equal_to: '>=',
                in: 'IN',
                like: 'LIKE'
            } */
        }
    },
    created(){
        this.fetchIndexData()
    },
    methods: {
        next(){
            if(this.links.next)
            {
                this.query.page++
                this.fetchIndexData()
                this.opened = []
            }
        },
        prev(){
            if(this.links.prev)
            {
                this.query.page--
                this.fetchIndexData()
                this.opened = []
            }
        },
        details(employee){
            const index = this.opened.indexOf(employee);
            if (index > -1) {
                this.opened.splice(index, 1)
            } else {
                this.opened.push(employee)
            }
        },
        toggleOrder(column){
            if(column === this.query.column)
            {
                if(this.query.direction === 'desc')
                {
                    this.query.direction = 'asc'
                }
                else
                {
                    this.query.direction = 'desc'
                }
            }
            else{
                this.query.column = column
                this.query.direction = 'asc'
            }
            this.opened = []
            this.fetchIndexData()
        },
        fetchIndexData(){
            let vm = this
            axios.get(`${this.source}?column=${this.query.column}&direction=${this.query.direction}&page=${this.query.page}
            &per_page=${this.query.per_page}&search_column=${this.query.search_column}&search_input=${this.query.search_input}`).then(response =>{
                vm.model = response.data.data
                vm.meta = response.data.meta
                vm.links = response.data.links
            })
        },
    },
}
</script>