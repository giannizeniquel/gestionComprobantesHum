document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementsByClassName('field-collection-add-button')[0]; // Cambia esto al selector adecuado
    
    //const resultDiv = document.getElementById('ajax-result'); // Cambia esto al selector adecuado

    button.addEventListener('click', function() {
        const idCurso = document.getElementById('Pago_curso').value;
        const formData = new FormData();
        let responseData;
        formData.append('idCurso', idCurso);
        console.log(idCurso);
        fetch(`obtenerCuotasDeCurso`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                return response.json();
            })
            .then(cuotasData => {
                //resultDiv.textContent = data.message;
                console.log("cuotasData:", cuotasData);
            })
            .catch(error => { 
                console.log("error: "+error);
            })
    });
} );
    
    