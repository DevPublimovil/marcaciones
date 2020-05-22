<template>
    <div class="data-table py-2">
        <div class="flex justify-between dt-header-table">
            <div>
                <h3>{{ employee.first_name+ employee.last_name }} | {{ employee.cod }}</h3>
            </div>
            <div>
                <button type="button" class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="$emit('prev')"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                <button type="button" class="bg-white hover:bg-blue-100 text-gray-800 font-semibold py-1 px-4 border border-gray-400 rounded shadow" @click="$emit('close')"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="dt-table">
            <table class="table-auto w-full text-center">
                <thead>
                    
                </thead>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    props:['employee'],
    data(){
        return{

        }
    }
}
</script>