<template>
    <div class="status-action-component">
        <div class="container-lg mx-auto py-2">
            <div class="card-status w-full md:w-10/12 mx-auto">
                <div
                    class="card-body-status flex bg-white border border-gray-200 rounded shadow"
                    v-if="actions.length > 0"
                >
                    <div class="timeline w-full relative mt-5 mx-2 p-0" v-if="actions.length > 0">
                        <div v-for="(action, index) in actions" :key="index">
                            <div class="time-label">
                                <span
                                    class="text-white shadow-none bg-gray-600 inline-block font-bold p-2 rounded"
                                    >{{ action.created_at }}</span
                                >
                            </div>
                            <div class="mt-2">
                                <div
                                    class="timeline-item rounded-lg border border-gray-400 text-gray-600 ml-16 p-0 mt-0 relative"
                                >
                                    <div class="w-full py-4">
                                        <div
                                            class="flex justify-center"
                                            @click="viewPdf(action.id)"
                                        >
                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div
                                                        class="w-10 h-10 mx-auto cursor-pointer bg-blue-500 rounded-full text-lg text-white flex items-center"
                                                        title="Acción de personal creada"
                                                        v-if="action.employee_id == null"
                                                    >
                                                        <span class="text-center text-white w-full">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="w-10 h-10 mx-auto cursor-pointer rounded-full text-lg text-white flex items-center"
                                                        title="Acción de personal creada"
                                                        v-else
                                                        :class="[action.check_employee == 1 ? 'bg-blue-500' : 'bg-red-500']"
                                                    >
                                                        <span class="text-center text-white w-full">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div
                                                    class="text-xs text-center md:text-base hidden md:block"
                                                >
                                                    <span v-if="action.employee_id == null">
                                                        Acción de personal enviada
                                                    </span>
                                                    <span v-else :class="[action.check_employee == 1 ? '' : 'text-red-500']">
                                                        {{action.check_employee == 1 ? 'Acción de personal enviada' : 'Debes Aprobar la acción de personal'}}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div
                                                        class="absolute flex align-center items-center align-middle content-center"
                                                        style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)"
                                                    >
                                                        <div
                                                            class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1"
                                                        >
                                                            <div
                                                                class="w-0  py-1 rounded"
                                                                :class="[
                                                                    action.check_gte == 1
                                                                        ? 'bg-blue-500'
                                                                        : action.check_gte == null
                                                                        ? 'bg-gray-300'
                                                                        : 'bg-blue-500',
                                                                ]"
                                                                style="width: 100%;"
                                                            ></div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="w-10 h-10 mx-auto cursor-pointer rounded-full text-lg text-white flex items-center"
                                                        :class="[
                                                            action.check_gte == 1
                                                                ? 'bg-blue-500'
                                                                : action.check_gte == null
                                                                ? 'bg-gray-300'
                                                                : 'bg-red-500',
                                                        ]"
                                                        :title="[
                                                            action.check_gte == 1
                                                                ? 'Tu accion de personal ha sido aprobado por tu jefe'
                                                                : action.check_gte == null
                                                                ? 'Tu accion de personal aún no ha sido aprobado por tu jefe'
                                                                : 'Tu acción de personal ha sido rechazada por tu jefe',
                                                        ]"
                                                    >
                                                        <span class="text-center text-white w-full">
                                                            <i
                                                                class="fa"
                                                                :class="[
                                                                    action.check_gte == 1
                                                                        ? 'fa-check-circle'
                                                                        : action.check_gte == null
                                                                        ? 'fa-times-circle'
                                                                        : 'fa-times-circle',
                                                                ]"
                                                            ></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div
                                                    class="text-xs text-center md:text-base hidden md:block"
                                                >
                                                    Gte.
                                                    {{
                                                        action.check_gte == 1
                                                            ? 'aprobada'
                                                            : action.check_gte == null
                                                            ? 'pendiente'
                                                            : 'no aprobada'
                                                    }}
                                                </div>
                                            </div>

                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div
                                                        class="absolute flex align-center items-center align-middle content-center"
                                                        style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)"
                                                    >
                                                        <div
                                                            class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1"
                                                        >
                                                            <div
                                                                class="w-0  py-1 rounded"
                                                                :class="[
                                                                    action.check_rh == 1
                                                                        ? 'bg-blue-500'
                                                                        : action.check_rh == null
                                                                        ? 'bg-gray-300'
                                                                        : 'bg-blue-500',
                                                                ]"
                                                                style="width: 100%"
                                                            ></div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="w-10 h-10 mx-auto cursor-pointer rounded-full text-lg text-white flex items-center"
                                                        :class="[
                                                            action.check_rh == 1
                                                                ? 'bg-blue-500'
                                                                : action.check_rh == null
                                                                ? 'bg-gray-300'
                                                                : 'bg-red-500',
                                                        ]"
                                                        :title="[
                                                            action.check_rh == 1
                                                                ? 'Tu accion de personal ha sido aprobado por tu jefe'
                                                                : action.check_rh == 0
                                                                ? 'Tu acción de personal ha sido rechazada por recursos humanos'
                                                                : 'Tu accion de personal aún no ha sido aprobado por recursos humanos',
                                                        ]"
                                                    >
                                                        <span class="text-center text-white w-full">
                                                            <i
                                                                class="fa"
                                                                :class="[
                                                                    action.check_rh == 1
                                                                        ? 'fa-check-circle'
                                                                        : action.check_rh == null
                                                                        ? 'fa-times-circle'
                                                                        : 'fa-times-circle',
                                                                ]"
                                                            ></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div
                                                    class="text-xs text-center md:text-base hidden md:block"
                                                >
                                                    RH.
                                                    {{
                                                        action.check_rh == 1
                                                            ? 'aprobada'
                                                            : action.check_rh == null
                                                            ? 'pendiente'
                                                            : 'no aprobada'
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-1 text-xs">
                                    <button
                                        class="btn border border-gray-800 hover:text-white hover:bg-gray-800"
                                        v-if="action.check_gte == null"
                                        @click="edit(action.id)"
                                    >
                                        Editar
                                    </button>
                                    <a
                                        :href="action.attached"
                                        class="btn border border-gray-800 hover:text-white hover:bg-gray-800 ml-1"
                                        target="_blank"
                                        v-if="action.attached != null"
                                    >
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </a>
                                    <button
                                        class="btn border border-gray-800 hover:text-white hover:bg-gray-800 ml-1"
                                        @click="viewPdf(action.id)"
                                    >
                                        Ver
                                    </button>
                                    <button
                                        class="btn bg-blue-500 hover:bg-blue-600 text-white ml-1"
                                        v-if="
                                            action.employee_id != null && action.check_employee == 0
                                        "
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
                <template v-else>
                    <div class="w-1/2 mx-auto m-4">
                        <img src="/images/empty.svg" />
                        <p class="text-gray-500 font-bold text-center mb-4">
                            No se encontró ningún registro
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],
    mounted() {
        this.fetchData();
    },
    data() {
        return {
            actions: [],
            loadingApproved: false,
        };
    },
    methods: {
        fetchData() {
            axios
                .get('/apiactions/employee/' + this.user.id)
                .then(response => {
                    this.actions = response.data;
                })
                .catch(response => {
                    toastr.error('Ocurrió un error, por favor recarga la página');
                });
        },
        edit(action) {
            window.location.href = '/actions/' + action + '/edit';
        },
        viewPdf(action) {
            window.open('/actions/' + action, '_blank');
        },
        changeApproved(action) {
            this.loadingApproved = true;
            toastr.info('¡espera un momento, tus cambios se estan guardando!');
            axios
                .put('/actions/approved/' + action)
                .then(({ data }) => {
                    this.fetchData();
                    swal(data, {
                        icon: 'success',
                        timer: 2000,
                        button: false,
                    });
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, por favor intentelo de nuevo');
                })
                .finally(() => {
                    this.loadingApproved = false;
                });
        },
    },
};
</script>
