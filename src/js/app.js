let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    tabs() //Cambia la seccion cuando se presionen los tabs
    botonesPaginador() //Agrega o quita los botones del paginador
    paginaSiguiente()
    paginaAnterior()
}

function tabs() {
    const botones = document.querySelectorAll(".tabs button");
    botones.forEach( (boton) => {
        boton.addEventListener("click", function(e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarseccion(paso);
            botonesPaginador();
        });
    })
}

function mostrarseccion(paso) {
    var divs = document.getElementsByClassName("seccion");
    //Ocultamos todos los divs
    for (var i = 0; i < divs.length; i++) {
        divs[i].classList.remove("mostrar");
        divs[i].classList.add("ocultar");
    }
    //Selecionamos la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add("mostrar");
    seccion.classList.remove("ocultar");
    
    //Quitamos la clase del tab anterior
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior) {
        tabAnterior.classList.remove("actual");
    }
    //Resaltamos el boton que hemos hecho click
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add("actual");
}  

function botonesPaginador() {
    const paginaSiguiente = document.getElementById("siguiente");
    const paginaAnterior = document.getElementById("anterior");

    if (paso === 1) {
        paginaAnterior.classList.add("ocultar");
        paginaSiguiente.classList.remove("ocultar");
    } else if(paso === 3) {
        paginaAnterior.classList.remove("ocultar");
        paginaSiguiente.classList.add("ocultar");
    } else {
        paginaAnterior.classList.remove("ocultar");
        paginaSiguiente.classList.remove("ocultar");
    }
    mostrarseccion(paso);
}

function paginaSiguiente() {
    const paginaSiguiente = document.getElementById("siguiente")
    paginaSiguiente.addEventListener("click", function() {
        if (paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}

function paginaAnterior() {
    const paginaAnterior = document.getElementById("anterior");
    paginaAnterior.addEventListener("click", function() {
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador(); 
    })
}