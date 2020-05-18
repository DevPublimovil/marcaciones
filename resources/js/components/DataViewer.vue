<template>
    <div class="dv">
        <div class="dv-header flex justify-between">
            <div class="dv-header-title">
                {{ title }}
            </div>
            <div class="dv-header-columns">
                <select class="dv-header-select"> 
                    <option v-for="(column, index) in columns" :key="index">{{ column }}</option>
                </select>
            </div>
            <div class="dv-header-operators">
                <select class="dv-header-select">
                    <option v-for="(operator, index) in operators" :key="index">{{ operator }}</option>
                </select>
            </div>
            <div class="dv-header-search">
                <input type="text" class="dv-header-input" placeholder="Buscar">
            </div>
            <div class="dv-header-submit">
                <button class="dv-header-btn">Buscar</button>
            </div>
        
        </div>
        <div class="dv-body">
            <table class="dv-table">
                <thead>
                    <th v-for="(column, index) in columns" :key="index" @click="toggleOrder(column)">
                        <span>{{ column }}</span>
                        <span class="dv-table-column" v-if="column === query.column">
                            <span v-if="query.direction == 'asc'">&uarr;</span>
                            <span v-if="query.direction == 'desc'">&darr;</span>
                        </span>
                    </th>
                </thead>
                <tbody>
                    <tr v-for="row in model.data">
                        <td v-for="(value, key) in row" :key="key">
                            {{ value }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dv-footer">
            <div class="dv-footer-item">
                <span>Mostrando {{ model.from }} - {{ model.total }}</span>
            </div>
            <div class="dv-footer-item">
                <span>Mostrar</span>
                <input type="text" v-model="query.per_page" class="dv-footer-input" @keyup.enter="fetchIndexData()">
            </div>
            <div class="dv-footer-sub">
                <button class="" @click="prev()">&laquo;</button>
                <button class="" @click="next()">&raquo;</button>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props:['title','source'],
    data(){
        return{
            model: [],
            columns: [],
            query:{
                page: 1,
                column: 'id',
                direction: 'desc',
                per_page: 10
            },
            operators:{
                'equal': '=',
                'not_equal': '<>',
                'less_than': '<',
                'grater_than': '>',
                'less_than_or_equal_to': '<=',
                'grater_than_or_equal_to': '>='
            }
        }
    },
    created(){
        this.fetchIndexData()
    },
    methods: {
        next(){
            if(this.model.next_page_url)
            {
                this.query.page++
                this.fetchIndexData()
            }
        },
        prev(){
            if(this.model.prev_page_url)
            {
                this.query.page--
                this.fetchIndexData()
            }
        },
        toggleOrder(column){
            if(column === this.query.column)
            {
                if(this.query.direction === 'desc')
                {
                    this.query.direction = 'asc'
                }
                else
                {
                    this.query.direction = 'desc'
                }
            }
            else{
                this.query.column = column
                this.query.direction = 'asc'
            }
            this.fetchIndexData()
        },
        fetchIndexData(){
            let vm = this
            axios.get(`${this.source}?column=${this.query.column}&direction=${this.query.direction}&page=${this.query.page}
            &per_page=${this.query.per_page}&search_input`).then(response =>{
                vm.model = response.data.model
                vm.columns = response.data.columns
            })
        },
    },
}
</script>