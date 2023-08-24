let arr_cuotas = []; // declaro array de cuotas
let arr_cuotas_pagadas = []; // declaro array de cuotas
let url = window.location.href;
if(url.includes('crudAction=new') || url.includes('crudAction=edit')) {
    document.addEventListener('DOMContentLoaded', function() {
        const add_detalle = document.getElementsByClassName('field-collection-add-button')[0]; // Cambia esto al selector adecuado
        const select_pagoCurso = document.getElementById('Pago_curso');
        if(select_pagoCurso) {
            select_pagoCurso.selectedIndex = 1;
            select_pagoCurso.setAttribute('placeholder', select_pagoCurso[1].innerText);
            select_pagoCurso.setAttribute('required', true);
            $(select_pagoCurso[0]).remove(); //elimino el option vacio
            $('#Pago_curso').select2({
                minimumResultsForSearch: -1, //elimina la busqueda
            });
        }
        
        
        add_detalle.addEventListener('click', function() {
            const idCurso = document.getElementById('Pago_curso').value;
            const formData = new FormData();
            let selectsCuotas = add_detalle.parentElement.children[0].getElementsByTagName('select')
            formData.append('idCurso', idCurso);
            fetch(`obtenerCuotasDeCurso`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    return response.json();
                })
                .then(totalCuotas => {

                    arr_cuotas = totalCuotas[0]['cuotasData'];
                    arr_cuotas_pagadas = totalCuotas[0]['cuotasPagadasData'];

                    let idsNoPermitidos = arr_cuotas_pagadas.map(idCuota => idCuota.idCuota);

                    //recorremos todos los select cuotas
                    for (let i = 0; i < selectsCuotas.length; i++) {
                        //recorremos options de cada select
                        for (let j = 0; j < selectsCuotas[i].length; j++) {
                            //recorremos cuotas pagadas
                            for (let k = 0; k < idsNoPermitidos.length; k++) {
                                //filtramos las cuotas que ya estan pagadas
                                if(selectsCuotas[i][j].value === idsNoPermitidos[k].toString()) {
                                    selectsCuotas[i][j].remove();
                                }
                            }
                        }
                    }
  
                    //asigamos la libreria select2 a los inputs de cuotas
                    $('.select2_cuotas').select2({
                        placeholder: "Seleccione cuota/s",
                        allowClear: true
                    });
                })
                .catch(error => { 
                    console.log("error: "+error);
                })
        });
    } );
}


function calcularMontoCuotas(selectCuotas) {
    let detalle_parent = selectCuotas.parentElement.parentElement.parentElement.parentElement;
    let id_detalle = detalle_parent.id;
    let input_montoCuotas = document.getElementById(id_detalle+'_montoCuotas');
    input_montoCuotas.value = 0;
    let array_cuotasSelecteds = [];
    array_cuotasSelecteds = $(selectCuotas).find(':selected') //cuotas que elijo en el select2

    //arr_cuotas se declara en la primera linea y le damos valor en la lnea 27
    //contiene todas las cuotas del curso obtenidas del ajax
    for (let i = 0; i < arr_cuotas.length; i++) { // array de todas las cuotas del curso
        for (let j = 0; j < array_cuotasSelecteds.length; j++) {
            if (arr_cuotas[i].idCuota == array_cuotasSelecteds[j].value) {
                input_montoCuotas.value =  parseFloat(input_montoCuotas.value) +  parseFloat(arr_cuotas[i].monto);
            }
        }
    }
}
    
    