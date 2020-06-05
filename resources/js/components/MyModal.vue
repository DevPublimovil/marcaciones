<template>
    <div class="modal-component">
        <div class="main-modal fixed w-full h-100 inset-0 overflow-hidden flex justify-center items-center animated fadeIn faster"
		style="background: rgba(0,0,0,.7);">
            <div class="border border-teal-500 shadow-lg modal-container bg-white w-11/12 xl:max-w-2xl mx-auto h-auto rounded shadow-lg overflow-y-auto">
                <div class="modal-content py-4 text-left px-6">
                    <div class="flex justify-between items-center pb-3">
                        <slot name="header-modal"></slot>
                        <div class="modal-close cursor-pointer z-50" @click="$emit('close')">
                            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 18 18">
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="my-5">
                       <slot name="body-modal"></slot>
                    </div>
                    <div class="flex justify-end pt-2">
                       <slot name="footer-modal"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            
        }
    }
}
</script>
<style>
    .animated {
        z-index: 1050;
    }
</style>