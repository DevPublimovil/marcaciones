<template>
    <div class="details-markings-component py-4">
        <div class="dt-header">
            <div class="flex justify-between p-0">
                <div class="self-center flex-auto">
                    <h3>{{ employee.name_employee + ' ' + employee.surname_employee }} | {{ employee.departament.display_name }}</h3>
                </div>
                <div class="flex-auto justify-end">
                    <div class="flex justify-end">
                        <div class="self-center">
                            <span>Desde: </span>
                        </div>
                        <div>
                            <datepicker :language="es" v-model="startDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                        </div>
                        <div class="self-center">
                                <span>Hasta: </span>
                        </div>
                        <div>
                            <datepicker :language="es" v-model="endDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dt-body mt-4">
            <table class="table-auto w-full text-base text-gray-800">
                <thead class="text-center">
                    <th>Fecha</th>
                    <th>Dia</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Horas Trabajadas</th>
                    <th>Horas extras</th>
                    <th>Minutos</th>
                </thead>
                <tbody>
                    <tr v-for="(marking, index) in markings.markings" :key="index">
                        <td>{{ marking.date }}</td>
                        <td>{{ marking.day }}</td>
                        <td>{{ marking.in }}</td>
                        <td>{{ marking.out }}</td>
                        <td>{{ marking.hours_worked }}</td>
                        <td>{{ marking.extra_hours }}</td>
                        <td>{{ marking.late_arrivals }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <th>TOTAL:</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ markings.total_hours_worked}}</th>
                    <th>{{ markings.total_extra_hours }}</th>
                    <th>{{ markings.total_late_arrivals }}</th>
                    <th></th>
                </tfoot>
            </table>
            <div class="text-center">{{ employee.company.display_name }}</div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';
import DataTable from './DataTable.vue'
export default {
    props:['employee','startdate','enddate'],
    components: {
        Datepicker,
        DataTable
    },
    data(){
        return{
            en: en,
            es: es,
            startDate: '',
            endDate: '',
            markings: [],
        }
    },
    methods: {
        fetchDataMarkings(){
            let url = '/apiassists/show/' + this.employee.id
            let vm = this
            axios.get(url,{
                params:{
                    start_date: vm.startDate,
                    end_date: vm.endDate
                }
            }).then(response => {
                this.markings = response.data
            })
        },
        changeDate(){
            this.markings = []
            localStorage.setItem('time-vue', JSON.stringify({start: this.startDate, end: this.endDate}));
            this.$root.start = this.startDate
            this.$root.end = this.endDate
            this.fetchDataMarkings()
        },
    },
    mounted() {
        let datosDB = JSON.parse(localStorage.getItem('time-vue'));
        if(datosDB == null){
            this.startDate = this.startdate
            this.endDate = this.enddate
            this.$root.start = this.startdate
            this.$root.end = this.enddate
        }else{
            this.startDate = datosDB.start
            this.endDate = datosDB.end
            this.$root.start = datosDB.start
            this.$root.end = datosDB.end
        }
       
        this.fetchDataMarkings()
    },
}
</script>