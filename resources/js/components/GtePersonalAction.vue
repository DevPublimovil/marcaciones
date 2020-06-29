<template>
    <div class="form-personal-action text-xs">
        <div class="container-lg xl:w-10/12 mx-auto py-4">
            <div class="w-full p-0 mx-auto">
                <div class="bg-white p-6  shadow-lg uppercase pt-2 rounded">
                    <form id="formActions">
                        <template v-if="user.role_id == 2 || user.role_id == 4">
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
                                    <option value="" selected>Seleccione un empleado</option>
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
                                        type="radio"
                                        v-model="typeactions"
                                        class="form-radio bg-gray-400"
                                        @click="changeType()"
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
                                    @click="changeOther()"
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
                                placeholder="Puedes escribir el día que has cometido la falta"
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
    props: ['user', 'types', 'action', 'myemployee','employees'],
    data() {
        return {
            typeactions: '',
            other: '',
            loading: false,
            description: '',
            errors: [],
            showOther: false,
            employee: '',
            someData:''
        };
    },
    methods: {
        createPersonalAction() {
            let vm = this;
            vm.loading = true;
            toastr.info('Por favor espera mientras se crea tu acción de personal')
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
                    swal({text:response.data.message, icon:response.data.type})
                    window.open('/actions/' + response.data.action, '_blank')
                    window.location.href = '/home';
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
                    swal({text:data.message, icon:data.type})
                    window.open('/actions/' + data.action, '_blank')
                    window.location.href = '/home';
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema, intentalo de nuevo');
                })
                .finally(() => (vm.loading = false));
        },
        processFile(event){
            this.someData = event.target.files[0]
        },
        changeOther(){
            if(this.showOther){
                this.other = ''
                this.showOther = false
            }else{
                this.typeactions = ''
                this.showOther = true
            }
        },
        changeType(){
            this.other = ''
            this.showOther = false
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
            if (this.user.role_id == 2 && !this.employee) {
                this.showAlert('Por favor selecciona un empleado');
            }else if (!this.typeactions && !this.other) {
                this.showAlert(
                    'Por favor selecciona una falta cometida o descríbela en "Otros"',
                );
            }else if (!this.description) {
                this.showAlert('La descripción de su acción de personal es obligatoria');
            }else {
                if (this.action != null) {
                    this.update(this.action.id);
                } else {
                    this.createPersonalAction();
                }
            }
        },
    },
    mounted() {
        if (this.action != null) {
            if (this.action.other_action != null) {
                this.showOther = true;
            }
            this.description = this.action.description;
            this.other = this.action.other_action;
            if (this.action.personalaction) {
                    this.typeactions = this.action.personalaction.type_action_id
            }
        }

        if (this.myemployee != null) {
            this.employee = this.myemployee;
        }
    },
};
</script>
