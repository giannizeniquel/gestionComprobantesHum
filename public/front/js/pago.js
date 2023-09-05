let url = window.location.href;
let arr_cuotas = []; // declaro array de cuotas
let arr_cuotas_pagadas = []; // declaro array de cuotas
let detalles;
let selectsCuotasDetalles;
let inputsDetalles;
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

        if(url.includes('crudAction=edit')){
            detalles = document.getElementById('Pago_pagoDetalles');
            inputsDetalles = detalles.getElementsByTagName('input');
            selectsCuotasDetalles = detalles.getElementsByTagName('select');
            for (let i = 0; i < selectsCuotasDetalles.length; i++) {
                $('#'+selectsCuotasDetalles[i].id).select2();
            }
            for (let i = 0; i < inputsDetalles.length; i++) {
                if(inputsDetalles[i].type == 'file') {
                    inputsDetalles[i].removeAttribute('required');
                }
            }
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
                    if(url.includes('crudAction=edit')){
                        for (let i = 0; i < selectsCuotasDetalles.length; i++) {
                            //agrego los id de los <option> que vienen en los detalles al array de ids ya pagados
                            idsNoPermitidos.push(selectsCuotasDetalles[i].id);
                        }
                    }
                    //recorremos todos los select cuotas
                    for (let i = 0; i < selectsCuotas.length; i++) {
                        //recorremos options de cada select
                        for (let j = 0; j < selectsCuotas[i].length; j++) {
                            //recorremos cuotas pagadas
                            for (let k = 0; k < idsNoPermitidos.length; k++) {
                                if(url.includes('crudAction=edit')){
                                    //recorro los selects que vienen en los detalles
                                    for (let z = 0; z < selectsCuotasDetalles.length; z++) {
                                        //pregunto si existe el <option>
                                        if(selectsCuotas[i][j]){ 
                                            //filtramos las cuotas que ya estan pagadas
                                            //selectsCuotas[i][j].selected !== true -> esta condicion permite que no se eliminen los options que vienen seleccionados
                                            if(selectsCuotas[i][j].value === idsNoPermitidos[k].toString() && selectsCuotas[i][j].selected !== true) {
                                                if(selectsCuotas[i][j].id != selectsCuotasDetalles[z].id){
                                                    selectsCuotas[i][j].remove();
                                                }       
                                            }
                                        }
                                    }
                                }else{
                                    //filtramos las cuotas que ya estan pagadas, para el new no hay mas condiciones
                                    if(selectsCuotas[i][j].value === idsNoPermitidos[k].toString()) {
                                        selectsCuotas[i][j].remove();
                                    }
                                }
                            }
                        }
                    }
  
                    //asigamos la libreria select2 a los inputs de cuotas
                    if(url.includes('crudAction=edit')){
                        //tomo el ultimo select del array, es que se agrega cuando aniadimos un nuevo detalle al pago
                        let ultimoSelect = selectsCuotas[selectsCuotas.length - 1];
                        $('#'+ultimoSelect.id).select2({
                            placeholder: "Seleccione cuota/s",
                            allowClear: true
                        });
                    }else{
                        $('.select2_cuotas').select2({
                            placeholder: "Seleccione cuota/s",
                            allowClear: true
                        });
                    }
                })
                .catch(error => { 
                    console.log("error: "+error);
                })
        });
    });
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
    
    