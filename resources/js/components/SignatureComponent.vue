<template>
    <div class="signature-component">
        <div class="container mx-auto py-2">
               <div id="signature-pad" class="signature-pad mt-20">
                    <div class="signature-pad--body mx-auto w-3/4">
                       <div class="flex items-center align-middle content-center">
                            <div class="flex-auto">
                                <h3 class="text-blue-900 text-xl font-bold mb-1">DIBUJA TU FIRMA</h3>
                            </div>
                            <div class="flex-auto mb-2 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                <p class="font-bold">Consejo</p>
                                <p>Dibuja tu firma en el centro del recuadro para obtener mejores resultados.</p>
                            </div>
                       </div>
                        <canvas class="w-full h-64 border-gray-700"></canvas>
                        <div class="signature-pad--footer mt-1">
                            <div class="flex signature-pad--actions items-end content-end">
                                <div class="flex-1 text-right">
                                    <button type="button" class="btn bg-white border border-gray-500 hover:text-white hover:bg-blue-500" data-action="clear" title="Limpiar lienzo"><i class="fa fa-undo" aria-hidden="true"></i> Limpiar</button>
                                    <button type="button" class="btn bg-blue-500 hover:bg-blue-600 text-white" data-action="save-svg"><i class="fa fa-spinner fa-spin fa-fw" :class="[showIcon ? '' : 'hidden']"></i> Guardar firma</button>
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
            showIcon:false
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

    signaturePad.clear();
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
        if (signaturePad.isEmpty()) {
            toastr.info('Debes de dibujar tu firma')
        } else {
            vm.showIcon = true
            var dataURL = signaturePad.toDataURL();
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

    },
}
</script>