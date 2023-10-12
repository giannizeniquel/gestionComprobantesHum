let url = window.location.href; 
if(url.includes('crudAction=new') || url.includes('crudAction=edit')) {
    document.addEventListener('DOMContentLoaded', function() {
        const select_reclamoPago = document.getElementById('Reclamo_pago');
        const mensajes = document.getElementsByTagName('textarea');
        if(select_reclamoPago) {
            if (url.includes('crudAction=new')) {
                //admin no crea reclamos, este contro es solo para ROLE_USER
                select_reclamoPago.selectedIndex = 1;
                select_reclamoPago.setAttribute('placeholder', select_reclamoPago[1].innerText);
                select_reclamoPago.setAttribute('required', true);
                $(select_reclamoPago[0]).remove(); //elimino el option vacio

            }else if (url.includes('crudAction=edit')){
                for (let i = 0; i < mensajes.length; i++) {
                    mensajes[i].setAttribute('readonly', true);
                }
            }
            select_reclamoPago.setAttribute('hidden', true);
            let pagoId = select_reclamoPago.value;
            let spanIdPago = document.createElement('span');
            spanIdPago.innerText = pagoId;
            select_reclamoPago.after(spanIdPago);
        }
    });
}else if (url.includes('crudAction=detail')){
    document.addEventListener('DOMContentLoaded', function() {
        //elimino botones de borrar y editar
        //los reclamos no se borran, solo se cierran con su campo estado en false
        $('.action-edit').remove();
        $('.action-delete').remove();
    });
}