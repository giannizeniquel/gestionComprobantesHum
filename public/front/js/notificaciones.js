const socket = new WebSocket("ws://localhost:8080");
 
socket.addEventListener("open", function() {
    console.log("CONNECTED");
});

socket.addEventListener("message", function(e) {
    console.log(e.data);
    try
    {
        const message = JSON.parse(e.data);
        console.log(message.name+' dice:', message.message);
    }
    catch(e)
    {
        console.log(e.data);
    }
});

$("#new-Reclamo-form,#edit-Reclamo-form").submit(function (event) {
    const cajasMensajes = document.getElementsByTagName('textarea');
    let ultimoMensaje;
    for (let i = 0; i < cajasMensajes.length; i++) {
        if (cajasMensajes[i].readOnly != true) {
            ultimoMensaje = cajasMensajes[i];
        }
    }
    const message = {
        name: $('.user-name')[0].innerText,
        message: ultimoMensaje.value
    };
    socket.send(JSON.stringify(message));
    addMessage(message.name, message.message);
});
