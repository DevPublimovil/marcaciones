<template>
    <div class="form-personal-action">
         <div class="container mx-auto py-4">
            <div class="w-11/12 mx-auto">
                <h5 class="text-xl py-2 px-2 text-blue-900 font-bold">Nueva acción de personal</h5>
                <div class="bg-white p-6  shadow-lg uppercase pt-2 rounded">
                    <form id="formActions">
                        <h4 class="text-xl text-center text-gray-700">Faltas cometidas</h4>
                        <div class="form-control-ic text-base grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <div v-for="(type, index) in types" :key="index">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="typeactions" class="form-checkbox bg-gray-400" :value="type.id" >
                                    <span class="ml-2">{{ type.name_type_action}}</span>
                                </label>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="form-checkbox bg-gray-400" @click="otherAction()">
                                <span class="ml-2">Otros</span>
                            </label>
                        </div>
                        <transition name="fade">
                            <textarea class="form-textarea block border border-gray-500 w-full" rows="2" v-model="other" v-if="showOther" placeholder="Describe la falta cometida"></textarea>
                        </transition>

                        <div class="form-control-ic">
                            <h4 class="text-xl text-center text-gray-700 mb-6">Desripción de la acción</h4>
                            <textarea class="form-textarea border border-gray-500 w-full" rows="5" v-model="description" placeholder="Puedes escribir el dia que cometistes la falta"></textarea>
                        </div>
                        <div class="flex justify-center btn-group text-center">
                            <a :href="'/home'" class="btn text-blue-900 border border-blue-900 hover:bg-gray-200">
                                Cancelar
                            </a>
                            <button type="button" class="btn btn-blue mx-2" @click="checkForm()">
                               <i class="fa fa-spinner fa-spin" aria-hidden="true" v-if="loading"></i>
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
import swal from 'sweetalert'
export default {
    props:['user','types'],
    data(){
        return{
            typeactions:[],
            other:'',
            loading:false,
            description:'',
            errors: [],
            showOther: false
        }
    },
    methods: {
        createPersonalAction(){
            let vm = this
            vm.loading = true
            axios.post('/actions/',{
                actions: vm.typeactions,
                otherAction: vm.other,
                description: vm.description
            }).then(response => {
                swal(response.data, "Puedes revisar su estado en tu historial", "success").then((value) =>{
                    if(value)
                    {
                        window.location.href = "/actions"
                    }
                })
            }).catch(error => {
                toastr.error('Ocurrió un problema, intentalo de nuevo')
            }).finally(() => vm.loading = false)
        },
        showAlert(message){
           swal({
               icon: "warning",
               text: message,
               button: {
                   text: "ok",
               }
           });
        },
        checkForm(){
            if(!this.typeactions.length && !this.other){
              this.showAlert('Por favor selecciona al menos una falta cometida o describela en "Otros"')
            }else if(!this.description){
                this.showAlert('La descripción de su accion de personal es obligatoria')
            }else{
                this.createPersonalAction()
            }

        },
        otherAction(){
            if(this.showOther)
            {
                this.showOther = false
            }
            else
            {
                this.showOther = true
                this.other = ''
            }
        }
    },
}
</script>
<style>

.loading {
  border-top-color: #f1f0ef;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
