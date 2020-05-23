<template>
    <div class="hortory-component">
        <div class="bg-gray-200 p-0">
            <div class="flex rounded-lg justify-between">
                <div>
                    <span class="btn cursor-pointer" :class="{ activeTab: isPending }" @click="fetchPending()">Pendientes</span>
                    <span class="btn cursor-pointer" :class="{ activeTab: isApproved }" @click="fetchApproved()">Aprovadas</span>
                </div>
                <div>
                    <h3 class="self-center text-xl mr-2">Historial de acciones de personal</h3>
                </div>
            </div>
            <div class="bg-white p-3 m-0">
                <div class="timeline relative p-0">
                    <div v-for="(action, index) in actions" :key="index">
                        <div class="time-label">
                            <span class="text-white shadow-none bg-gray-700 inline-block font-bold p-2 rounded">{{ action.created_at }}</span>
                        </div>
                        <div class="mt-2">
                            <div class="timeline-item rounded-lg border border-gray-400 text-gray-600 ml-16 p-0 mt-0 relative">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="timeline-header text-xl text-gray-800 leading-loose p-1 m-0">
                                            <span><i class="text-green-600 fa fa-check-circle" aria-hidden="true"></i> Gerente</span>
                                            <span><i class="text-red-600 fa fa-times-circle" aria-hidden="true"></i> Recursos humanos</span>
                                        </h3>
                                    </div>
                                    <div>
                                        <span class="time text-gray-500 text-xs p-3 float-right">
                                            <i class="fa fa-clock-o"></i> {{ action.diffHumans }}
                                        </span>
                                    </div>
                                </div>
            
                                <div class="timeline-body p-2">
                                    <p class="mb-3">{{ action.description }}</p>
                                    <div class="flex justify-end">
                                        <a :href="'/actions/'+ action.id + '/edit'" class="btn border border-blue-700 text-ble-700 hover:text-blue-800 hover:bg-blue-100 mx-1">
                                            Editar
                                        </a>
                                        <a :href="'/actions/' + action.id" class="btn bg-blue-600 hover:bg-blue-700 text-white mx-1">
                                            Ver pdf <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            actions: [],
            isPending: true,
            isApproved: false
        }
    },
    created(){
        this.fetchPending();
    },
    methods: {
        fetchPending(){
            axios.get('/apiactions/pending').then(response => {
                this.actions = response.data
                this.isPending = true
                this.isApproved = false
            })
        },
        fetchApproved(){
            axios.get('/apiactions/approved').then(response => {
                this.actions = response.data
                this.isPending = false
                this.isApproved = true
            })
        }
    },
}
</script>
