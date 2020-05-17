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
                         <span class="transition duration-500 ease-in-out button button-one text-xs border-b-4 border-primaryshadow transform hover:-translate-y-1 hover:scale-100" :class="{'union-btn-active' : period == 'semanal', 'text-white' : period == 'semanal'}" @click="showModal()">{{ action }}</span>
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
    </div>
</template>

<script>
import EventBus from '../eventbus.js';
export default {
    props:['employee'],
    data(){
        return{
            markings: [],
            period: 'semanal',
            date: '',
            action:'Periodo',
               title: 'A Vue.js and Tailwind CSS Modal',
                body: 'Lorem Khaled Ipsum is a major key to success. The key to more success is to get a massage once a week, very important, major key, cloth talk. Hammock talk come soon. It’s important to use cocoa butter. It’s the key to more success, why not live smooth? Why live rough? Stay focused. Let’s see what Chef Dee got that they don’t want us to eat. Lion! The key is to enjoy life, because they don’t want you to enjoy life. I promise you, they don’t want you to jetski, they don’t want you to smile.'
        }
    },
    computed: {
            searchDay(){
               return this.markings.filter((day) => day.date.toLowerCase().includes(this.date));
            }
    },
    methods: {
        getMarkingsWeekly(){
            this.period = 'semanal'
            axios.get('/markings-weekly/' + this.employee).then(response => {
                this.markings = response.data.data
                EventBus.$emit('markings', this.period)
            })
        },
        getMarkingsMonth(){
            this.period = 'mensual'
            axios.get('/markings-month/' + this.employee).then(response => {
                this.markings = response.data.data
                EventBus.$emit('markings', this.period)
            })
        },
        showModal(){
            this.$modal.show('hello-world');
        }
    },
    mounted() {
        this.getMarkingsWeekly();
    },
}
</script>