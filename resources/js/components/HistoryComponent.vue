<template>
    <div class="hortory-component">
        <div class="history-main shadow-lg rounded p-0 m-0">
            <div class="history-header flex justify-between">
                <ul class="list-reset flex border-b">
                    <li class="-mb-px m-0">
                        <a class="bg-white inline-block hover:text-blue-600  rounded-t py-2 px-4 font-semibold" :class="[ isPending ? 'border-l border-t border-r text-blue-600' : 'text-gray-500' ]" href="#" @click="fetchPending()">Pendientes</a>
                    </li>
                    <li class="-mb-px m-0">
                        <a class="bg-white inline-block hover:text-blue-600 py-2 px-4 font-semibold" :class="[ isApproved ? 'border-l border-t border-r text-blue-600' : 'text-gray-500' ]" href="#" @click="fetchApproved()">Aprobadas</a>
                    </li>
                </ul>
                <div class="self-center">
                    <h3 class="mr-3 text-xl text-blue-800">Historial de acciones de personal</h3>
                </div>
            </div>
            <div class="history-body flex bg-white">
                <div class="flex-1">
                    <div class="timeline relative mt-5 mx-2 p-0" v-if="actions.length > 0">
                        <div v-for="(action, index) in actions" :key="index">
                            <div class="time-label">
                                <span class="text-white shadow-none bg-gray-700 inline-block font-bold p-2 rounded">{{ action.created_at }}</span>
                            </div>
                            <div class="mt-2">
                                <div class="timeline-item rounded-lg border border-gray-400 text-gray-600 ml-16 p-0 mt-0 relative">
                                    <div class="flex justify-between">
                                        <div>
                                            <h3 class="timeline-header text-xl text-gray-800 leading-loose p-1 m-0" v-if="role.role.name == 'gerente' || role.role.name == 'rrhh'">
                                                {{ action.name_employee }}
                                            </h3>
                                            <h3 class="timeline-header text-xl text-gray-800 leading-loose p-1 m-0" v-else>
                                                <span><i :class="[action.check_gte ? 'text-green-600 fa fa-check-circle' : 'text-red-600 fa fa-times-circle']" aria-hidden="true"></i> Gerente</span>
                                                <span><i :class="[action.check_rh ? 'text-green-600 fa fa-check-circle' : 'text-red-600 fa fa-times-circle']" aria-hidden="true"></i> Recursos humanos</span>
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
                                            <a :href="'/actions/' + action.id" class="btn-sm border border-blue-700 text-ble-700 hover:text-blue-800 hover:bg-blue-100 mx-1" target="_blank">
                                                Ver pdf <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                            <a :href="'/actions/'+ action.id + '/edit'" class="btn-sm border border-blue-700 text-ble-700 hover:text-blue-800 hover:bg-blue-100 mx-1" v-if="role.role.name == 'empleado' && isPending">
                                                Editar
                                            </a>
                                            <template v-if="role.role.name == 'gerente'">
                                                <button class="btn-sm border border-red-700 text-red-700 hover:text-red-800 hover:bg-red-100 mx-1" v-if="isPending" @click="changeNoApproved(action.id)">
                                                    No aprobar
                                                </button>
                                            </template>
                                            <template v-if="role.role.name == 'gerente' || role.role.name == 'rrhh'">
                                                <button class="btn-sm bg-blue-600 hover:bg-blue-700 text-white mx-1" v-if="isPending" @click="changeApproved(action.id)">
                                                    Aprobar
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <template v-else>
                        <div class="w-1/2 mx-auto m-4">
                            <img src="/images/empty.svg">
                            <p class="text-gray-500 font-bold text-center mb-4">No se encontró ningún registro</p>
                        </div>
                    </template>
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
            isApproved: false,
            role: [],
            token: ''
        }
    },
    created(){
        this.fetchPending();
        //this.token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    },
    methods: {
        fetchPending(){
            axios.get('/apiactions/1').then(response => {
                this.actions = response.data.actions
                this.role = response.data.user
                this.isPending = true
                this.isApproved = false
            })
        },
        fetchApproved(){
            axios.get('/apiactions/2').then(response => {
                this.actions = response.data.actions
                this.role = response.data.user
                this.isPending = false
                this.isApproved = true
            })
        },
        changeApproved(action){
            axios.put('/actions/approved/' + action).then(({data}) => {
                this.fetchPending()
                swal(data,{
                        icon: "success",
                        timer: 2000,
                        button: false
                    });
            })
        },
        changeNoApproved(action){
            let vm = this
            swal({
                title: "¿Estás seguro que deseas descartar la acción de personal?",
                icon: "warning",
                buttons: true,
                buttons:  ["Cancelar", "Aceptar"],
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    axios.put('/actions/noapproved/' + action).then(response => {
                        vm.fetchPending()
                        swal(response.data,{
                            icon: "warning",
                            timer: 2000,
                            button: false
                        });
                    })
                } else {
                    swal("¡Aun puedes aprobar la acción de personal!");
                }
                });
        }
    },
}
</script>