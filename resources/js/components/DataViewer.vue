<template>
    <div class="dv text-base py-4">
        <div class="w-11/12 mx-auto">
            <div
                class="flex h-12 justify-between align-middle content-center items-center mt-4 mb-4"
            >
                <div>
                    <h2 class="text-2xl text-blue-900 font-bold">
                        <i class="fa fa-users" aria-hidden="true"></i> Lista de empleados
                    </h2>
                </div>
                <div class="text-sm">
                    <span
                        v-if="employeesSelected.length > 0"
                        class="btn border border-blue-800 text-blue-800 hover:bg-blue-200 cursor-pointer"
                        @click="showFormChange()"
                        >Cambiar horario</span
                    >
                    <template v-if="rol == 3">
                        <span
                            class="btn bg-blue-500 hover:bg-blue-400 text-white cursor-pointer"
                            @click="newTimestable()"
                            >Nuevo horario</span
                        >
                        <a
                            href="/employees/create"
                            class="btn bg-blue-500 hover:bg-blue-400 text-white"
                            >Nuevo empleado</a
                        >
                    </template>
                </div>
            </div>
            <div class="flex">
                <div class="w-1/3 mr-2">
                    <div
                        class="w-full rounded-lg border border-gray-300 text-gray-700 overflow-hidden shadow-lg bg-white py-6 px-4"
                    >
                        <h2 class="text-md font-bold h-12">Horarios</h2>
                        <time-component
                            v-for="(time, index) in timestables"
                            :time="time"
                            :currentTime="idtimestable"
                            :key="index"
                            @update-timestable="updateTime"
                            @list-employees="listEmployees"
                        ></time-component>
                    </div>
                </div>
                <section
                    class="w-3/4 bg-white text-gray-700 shadow-md rounded-lg border border-gray-300"
                >
                    <div class="px-2 py-4">
                        <div class="dv-header flex justify-between p-0">
                            <div>
                                <span class="inline-block align-middle">Mostrar</span>
                                <select
                                    class="form-select"
                                    v-model="query.per_page"
                                    @change="fetchIndexData()"
                                >
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="inline-block align-middle">empleados</span>
                            </div>
                            <div>
                                <input
                                    type="text"
                                    class="inline-block form-input"
                                    placeholder="Buscar"
                                    v-model="query.search_input"
                                    @keyup.enter="fetchIndexData()"
                                />
                                <button
                                    type="button"
                                    class="inline-block align-top text-xs h-full bg-white hover:bg-blue-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded"
                                    @click="fetchIndexData()"
                                >
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="dv-body flex">
                            <table class="table-fixed w-full text-center">
                                <thead class="text-gray-900">
                                    <th width="40px"></th>
                                    <th
                                        class="cursor-pointer px-4 py-2"
                                        v-for="(column, index) in columns"
                                        :key="index"
                                        @click="toggleOrder(column)"
                                    >
                                        <span>{{ column }}</span>
                                        <span
                                            class="dv-table-column"
                                            v-if="column === query.column"
                                        >
                                            <span v-if="query.direction == 'asc'">&uarr;</span>
                                            <span v-if="query.direction == 'desc'">&darr;</span>
                                        </span>
                                    </th>
                                    <th width="40px" v-if="rol == 2"></th>
                                </thead>
                                <tbody class="text-gray-700 shadow-inner">
                                    <tr
                                        class="hover:bg-blue-400 hover:text-white cursor-pointer"
                                        v-for="(row, index) in model"
                                        :key="index"
                                    >
                                        <td @click="changeTime(row.id)">
                                            <input
                                                type="checkbox"
                                                :checked="employeesSelected.includes(row.id)"
                                                class="cursor-pointer"
                                            />
                                        </td>
                                        <td class="px-4 py-2" @click="showProfile(row.id)">
                                            {{ row.first_name }}
                                        </td>
                                        <td @click="showProfile(row.id)">{{ row.last_name }}</td>
                                        <td @click="showProfile(row.id)">{{ row.cod }}</td>
                                        <td class="text-xl" title="Crear acción de personal" v-if="rol == 2">
                                            <a :href="'/create/action/employee/' + row.id">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="isLoading">
                                        <td colspan="4">
                                            <div class="flex justify-center py-2">
                                                <div class="text-gray-700 text-center ">
                                                    <i
                                                        class="fa fa-spinner fa-pulse fa-spin text-blue-500 fa-3x"
                                                        aria-hidden="true"
                                                    ></i>
                                                    <div class="logo">Cargando...</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="model.length == 0 && isLoading == false">
                                        <td colspan="4">
                                            <p class="text-center mt-4">
                                                No se encontró ningún registro
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="dv-footer flex justify-between mt-5" v-if="model.length > 0">
                            <div>
                                <span
                                    >Mostrando {{ meta.from }} - {{ meta.to }} de
                                    {{ meta.total }} empleados</span
                                >
                            </div>
                            <div>
                                <button
                                    type="button"
                                    class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow"
                                    @click="prev()"
                                    :disabled="links.prev == null"
                                >
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </button>
                                <button
                                    class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow"
                                    @click="next()"
                                    :disabled="links.next == null"
                                >
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <modal name="modal-timestable" height="auto" @closed="modalClosed">
            <div class="py-4 px-4">
                <div class="flex header-time mb-8">
                    <h2 class="text-xl text-blue-900">{{ timeTitle }}</h2>
                </div>
                <div class="body-time mb-8">
                    <div class="flex">
                        <div class="flex-1">
                            <label for="time_in" class="font-bold text-gray-600"
                                >Hora entrada</label
                            >
                            <input
                                type="time"
                                class="form-input w-3/4"
                                name=""
                                id=""
                                v-model="timein"
                                min="00:00"
                                max="23:59"
                            />
                        </div>
                        <div class="flex-1">
                            <label for="time_out" class="font-bold text-gray-600"
                                >Hora salida</label
                            >
                            <input
                                type="time"
                                class="form-input w-3/4"
                                name=""
                                id=""
                                v-model="timeout"
                                min="00:00"
                                max="23:59"
                            />
                        </div>
                    </div>
                </div>
                <div class="footer-time ">
                    <div class="flex justify-end">
                        <button
                            class="btn bg-blue-600 text-white hover:bg-blue-500"
                            @click="saveTimestable()"
                        >
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </modal>
        <modal name="modal-change-employees" height="auto">
            <div class="py-4 px-4">
                <div class="flex header-change-employees mb-8">
                    <h2 class="text-xl text-blue-900">Cambiar horario</h2>
                </div>
                <div class="body-change-employees mb-8">
                    <label for="selectTimestables">Selecciona el horario</label>
                    <select class="form-select w-full" v-model="selectTime" id="selectTimestables">
                        <option v-for="(time, index) in timestables" :value="time.id" :key="index"
                            >{{ time.in }} - {{ time.out }}</option
                        >
                    </select>
                </div>
                <div class="footer-change-employees">
                    <div class="flex justify-end">
                        <button
                            class="btn bg-blue-600 text-white hover:bg-blue-500"
                            @click="saveChamgesEmployees()"
                        >
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>
<script>
export default {
    props: ['title', 'source', 'columns', 'rol'],
    data() {
        return {
            timeTitle: '',
            idtimestable: '',
            model: [],
            isLoading: false,
            meta: [],
            links: [],
            employeesSelected: [],
            timestables: [],
            timein: '08:00',
            timeout: '17:30',
            selectTime: '',
            token: '',
            query: {
                page: 1,
                column: 'nombre',
                direction: 'desc',
                per_page: 10,
                // search_operator: 'equal',
                search_input: '',
            },
            /* operators:{
                equal: '=',
                not_equal: '<>',
                less_than: '<',
                grater_than: '>',
                less_than_or_equal_to: '<=',
                grater_than_or_equal_to: '>=',
                in: 'IN',
                like: 'LIKE'
            } */
        };
    },
    created() {
        this.fetchIndexData();
        this.fetchTimestable();
    },
    methods: {
        next() {
            if (this.links.next) {
                this.query.page++;
                this.fetchIndexData();
            }
        },
        prev() {
            if (this.links.prev) {
                this.query.page--;
                this.fetchIndexData();
            }
        },
        changeTime(employee) {
            const index = this.employeesSelected.indexOf(employee);
            if (index > -1) {
                this.employeesSelected.splice(index, 1);
            } else {
                this.employeesSelected.push(employee);
            }
        },
        updateTime(e) {
            this.timein = e.in;
            this.timeout = e.out;
            this.timeTitle = 'Editar horario';
            this.idtimestable = e.id;
            this.$modal.show('modal-timestable');
        },
        listEmployees(e) {
            this.idtimestable = e;
            this.fetchIndexData();
        },
        saveTimestable() {
            if (this.idtimestable) {
                axios
                    .put('/timestables/' + this.idtimestable, {
                        in: this.timein,
                        out: this.timeout,
                    })
                    .then(({ data }) => {
                        this.$modal.hide('modal-timestable');
                        swal(data, {
                            icon: 'success',
                            timer: 2000,
                            button: false,
                        });
                        this.fetchTimestable();
                    });
            } else {
                axios
                    .post('/timestables', {
                        in: this.timein,
                        out: this.timeout,
                    })
                    .then(({ data }) => {
                        this.$modal.hide('modal-timestable');
                        swal(data, {
                            icon: 'success',
                            timer: 2000,
                            button: false,
                        });
                        this.fetchTimestable();
                    });
            }
        },
        saveChamgesEmployees() {
            axios
                .post('/timestables/change', {
                    employees: this.employeesSelected,
                    timestable: this.selectTime,
                })
                .then(({ data }) => {
                    swal(data, {
                        icon: 'success',
                        timer: 2000,
                        button: false,
                    });
                    this.employeesSelected = [];
                    this.idtimestable = '';
                    this.selectTime = '';
                    this.$modal.hide('modal-change-employees');
                    this.fetchIndexData();
                });
        },
        toggleOrder(column) {
            if (column === this.query.column) {
                if (this.query.direction === 'desc') {
                    this.query.direction = 'asc';
                } else {
                    this.query.direction = 'desc';
                }
            } else {
                this.query.column = column;
                this.query.direction = 'asc';
            }
            this.fetchIndexData();
        },
        showFormChange() {
            this.$modal.show('modal-change-employees');
        },
        fetchIndexData() {
            let vm = this;
            vm.isLoading = true;
            vm.model = [];
            axios
                .get(
                    `${this.source}?column=${this.query.column}&direction=${this.query.direction}&page=${this.query.page}
            &per_page=${this.query.per_page}&search_input=${this.query.search_input}&time=${this.idtimestable}`,
                )
                .then(response => {
                    vm.model = response.data.data;
                    vm.meta = response.data.meta;
                    vm.links = response.data.links;
                })
                .catch(error => {
                    toastr.error('Ocurrió un problema por favot intentalo de nuevo');
                })
                .finally(() => {
                    vm.isLoading = false;
                });
        },
        fetchTimestable() {
            axios.get('/apitime').then(({ data }) => {
                this.timestables = data;
            });
        },
        showProfile(employee) {
            if (this.rol == 3) {
                window.location.href = '/employees/' + employee;
            }
        },
        newTimestable() {
            this.timeTitle = 'Nuevo horario';
            this.$modal.show('modal-timestable');
        },
        modalClosed() {
            this.idtimestable = '';
            (this.timeTitle = ''), (this.timein = '08:00'), (this.timeout = '17:30');
        },
    },
    mounted() {
        this.token = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    },
};
</script>
