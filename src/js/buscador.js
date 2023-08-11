document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const inputFecha = document.getElementById("fecha");
    inputFecha.addEventListener("input", function(e) {
        const fechaSelecionada =  e.target.value;
        window.location = `?fecha=${fechaSelecionada}`;
    })
}