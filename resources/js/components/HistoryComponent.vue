<template>
    <div class="hortory-component">
        <div class="history-main w-10/12 mx-auto mt-4 shadow-lg rounded-lg p-0">
            <div class="history-header flex justify-between">
                <div class="self-center">
                    
                </div>
                <ul class="list-reset flex border-b text-xl">
                    <li class="-mb-px m-0">
                        <a class="bg-white inline-block hover:text-blue-600  rounded-t py-2 px-4 font-semibold" :class="[ isPending ? 'border-l border-t border-r text-blue-600' : 'text-gray-500' ]" href="#" @click="fetchPending()">Pendientes</a>
                    </li>
                    <li class="-mb-px m-0">
                        <a class="bg-white inline-block hover:text-blue-600 py-2 px-4 font-semibold" :class="[ isApproved ? 'border-l border-t border-r text-blue-600' : 'text-gray-500' ]" href="#" @click="fetchApproved()">Aprobadas</a>
                    </li>
                </ul>
            </div>
            <div class="history-body flex bg-white rounded min-h-full">
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
                                            <h3 class="timeline-header text-xl text-gray-800 leading-loose p-1 m-0" v-if="role.role.name == 'gerente' || role.role.name == 'rrhh' || role.role.name == 'subjefe'">
                                                {{ action.name_employee }}
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
                                            <template v-if="role.role.name == 'gerente' || role.role.name == 'subjefe'">
                                                <button class="btn-sm border border-red-700 text-red-700 hover:text-red-800 hover:bg-red-100 mx-1" v-if="isPending" @click="changeNoApproved(action.id)">
                                                    <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="loadingNoApproved"></i>
                                                    No aprobar
                                                </button>
                                            </template>
                                            <template v-if="role.role.name == 'gerente' || role.role.name == 'rrhh' || role.role.name == 'subjefe'">
                                                <button class="btn-sm bg-blue-600 hover:bg-blue-700 text-white mx-1" v-if="isPending" @click="changeApproved(action.id)">
                                                    <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="loadingApproved"></i>
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
                        <div class="flex justify-center mt-32 mb-32" v-if="isLoading">
                            <div class="text-gray-700 text-center ">
                                <i class="fa fa-spinner fa-pulse fa-spin text-blue-500 fa-3x" aria-hidden="true"></i>
                                <div class="logo">Cargando...</div>
                            </div>
                        </div>
                        <div class="flex w-1/2 mx-auto p-1 h-full" v-if="!isLoading">
                            <div class="flex-1 h-64">
                                <img src="/images/empty.svg" class="object-contain h-56 w-full" alt="">
                                <p class="text-center">No se encontró ningún registro</p>
                            </div>
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
            token: '',
            loadingApproved: false,
            loadingNoApproved: false,
            isLoading: false
        }
    },
    created(){
        this.fetchPending();
        //this.token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    },
    methods: {
        fetchPending(){
            let vm = this
            vm.isLoading = true
            axios.get('/apiactions/1').then(response => {
                vm.actions = response.data.actions
                vm.role = response.data.user
                vm.isPending = true
                vm.isApproved = false
            }).catch(error =>{
                toastr.error('Ocurrió un error al cargar la página, intentalo de nuevo')
            }).finally(()=>{
                vm.isLoading = false
            })
        },
        fetchApproved(){
            let vm = this
            vm.isLoading = true
            axios.get('/apiactions/2').then(response => {
                vm.actions = response.data.actions
                vm.role = response.data.user
                vm.isPending = false
                vm.isApproved = true
            }).catch(error =>{
                toastr.error('Ocurrió un error al cargar la página, intentalo de nuevo')
            }).finally(()=>{
                vm.isLoading = false
            })
        },
        changeApproved(action){
            this.loadingApproved = true
            toastr.info('¡espera un momento, tus cambios se estan guardando!')
            axios.put('/actions/approved/' + action).then(({data}) => {
                this.fetchPending()
                swal(data,{
                        icon: "success",
                        timer: 2000,
                        button: false
                    });
            }).catch(error => {
                toastr.error('Ocurrió un problema, por favor intentelo de nuevo')
            }).finally(()=>{
                this.loadingApproved = false
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
                    vm.loadingNoApproved = true
                    toastr.info('¡espera un momento, tus cambios se estan guardando!')
                    axios.put('/actions/noapproved/' + action).then(response => {
                        vm.fetchPending()
                        swal(response.data,{
                            icon: "warning",
                            timer: 2000,
                            button: false
                        });
                    }).catch(error => {
                        toastr.error('Ocurrió un problema, por favor intentelo de nuevo')
                    }).finally(()=>{
                        this.loadingNoApproved = false
                    })
                } else {
                    swal("¡Aun puedes aprobar la acción de personal!");
                }
                });
        }
    },
}
</script>