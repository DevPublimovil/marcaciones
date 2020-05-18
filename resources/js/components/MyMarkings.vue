<<template>
    <div class="mis-marcaciones ">
        <div class="bg-white rounded overflow-hidden shadow-md p-1">
            <div class="flex w-full justify-between mb-2">
                <div class="flex ">
                    <div class="flex-initial pl-1">
                        <a href="" class="transition duration-500 ease-in-out button text-xs bg-gray-300 text-primary border-b-4 border-gray-400 transform hover:-translate-y-1 hover:scale-100 ..." >
                            Crear acción de personal
                        </a>
                    </div>
                    <div class="flex-initial">
                         <span class="transition duration-500 ease-in-out button button-one text-xs border-b-4 border-primaryshadow transform hover:-translate-y-1 hover:scale-100" :class="{'union-btn-active' : period == 'semanal', 'text-white' : period == 'semanal'}" @click="showModal = true">{{ action }}</span>
                    </div>
                </div>
                <input v-model="date" class="shadow appearance-none border border-indigo-400 sm:block hidden rounded w-60 py-2 px-3 text-gray-700 leading-tight focus:outline-none text-xs" name="search" id="search" type="search" placeholder="Buscar">
            </div>
            <table class="w-full table-auto rounded border-collapse  border-gray-500 mt-1">
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
            <div slot="body">
                <div class="flex">
                    <div class="flex-1">
                        <datepicker :language="es" input-class="form-input" v-model="start_date"  placeholder="Selecciona la fecha"></datepicker>
                    </div>
                    <div class="flex-1">
                        <datepicker :language="es" input-class="form-input" v-model="end_date" placeholder="Selecciona la fecha"></datepicker>
                    </div>
                </div>
            </div>
            <h3 slot="header">custom header</h3>
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
            showModal: false,
            date: '',
            action:'Periodo',
            start_date: '',
            end_date:'',
            en: en,
            es: es
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
        getMarkings(){
            this.period = 2
            axios.get('/markings-month/' + this.employee).then(response => {
                this.markings = response.data.data
            })
        },
    },
    mounted() {
        this.getMarkingsWeekly();
    },
}
</script>