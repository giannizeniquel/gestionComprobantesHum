let url = window.location.href;
let arrayTipoCurso=[];
if(url.includes('crudAction=new') || url.includes('crudAction=edit')) {
    document.addEventListener("DOMContentLoaded", function() {
        let selectCarrera = $('#Curso_carrera');
        let selectTipo = $('#Curso_tipo')[0];   
        selectCarrera.on('change', function() {
            let selectedCarreraId = selectCarrera.val();
                for (let i = 0; i < selectTipo.length; i++) {
                selectTipo[i].style.display="none";
            }
            selectTipo.value="";

            if (selectedCarreraId) {
                    const formData = new FormData();
                    formData.append('carreraId', selectedCarreraId);
                    fetch('obtener_carrera', {
                    method: 'POST',
                    body: formData,
                    })
                    .then(response => {
                       return response.json();
                    })
                    .then(data => {   
                        arrayTipoCurso =  Object.keys(data).map( key => ( data[key].toString()) );
                     for (let j = 0; j < arrayTipoCurso.length; j++) {

                        for (let i = 1; i < selectTipo.length; i++) {
                         
                                if (selectTipo[i].value!=arrayTipoCurso[j] && selectTipo[i].style.display!="block") {
                                   selectTipo[i].style.display="none";
                                }else{
                                   selectTipo[i].style.display="block";
                                }
                            }  
                        }                        
                     })
                    .catch(error => { console.log(error);
                                     }) 
                   
            } else {
             
            }
        });
  });
}
