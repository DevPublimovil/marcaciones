<template>
    <div class="form-personal-action">
         <div class="container-lg mx-auto py-4">
            <div class="w-11/12 mx-auto shadow-lg uppercase pt-2 bg-gray-300 rounded">
                <h5 class="text-xl text-right py-2 px-4 text-blue-900">Accion de personal</h5>
                <div class="bg-white p-6">
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
                                <input type="checkbox" class="form-checkbox bg-gray-400" @click="showOther ? showOther = false : showOther = true">
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
            description:'',
            errors: [],
            classButton: 'bg-blue-700 btnsweet',
            showOther: false
        }
    },
    methods: {
        createPersonalAction(){
            let vm = this
            axios.post('/actions/',{
                actions: vm.typeactions,
                otherAction: vm.other,
                description: vm.description
            }).then(response => {
                swal(response.data, "Puedes revisar su estado en tu historial", "success").then((value) =>{
                    if(value)
                    {
                        window.location.href = "/home"
                    }
                })
            })
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
                this.showAlert('La descipcion de su accion de personal es obligatoria')
            }else{
                this.createPersonalAction()
            }

        }
    },
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0
}
</style>