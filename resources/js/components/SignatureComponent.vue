<template>
    <div class="signature-component">
        <div class="container-lg mx-auto w-full py-2">
               <div id="signature-pad" class="signature-pad">
                    <div class="signature-pad--body mx-auto w-full">
                       <div class="flex w-full lg:w-2/4 xl:w-2/5 mx-auto items-center align-middle">
                            <div class="flex-1 hidden md:block mb-2 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold">Consejo</p>
                                <p> - Asegurate de cubrir la mayor área posible del recuadro</p>
                                <p>
                                    - Para obtener un mejor resultado puedes crear tu firma desde tu teléfono
                                </p>
                            </div>
                            <div class="flex-1 block md:hidden mb-2 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold">Consejo</p>
                                <p> - Asegurate activar la rotación de tu teléfono</p>
                            </div>
                       </div>
                        <div v-show="showgif" class="block md:hidden">
                            <img src="/images/turnphone.gif"  alt="">
                        </div>
                        <div v-show="!showgif">
                            <div class="w-4/5 lg:w-2/4 xl:w-2/5 h-64 border border-gray-500 rounded-lg mx-auto">
                                <canvas class="w-full h-64 border-gray-700 rounded-lg" id="mycanvas"></canvas>
                            </div>
                            <div class="signature-pad-footer w-4/5 lg:w-2/4 xl:w-2/5 mx-auto mt-1">
                                <div class="flex signature-pad-actions items-end content-end">
                                    <div class="flex-1 text-right">
                                        <button type="button" class="btn bg-white border border-gray-500 focus:outline-none hover:text-white hover:bg-blue-500" data-action="clear" title="Limpiar lienzo"><i class="fa fa-undo" aria-hidden="true"></i> Limpiar</button>
                                        <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" data-action="save-svg"><i class="fa fa-spinner fa-spin fa-fw" :class="[showIcon ? '' : 'hidden']"></i> Guardar firma</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>
<script>
import SignaturePad from 'signature_pad'
export default {
    props:['user'],
    data() {
        return {
            showIcon:false,
            showgif: true
        }
    },
    mounted() {
    let vm = this
    var wrapper = document.getElementById("signature-pad");
    var clearButton = wrapper.querySelector("[data-action=clear]");
    var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
    var canvas = wrapper.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    function resizeCanvas() {

    var ratio =  Math.max(window.devicePixelRatio || 1, 1);

    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    }

    window.onresize = resizeCanvas;
    resizeCanvas();

    function download(dataURL, filename) {
        if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
            window.open(dataURL);
        } else {
            var blob = dataURLToBlob(dataURL);
            var url = window.URL.createObjectURL(blob);

            var a = document.createElement("a");
            a.style = "display: none";
            a.href = url;
            a.download = filename;

            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
        }
    }

    function dataURLToBlob(dataURL) {
        var parts = dataURL.split(';base64,');
        var contentType = parts[0].split(":")[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;
        var uInt8Array = new Uint8Array(rawLength);

        for (var i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uInt8Array], { type: contentType });
    }

    clearButton.addEventListener("click", function (event) {
        signaturePad.clear();
    });

    saveSVGButton.addEventListener("click", function (event) {
        var dataURL = signaturePad.toDataURL();
        if (signaturePad.isEmpty() || String(dataURL).length < 10000) {
            toastr.info('Debes de dibujar tu firma')
        } else {
            vm.showIcon = true
            axios.put('/employees/firm/' + vm.user,{
                imagefirma: dataURL
            }).then(({data})=>{
                toastr.success('Tu firma se guardo con éxito')
                if(data == 'empleado'){
                    window.location.href = '/home'
                }else{
                    window.location.href = '/actions'
                }
            }).catch(response =>{
                toastr.error('Ocurrió un problema intentalo de nuevo')
            }).finally(()=>{
                vm.showIcon = false
            })
        }
    });

        window.addEventListener("orientationchange", function() {
            if(window.orientation == 90 || window.orientation == -90){
                vm.showgif = false
            }else{
                vm.showgif = true
            }
        }, false);
    },

    created() {
        let vm = this
        if(window.screen.width > 768){
            vm.showgif = false
        }else{
            if(window.orientation == 90 || window.orientation == -90){
                vm.showgif = false
            }else{
                vm.showgif = true
            }
        }
    },
}
</script>

<style>
    #mycanvas{
        background-color: white;
    }
</style>