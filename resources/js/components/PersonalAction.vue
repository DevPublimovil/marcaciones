<template>
    <div class="form-personal-action text-xs">
        <div class="container-lg xl:w-10/12 mx-auto py-4">
            <div class="w-full p-0 mx-auto">
                <div class="bg-white p-6  shadow-lg uppercase pt-2 rounded">
                    <form id="formActions">
                        <template v-if="user.role_id == 2">
                            <h4 class="text-xl text-center text-gray-700">Empleado</h4>
                            <div class="form-control-ic">
                                <select
                                    class="form-select w-full"
                                    name="employee"
                                    id="employee"
                                    v-model="employee"
                                >
                                    <option
                                        v-for="(employee, index) in employees"
                                        :key="index"
                                        :value="employee.id"
                                        >{{
                                            employee.name_employee + ' ' + employee.surname_employee
                                        }}</option
                                    >
                                </select>
                            </div>
                        </template>
                        <h4 class="text-xl text-center text-gray-700">Faltas cometidas</h4>
                        <div
                            class="form-control-ic text-base grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2"
                        >
                            <div v-for="(type, index) in types" :key="index">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        v-model="typeactions"
                                        class="form-checkbox bg-gray-400"
                                        :value="type.id"
                                    />
                                    <span class="ml-2">{{ type.name_type_action }}</span>
                                </label>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    class="form-checkbox bg-gray-400"
                                    :checked="showOther"
                                    @click="showOther ? (showOther = false) : (showOther = true)"
                                />
                                <span class="ml-2">Otros</span>
                            </label>
                        </div>
                        <transition name="fade">
                            <textarea
                                class="form-textarea block border border-gray-500 w-full"
                                rows="2"
                                v-model="other"
                                v-if="showOther"
                                placeholder="Describe la falta cometida"
                            ></textarea>
                        </transition>

                        <div class="form-control-ic">
                            <h4 class="text-xl text-center text-gray-700 mb-6">
                                Desripción de la acción
                            </h4>
                            <textarea
                                class="form-textarea border border-gray-500 w-full"
                                rows="5"
                                v-model="description"
                                placeholder="Puedes escribir el dia que cometistes la falta"
                            ></textarea>
                        </div>

                        <div class="form-control-ic">
                            <h4 class="text-xl text-center text-gray-700 mb-6">
                                Adjuntar archivo
                            </h4>
                            <input type="file" class="form-input w-full cursor-pointer" @change="processFile($event)">
                        </div>

                        <div class="flex justify-center btn-group text-center">
                            <a
                                :href="'/home'"
                                class="btn text-blue-900 border border-blue-900 hover:bg-gray-200"
                            >
                                Cancelar
                            </a>
                            <button type="button" class="btn btn-blue mx-2" @click="checkForm()">
                                <i
                                    class="fa fa-spinner fa-spin"
                                    aria-hidden="true"
                                    v-if="loading"
                                ></i>
                                Aceptar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert';
export default {
    props: ['user', 'types', 'action', 'myemployee'],
    data() {
        return {
            typeactions: [],
            other: '',
            loading: false,
            description: '',
            errors: [],
            showOther: false,
            employees: [],
            employee: '',
            someData:''
        };
    },
    methods: {
        createPersonalAction() {
            let vm = this;
            vm.loading = true;
            let formData = new FormData()
            formData.append('attached', vm.someData)
            formData.append('actions', JSON.stringify(vm.typeactions))
            formData.append('otherAction', vm.showOther ? vm.other : '')
            formData.append('description', vm.description)
            formData.append('employee', vm.employee)
            axios
                .post('/actions/', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    swal(response.data, 'Puedes revisar su estado en tu historial', 'success').then(
                        value => {
                            if (value) {
                                window.location.href = '/actions';
                            }
                        },
                    );
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, intentalo de nuevo');
                })
                .finally(() => (vm.loading = false));
        },
        update(action) {
            let vm = this;
            vm.loading = true;
            axios
                .put('/actions/' + action, {
                    actions: vm.typeactions,
                    otherAction: vm.showOther ? vm.other : null,
                    description: vm.description,
                })
                .then(({ data }) => {
                    swal(data, 'Puedes revisar su estado en tu historial', 'success').then(
                        value => {
                            if (value) {
                                window.location.href = '/actions';
                            }
                        },
                    );
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, intentalo de nuevo');
                })
                .finally(() => (vm.loading = false));
        },
        processFile(event){
            this.someData = event.target.files[0]
        },
        showAlert(message) {
            swal({
                icon: 'warning',
                text: message,
                button: {
                    text: 'ok',
                },
            });
        },
        checkForm() {
            if (this.user.role_id == 2 && this.employee == '') {
                this.showAlert('Por favor selecciona un empleado');
            } else if (!this.typeactions.length && !this.other) {
                this.showAlert(
                    'Por favor selecciona al menos una falta cometida o descríbela en "Otros"',
                );
            } else if (!this.description) {
                this.showAlert('La descripción de su acción de personal es obligatoria');
            } else {
                if (this.action != null) {
                    this.update(this.action.id);
                } else {
                    this.createPersonalAction();
                }
            }
        },
        fetchEmployees() {
            axios.get('/apiemployees/show').then(({ data }) => {
                this.employees = data;
            });
        },
    },
    mounted() {
        if (this.action != null) {
            if (this.action.other_action != null) {
                this.showOther = true;
            }
            this.description = this.action.description;
            this.other = this.action.other_action;
            if (this.action.personalaction.length > 0) {
                for (let index = 0; index < this.action.personalaction.length; index++) {
                    this.typeactions.push(this.action.personalaction[index].type_action_id);
                }
            }
        }

        if (this.user.role_id == 2) {
            this.fetchEmployees();
        }

        if (this.myemployee != null) {
            this.employee = this.myemployee;
        }
    },
};
</script>
<style>
.loading {
    border-top-color: #f1f0ef;
    -webkit-animation: spinner 1.5s linear infinite;
    animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    }
}

@keyframes spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
