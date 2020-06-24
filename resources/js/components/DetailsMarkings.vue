<template>
    <div class="details-markings-component py-4">
        <div class="dt-header">
            <div class="flex justify-between p-0">
                <div class="self-center flex-auto">
                    <h3 class="text-xl font-bold">{{ employee.name_employee + ' ' + employee.surname_employee }}</h3>
                    <h3 class="text-gray-500 text-sm">{{ employee.departament.display_name }}</h3>
                </div>
                <div class="flex-auto justify-end">
                    <div class="flex justify-end">
                        <div class="self-center mx-1">
                            <span>Desde: </span>
                        </div>
                        <div>
                            <datepicker :format="customFormatter" :language="es" v-model="startDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                        </div>
                        <div class="self-center mx-1">
                                <span>Hasta: </span>
                        </div>
                        <div>
                            <datepicker :format="customFormatter" :language="es" v-model="endDate" @input="changeDate()" input-class="inline-block form-input"  placeholder="Selecciona la fecha"></datepicker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dt-body mt-4">
            <table class="table-auto w-full text-sm text-gray-800">
                <thead class="text-center border-b-2">
                    <th>Fecha</th>
                    <th>Dia</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Horas Trabajadas</th>
                    <th>Horas extras</th>
                    <th>Llegadas tarde</th>
                    <th>Permiso</th>
                </thead>
                <tbody class="text-center">
                    <tr v-for="(marking, index) in markings.markings" :key="index" class="border-b-2">
                        <td>{{ marking.date }}</td>
                        <td>{{ marking.day }}</td>
                        <td>{{ marking.in }}</td>
                        <td>{{ marking.out }}</td>
                        <td>{{ marking.hours_worked }}</td>
                        <td>{{ marking.extra_hours }}</td>
                        <td>{{ marking.late_arrivals }}</td>
                        <td>{{ marking.permission }}</td>
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
            <div class="text-center font-bold text-xl">{{ employee.company.display_name }}</div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';
export default {
    props:['employee','startdate','enddate'],
    components: {
        Datepicker,
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
            this.$root.start = moment(this.startDate).format('YYYY-MM-DD')
            this.$root.end = moment(this.endDate).format('YYYY-MM-DD')
            this.fetchDataMarkings()
        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        }
    },
    mounted() {
        let datosDB = JSON.parse(localStorage.getItem('time-vue'));
        if(datosDB == null){
            this.startDate = this.startdate
            this.endDate = this.enddate
            this.$root.start = moment(this.startdate).format('YYYY-MM-DD')
            this.$root.end = moment(this.enddate).format('YYYY-MM-DD')
        }else{
            this.startDate = datosDB.start
            this.endDate = datosDB.end
            this.$root.start = moment(datosDB.start).format('YYYY-MM-DD')
            this.$root.end = moment(datosDB.end).format('YYYY-MM-DD')
        }

        this.fetchDataMarkings()
    },
}
</script>
