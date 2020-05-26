<<template>
    <div class="bad-progress">
        <transition name="slide-fade">
            <div class="mx-2" v-if="show">
                <div class="w-full py-2">
                    <p class="text-xs text-orange-500 mb-1">Porcentaje de llegadas tardías</p>
                    <div class="rounded bg-gray-500">
                        <div class="bg-barprogress rounded text-xs leading-none hover:bg-red-600 cursor-pointer py-1 text-center text-white" :style="{width:percent+'%'}">
                            {{percent + '%'}}
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import EventBus from '../eventbus.js';
export default {
    props:['employee'],
    data(){
        return{
            percent: 0,
            show: true,
        }
    },
    methods: {
        getPercent(){
            axios.get("/percent/" + this.employee).then(response => {
                this.percent = response.data
            })
        },
    },
    mounted() {
        this.getPercent();
        EventBus.$on('markings', period=> {
            if(period == 'semanal'){
                this.show = true
            }else{
                this.show = false
            }
        });
    },
}
</script>

<style>
    /* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}

.bg-barprogress{
    background: #f12711;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #f5af19, #f12711);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #f5af19, #f12711); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
</style>