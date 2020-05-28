<template>
    <div class="dv text-base py-6">
        <div class="flex justify-between align-center items-center align-middle content-center w-11/12 mx-auto mb-4">
            <div class="flex-1 text-blue-900 text-left">
               <h2 class="text-xl">{{ title }}</h2>
            </div>
            <div class="flex-1 text-right">
                <a href="/employees/create" class="btn bg-blue-500 text-white">Nuevo empleado <i class="fa fa-user-plus" aria-hidden="true"></i></a>
            </div>
        </div>
        <section class="w-11/12 mx-auto rounded overflow-hidden shadow-lg bg-white">
            <div class="px-2 py-4">
                <div class="dv-header flex justify-between p-0">
                    <div>
                        <span class="inline-block align-middle">Mostrar</span>
                        <select class="form-select" v-model="query.per_page" @change="fetchIndexData()">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="inline-block align-middle">empleados</span>
                    </div>
                    <div>
                        <input type="text" class="inline-block form-input" placeholder="Buscar" 
                        v-model="query.search_input" @keyup.enter="fetchIndexData()">
                        <button type="button" class="inline-block align-top text-xs h-full bg-white hover:bg-blue-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded" @click="fetchIndexData()">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="dv-body flex">
                    <table class="table-auto w-full text-center">
                        <thead>
                            <th class="cursor-pointer px-4 py-2" v-for="(column, index) in columns" :key="index" @click="toggleOrder(column)">
                                <span>{{ column }}</span>
                                <span class="dv-table-column" v-if="column === query.column">
                                    <span v-if="query.direction == 'asc'">&uarr;</span>
                                    <span v-if="query.direction == 'desc'">&darr;</span>
                                </span>
                            </th>
                        </thead>
                        <tbody class="text-gray-700 shadow-inner">
                            <template v-for="(row, index) in model">
                                <tr class="hover:bg-primaryhover hover:text-white cursor-pointer" @click="showProfile(row.id)"  :key="index">
                                    <td class="px-4 py-2">{{ row.first_name }}</td>
                                    <td>{{ row.last_name }}</td>
                                    <td>{{ row.cod }}</td>
                                    <td>{{ row.position }}</td>
                                    <td>{{ row.type }}</td>
                                    <td>{{ row.company }}</td>
                                    <td>{{ row.departament }}</td>
                                </tr>
                                <!-- <tr class="text-left text-base" v-if="opened.includes(index)" >
                                    <td colspan="8" class="pl-4">
                                        <span><b>Horas trabajadas: </b>{{ markings.hours_worked }}</span><br>
                                        <span><b>Horas extras: </b>{{ markings.extra_hours }}</span><br>
                                        <span><b>llegadas tarde: </b>{{ markings.late_arrivals }}</span>
                                    </td>
                                </tr> -->
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="dv-footer flex justify-between mt-5">
                    <div>
                        <span>Mostrando  {{ meta.from }} - {{ meta.to }} de {{ meta.total }} empleados</span>
                    </div>
                    <div>
                        <button type="button" class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="prev()" :disabled="links.prev == null"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        <button class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="next()" :disabled="links.next == null"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
export default {
    props:['title','source','columns'],
    data(){
        return{
            model: [],
            meta:[],
            links:[],
            opened: [],
            markings:[],
            token:'',
            query:{
                page: 1,
                column: 'nombre',
                direction: 'desc',
                per_page: 10,
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
                this.markings = []
            }
        },
        prev(){
            if(this.links.prev)
            {
                this.query.page--
                this.fetchIndexData()
                this.opened = []
                this.markings = []
            }
        },
        details(employee){
            let vm = this
            let url = '/apiemployees/markings/' + this.model[employee].id
            const index = this.opened.indexOf(employee);
            if (index > -1) {
                vm.markings = []
                this.opened.splice(index, 1)
            } else {
                axios.put(url,{
                    start_date: this.start_date,
                    end_date: this.end_date
                }).then(response => {
                    vm.markings = response.data
                })
                this.opened.push(employee)
            }
        },
        changeDate(){
            this.markings = []
            this.opened = []
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
            &per_page=${this.query.per_page}&search_input=${this.query.search_input}`).then(response =>{
                vm.model = response.data.data
                vm.meta = response.data.meta
                vm.links = response.data.links
            })
        },
        showProfile(employee){
            window.location.href = "/employees/" + employee
        }
    },
    mounted() {
        this.start_date = this.start
        this.end_date = this.end
        this.token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    },
}
</script>