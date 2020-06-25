<template>
    <div class="component-time">
        <!-- <li class="text-md font-bold h-12">Horarios</li> -->
        <div class="flex h-12 justify-between">
            <div>{{ time.in }} - {{ time.out}} </div>
            <div>
                <span class="cursor-pointer hover:bg-blue-500 hover:text-white border rounded py-1 px-2" @click="deleteTime(time.id)" title="Eliminar horario" v-if="time.total == 0"><i class="fa fa-trash" aria-hidden="true"></i></span>
                <span class="cursor-pointer hover:bg-blue-500 hover:text-white border rounded py-1 px-2" @click="changeTime(time)" title="Editar horario"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                <span class="cursor-pointer hover:bg-blue-500 hover:text-white border rounded py-1 px-2" @click="listEmployees(time.id)" :title="time.total + '  Empleados'" :class="[currentTime == time.id ? 'text-blue-500' : '']"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;&nbsp;{{time.total}}</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'TimeComponent',
    props:['time','currentTime'],
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
        },
        deleteTime(time){
            this.$emit('delete-time', time);
        }
    },
}
</script>