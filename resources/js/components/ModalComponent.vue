<template>
    <div class="modal-component">
        <modal name="hello-world">
            <div class="p-3 text-gray-500">
                <h5 class="text-center font-bold">Elige el periodo de marcaciones</h5>
                <div class="flex">
                    <div class="flex-1">
                        <datepicker input-class="form-input" calendar-class="fixed text-orange-400" placeholder="Selecciona la fecha"></datepicker>
                    </div>
                    <div class="flex-1"><datepicker input-class="form-input" placeholder="Selecciona la fecha"></datepicker></div>
                </div>
            </div>
            <!-- <datepicker placeholder="Select Date"></datepicker> -->
        </modal>
    </div>
</template> 

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    components: {
        Datepicker
    },
    data(){
        return{

        }
    },
}
</script>

<style>
    .vm--container{
        z-index: 98
    }
</style>