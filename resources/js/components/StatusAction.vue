<template>
    <div class="status-action-component">
        <div class="container-md mx-auto py-2">
            <div class="card-status w-10/12 mx-auto">
                <div class="flex card-header-status align-center items-center align-middle content-center h-12">
                    <h3 class="font-bold text-xl text-blue-900 mx-2">Historial de acciones de personal</h3>
                </div>
                <div class="card-body-status flex bg-white border border-gray-200 rounded shadow" v-if="actions.length > 0">
                    <div class="timeline w-full relative mt-5 mx-2 p-0" v-if="actions.length > 0">
                        <div v-for="(action, index) in actions" :key="index">
                            <div class="time-label">
                                <span class="text-white shadow-none bg-gray-600 inline-block font-bold p-2 rounded">{{ action.created_at }}</span>
                            </div>
                            <div class="mt-2">
                                <div class="timeline-item rounded-lg border border-gray-400 text-gray-600 ml-16 p-0 mt-0 relative">
                                    <div class="w-full py-4">
                                        <div class="flex justify-center" @click="viewPdf(action.id)">
                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div class="w-10 h-10 mx-auto cursor-pointer bg-blue-500 rounded-full text-lg text-white flex items-center" title="Accion de personal creada">
                                                        <span class="text-center text-white w-full">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="text-xs text-center md:text-base">Accion de personal enviada</div>
                                            </div>

                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div class="absolute flex align-center items-center align-middle content-center" style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                                                    <div class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1">
                                                        <div class="w-0  py-1 rounded" :class="[ action.check_gte == 1 ? 'bg-blue-500' : action.check_gte == 3 ? 'bg-blue-500' : 'bg-gray-300']" style="width: 100%;"></div>
                                                    </div>
                                                    </div>
                                                
                                                    <div class="w-10 h-10 mx-auto cursor-pointer rounded-full text-lg text-white flex items-center" :class="[ action.check_gte == 1 ? 'bg-blue-500' : action.check_gte == 3 ? 'bg-red-500' : 'bg-gray-300']" :title="[ action.check_gte == 1 ? 'Tu accion de personal ha sido aprobado por tu jefe' : action.check_gte == 3 ? 'Tu acción de personal ha sido rechazada' : 'Tu accion de personal aún no ha sido aprobado por tu jefe' ]">
                                                    <span class="text-center text-white w-full">
                                                        <i class="fa" :class="[ action.check_gte == 1 ? 'fa-check-circle' : action.check_gte == 3 ? 'fa-times-circle' : 'fa-times-circle' ]" ></i>
                                                    </span>
                                                    </div>
                                                </div>

                                                <div class="text-xs text-center md:text-base">Gte. {{action.check_gte == 1 ? 'aprobada' : action.check_gte == 3 ? 'no aprobada' : 'pendiente'}}</div>
                                            </div>

                                            <div class="w-1/4">
                                                <div class="relative mb-2">
                                                    <div class="absolute flex align-center items-center align-middle content-center" style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                                                    <div class="w-full bg-gray-200 rounded items-center align-middle align-center flex-1">
                                                        <div class="w-0  py-1 rounded" :class="[ action.check_rh == 1 ? 'bg-blue-500' : action.check_rh == 3 ? 'bg-blue-500' : 'bg-gray-300']" style="width: 100%"></div>
                                                    </div>
                                                    </div>

                                                    <div class="w-10 h-10 mx-auto cursor-pointer rounded-full text-lg text-white flex items-center" :class="[ action.check_rh == 1 ? 'bg-blue-500' : action.check_rh == 3 ? 'bg-red-500' : 'bg-gray-300']" :title="[ action.check_rh == 1 ? 'Tu accion de personal ha sido aprobado por tu jefe' : action.check_rh == 3 ? 'Tu acción de personal ha sido rechazada' : 'Tu accion de personal aún no ha sido aprobado por tu jefe' ]">
                                                    <span class="text-center text-white w-full">
                                                        <i class="fa" :class="[ action.check_rh == 1 ? 'fa-check-circle' : action.check_rh == 3 ? 'fa-times-circle' : 'fa-times-circle' ]"></i>
                                                    </span>
                                                    </div>
                                                </div>

                                                <div class="text-xs text-center md:text-base">RH. {{action.check_rh == 1 ? 'aprobada' : action.check_rh == 3 ? 'no aprobada' : 'pendiente'}}</div>
                                            </div>
                                        </div>
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
</template>
<script>
export default {
    props:['user'],
    mounted(){
        this.fetchData();
    },
    data() {
        return {
            actions:[]
        }
    },
    methods: {
        fetchData(){
            axios.get('/apiactions/employee/' + this.user.id).then(response => {
                this.actions = response.data
            }).catch(response =>{
                console.log(response)
            })
        },
        viewPdf(action){
            window.open('/actions/' + action, '_blank')
        }
    },
}
</script>