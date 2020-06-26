<template>
    <div>
        <button type="button" class="bg-blue-600 hidden md:inline-block  h-full hover:bg-blue-500 font-semibold text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" @click="showModal = true">
            Generar reporte  <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
        </button>

        <my-modal-component v-if="showModal" @close="showModal = false">
                <template v-slot:header-modal>
                    <div class="flex header-modal mb-4 py-2">
                        <h3 class="text-xl text-gray-600 font-bold">Opciones para el reporte</h3>
                    </div>
                </template>
                <template v-slot:body-modal >
                    <form :action="'/reports/create'" method="GET" target="_blank" id="mi-form-report" class="p-0 m-0 h-full">
                        <div class="flex">
                            <div class="flex-1">
                                 <input type="hidden" name="_token" :value="token">
                                <input type="hidden" name="start_date" :value="formatDate(start)">
                                <input type="hidden" name="end_date" :value="formatDate(end)">
                                <input type="hidden" name="employees" :value="selected">
                                <ul>
                                    <li class="mb-4 w-full">
                                        <label class="text-gray-600 font-bold">Listado de empleados</label>
                                        <multiselect  v-model="value" deselect-label="remover" selected-label="Seleccionado" :maxHeight="200" :limit="5" select-label="Presiona para agregar" track-by="name" label="name"  placeholder="Seleciona uno o varios empleados" :options="options" :searchable="true" :multiple="true">
                                        </multiselect>
                                    </li>
                                    <li class="mb-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="form-checkbox bg-gray-400" value="all" :checked="value.length == 0" @click="value = []">
                                            <span class="ml-2">Todos los empleados</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex justify-end modal-footer">
                            <button type="button" class="btn bg-blue-600 hover:bg-blue-500 text-white" @click="clear()">Generar Reporte</button>
                        </div>
                    </form>
                </template>
        </my-modal-component>
    </div>
</template>
<script>
export default {
    props:['token','start','end',],
    data() {
        return {
            employees:[],
            value:[],
            options:[],
            showModal:false,
        }
    },
    mounted() {
        this.getEmployees();
    },
    methods: {
        customForm(){
            this.$modal.show('form-report')
        },
        getEmployees(){
            axios.get('/apiemployees/show').then(({data}) => {
                this.employees = data
                for(var i = 0; i < this.employees.length; i++){
                    this.options.push({id: this.employees[i].id, name:this.employees[i].name_employee + ' ' + this.employees[i].surname_employee})
                }
            })
        },
        clear(){
            $("#mi-form-report").submit()
            this.showModal = false
            this.value = []
        },
        formatDate(date){
            return moment(date).format('YYYY-MM-DD')
        }
    },
    computed: {
        selected(){
            var miarray = []
            for(var i = 0; i < this.value.length; i++){
                miarray.push(this.value[i].id)
            }
            return miarray
        }
    },
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
  .multiselect__tag{
    background-color: #FF5733;
  }
  .multiselect__tag-icon:hover{
    background-color: #FF5733;
  }
  .multiselect__element:hover{
    background-color: #FF5733;
  }
</style>