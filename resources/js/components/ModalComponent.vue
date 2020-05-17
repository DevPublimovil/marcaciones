<template>
    <div class="modal-component">
        <modal name="hello-world">
            <datepicker placeholder="Select Date"></datepicker>
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