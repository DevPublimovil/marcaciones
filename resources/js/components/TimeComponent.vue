<template>
    <div class="component-time">
        <!-- <li class="text-md font-bold h-12">Horarios</li> -->
        <div class="flex h-12 justify-between">
            <div>{{ time.in }} - {{ time.out}} </div>
            <div>
                <span class="mx-2 cursor-pointer hover:text-blue-500" @click="changeTime(time)" title="Editar horario"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                 <span class="mx-2 cursor-pointer hover:text-blue-500" @click="listEmployees(time.id)" title="Empleados"><i class="fa fa-users" aria-hidden="true"></i></span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'TimeComponent',
    props:['time'],
    data(){
        return{
            
        }
    },
    created() {
        
    },
    methods: {
        changeTime(time){
            this.$emit('update-timestable', time);
        },
        listEmployees(time){
            this.$emit('list-employees', time);
        }
    },
}
</script>