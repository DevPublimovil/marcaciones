<template>
    <div>
        <li>
            <div class="relative inline-block" @mouseover="isVisible = true" @mouseleave="isVisible = false" @keydown.enter="isVisible = !isVisible">
                <button class="block h-8 w-8 rounded-full overflow-hidden border-2 border-gray-600 focus:outline-none focus:border-white">
                    <img class="h-full w-full object-cover"  :src="'/storage/'+info.avatar" alt="username">
                </button>
                <transition enter-active-class="transition duration-300 ease-out transform" enter-class="-translate-y-3 scale-95 opacity-0" enter-to-class="translate-y-0 scale-100 opacity-100" leave-active-class="transition duration-150 ease-in transform" leave-class="translate-y-0 opacity-100" leave-to-class="-translate-y-3 opacity-0">
                <div v-show="isVisible" class="fixed pt-2 w-1/5 top-auto right-0">
                    <div class="relative py-1 bg-white border border-gray-200 rounded-md shadow-xl">
                    <div class="relative top-0 w-4 h-4 origin-center transform rotate-45 translate-x-5 -translate-y-2 bg-white border-t border-l border-gray-200 rounded-sm pointer-events-none"></div>
                    <div class="relative text-center">
                        <span class="block w-full px-4 py-2 font-medium text-gray-700 whitespace-no-wrap hover:bg-gray-100 focus:outline-none hover:text-gray-900 focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out">{{ info.name }}</span>
                        <a href="#" class="block w-full bg-red-500 text-white rounded px-4 py-2 font-medium whitespace-no-wrap hover:bg-red-400 focus:outline-none focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out" @click="logout()" >
                            <i class="fa fa-power-off" aria-hidden="true"></i> Cerrar sesión
                        </a>
                    </div>
                    </div>
                </div>
                </transition>
            </div>
        </li>
        <form action="/logout" method="post" style="display:none" id="form-logout">
            <input type="hidden" name="_token" :value="csrf">
        </form>
    </div>
</template>

<script>
export default {
    props:['info'],
    data(){
        return{
            csrf: '',
            isVisible: false,
        }
    },
    methods: {
        logout(){
            event.preventDefault()
            document.getElementById('form-logout').submit()
        },
    },
    mounted() {
        this.csrf = document.querySelector('meta[name="csrf-token"]').content
    }
}
</script>