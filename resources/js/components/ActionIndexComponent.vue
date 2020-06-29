<template>
    <div class="action-index-component">
        <div class="w-full md:w-10/12 mx-auto shadow-xs bg-white rounded py-4 px-4 mt-4">
            <div class="flex w-full justify-center md:justify-end">
                <a
                    href="/gte/actions/create"
                    title="Crear una nueva acción de personal"
                    v-if="roleuser != 3"
                    class="bg-white hover:bg-gray-800 hover:text-white border border-gray-800 text-gray-800 font-bold py-2 px-4 mx-2 rounded"
                >
                <i class="fa fa-plus-circle block md:hidden" aria-hidden="true"></i>
                    <span class="hidden md:block">Crear</span>
                </a>
                <div class="inline-flex">
                    <button
                        class="hover:bg-gray-800 hover:text-white font-bold py-2 px-4 rounded-l border border-gray-800 focus:outline-none"
                        :class="[ isPending ? 'bg-gray-800 text-white' : 'text-gray-800 bg-white' ]"
                        title="Ver acciones pendientes de aprobar"
                        @click="getActions(rpending, 0)"
                    >
                    <i class="fa fa-file-text block md:hidden" aria-hidden="true"></i>
                        <span class="hidden md:block">Pendientes</span>
                    </button>
                    <button
                        class="hover:bg-gray-800 hover:text-white font-bold py-2 px-4 border border-gray-800 focus:outline-none"
                        :class="[ isApproved ? 'bg-gray-800 text-white' : 'text-gray-800 bg-white' ]"
                        title="ver acciones aprobadas"
                        @click="getActions(rapproved, 1)"
                    >
                        <i class="fa fa-check-circle block md:hidden" aria-hidden="true"></i>
                        <span class="hidden md:block">Aprobadas</span>
                    </button>
                    <button
                        class="hover:bg-gray-800 hover:text-white font-bold py-2 px-4 rounded-r border border-gray-800 focus:outline-none"
                        :class="[ isNotApproved ? 'bg-gray-800 text-white' : 'text-gray-800 bg-white' ]"
                        title="ver acciones no aprobadas"
                        @click="getActions(rnotapproved, 2)"
                    >
                        <i class="fa fa-times-circle block md:hidden" aria-hidden="true"></i>
                        <span class="hidden md:block">Rechazadas</span>
                    </button>
                </div>
            </div>
            <div class="history-body flex bg-white rounded min-h-full p-0">
                <div class="flex-1">
                    <div class="timeline relative mt-5 p-0" v-if="actions.length > 0">
                        <div v-for="(action, index) in actions" :key="index">
                            <div class="time-label">
                                <span
                                    class="text-white text-xs shadow-none bg-gray-700 inline-block font-bold p-2 rounded"
                                    >{{ action.created_at }}</span
                                >
                            </div>
                            <div class="mt-2">
                                <div
                                    class="timeline-item rounded-lg border border-gray-400 text-gray-600 ml-16 p-0 mt-0 relative"
                                >
                                    <div class="flex justify-between">
                                        <div class="text-xs md:text-base">
                                            <h3
                                                class="timeline-header text-gray-800 leading-loose p-1 m-0"
                                            >
                                               <b> Autor: </b>{{ action.name_employee }}
                                            </h3>
                                        </div>
                                        <div class="hidden md:block">
                                            <span
                                                class="time text-gray-500 text-xs p-3 float-right"
                                            >
                                                <i class="fa fa-clock-o"></i>
                                                {{ action.diffHumans }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="timeline-body p-2">
                                        <p class="text-xs md:text-sm"><b>Descripción</b></p>
                                        <p class="text-xs mb-3">{{ action.description }}</p>
                                        <template>
                                            <div v-if="action.comment != null">
                                                <p class="text-xs md:text-sm"><b>Comentarios</b></p>
                                                <p class="text-xs">{{action.comment}}</p>
                                            </div>
                                        </template>
                                        <div class="flex justify-end">
                                            <a
                                                :href="action.attached"
                                                class="btn-sm border border-blue-700 text-ble-700 hover:text-blue-800 hover:bg-blue-100 mx-1"
                                                target="_blank"
                                                v-if="action.attached != null"
                                            >
                                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                                            </a>
                                            <a
                                                :href="'/actions/' + action.id"
                                                class="btn-sm border border-blue-700 text-ble-700 hover:text-blue-800 hover:bg-blue-100 mx-1"
                                                target="_blank"
                                            >
                                                Ver pdf
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                            <button
                                                class="btn-sm border border-red-700 text-red-700 hover:text-red-800 hover:bg-red-100 mx-1"
                                                v-if="isPending"
                                                @click="changeNoApproved(action.id)"
                                            >
                                                <i
                                                    class="fa fa-spinner fa-spin"
                                                    aria-hidden="true"
                                                    v-if="loadingNoApproved"
                                                ></i>
                                                No aprobar
                                            </button>
                                            <button
                                                class="btn-sm bg-blue-600 hover:bg-blue-700 text-white mx-1"
                                                v-if="isPending"
                                                @click="changeApproved(action.id)"
                                            >
                                                <i
                                                    class="fa fa-spinner fa-spin"
                                                    aria-hidden="true"
                                                    v-if="loadingApproved"
                                                ></i>
                                                Aprobar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <template v-else>
                        <div class="flex flex-wrap content-center h-64" v-if="isLoading">
                            <div class="text-gray-700 text-center mx-auto">
                                <i
                                    class="fa fa-spinner fa-pulse fa-spin text-blue-500 fa-3x"
                                    aria-hidden="true"
                                ></i>
                                <div class="logo">Cargando...</div>
                            </div>
                        </div>
                        <div class="flex w-full md:w-1/2 mx-auto p-1 h-full" v-if="!isLoading">
                            <div class="flex-1 h-64">
                                <img
                                    src="/images/empty.svg"
                                    class="object-contain h-56 w-full"
                                    alt=""
                                />
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
    props:['rpending','rapproved','rnotapproved','roleuser','approve','notapprove'],
    data() {
        return {
            actions: [],
            isPending: true,
            isApproved: false,
            isNotApproved: false,
            token: '',
            loadingApproved: false,
            loadingNoApproved: false,
            isLoading: false,
        };
    },
    created() {
        this.getActions(this.rpending, 0);
        //this.token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    },
    methods: {
        getActions(url, typeAction){
            let vm = this;
            vm.isLoading = true;
            axios
                .get(url)
                .then(response => {
                    vm.actions = response.data.actions;
                    switch(typeAction) {
                        case 0:
                            vm.isPending = true;
                            vm.isApproved = false;
                            vm.isNotApproved = false;
                            break;
                        case 1:
                            vm.isApproved = true;
                            vm.isPending = false;
                            vm.isNotApproved = false;
                            break;
                        default:
                            vm.isNotApproved = true;
                            vm.isApproved = false;
                            vm.isPending = false;
                    }
                })
                .catch(error => {
                    toastr.error('Ocurrió un error al cargar la página, intentalo de nuevo');
                })
                .finally(() => {
                    vm.isLoading = false;
                });
        },
        changeApproved(action) {
            this.loadingApproved = true;
            toastr.info('¡espera un momento, tus cambios se estan guardando!');
            axios
                .put(this.approve + action)
                .then(({ data }) => {
                    swal(data.message, {
                        icon: data.icon,
                        timer: 2000,
                        button: false,
                    });
                    this.getActions(this.rpending, 0);
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, por favor intentelo de nuevo');
                })
                .finally(() => {
                    this.loadingApproved = false;
                });
        },
        changeNoApproved(action) {
            let vm = this;
            var input = document.createElement("TEXTAREA")
            input.className = "form-textarea w-full"
            input.placeholder = "Agrega tu comentario.."
            swal({
                title: '¿Estás seguro que deseas descartar la acción de personal?',
                icon: 'warning',
                buttons: true,
                buttons: ['Cancelar', 'Aceptar'],
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    swal({
                        text: "Debes ingresar un comentario",
                        closeOnClickOutside: false,
                        content: input
                    }).then(value =>{
                        if(input.value.length == 0)
                        {
                            swal({
                                icon: "warning",
                                text: '¡Debes ingresar un comentario!'
                                })
                        }else{
                            vm.sendNotApprove(action, input.value)
                        }
                    })
                } else {
                    swal('¡Aún puedes aprobar la acción de personal!');
                }
            });
        },

        sendNotApprove(action, comments){
            let vm = this;
            vm.loadingNoApproved = true;
            toastr.info('¡Espera un momento, tus cambios se están guardando!');
            axios
                .put(this.notapprove + action,{
                    comments: comments
                })
                .then(response => {
                    console.log(response.data)
                    vm.getActions(this.rpending, 0);
                    swal(response.data.message, {
                        icon: response.data.icon,
                        timer: 2000,
                        button: false,
                    });
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, intenta recargar la página');
                })
                .finally(() => {
                    this.loadingNoApproved = false;
                });
        }
    },
};
</script>
