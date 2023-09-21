let url = window.location.href;
document.addEventListener('DOMContentLoaded', function() {
    TODO: //ver la forma de pasar el rol de usuario de php a este archivo js
    //si es edit y el usuario no es admin o dev no dejo editar datos personales
    if (url.includes('crudAction=edit') && 
        !$('.user-name')[0].innerText.includes('(Adm)') &&
        !$('.user-name')[0].innerText.includes('(Dev)')) 
    {
        $('#User_apellido').prop('readonly', true);
        $('#User_nombre').prop('readonly', true);
        $('#User_dni').prop('readonly', true);
    }
});
    
