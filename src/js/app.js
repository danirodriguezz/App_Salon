
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: "",
    fecha: "",
    hora: "",
    servicios: []
}

document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    tabs() //Cambia la seccion cuando se presionen los tabs
    botonesPaginador() //Agrega o quita los botones del paginador
    paginaSiguiente()
    paginaAnterior()

    consultarAPI() //Consulta la API en el backend de PHP

    nombreCliente(); // Añade el nombre del cliente
    selecionarFecha(); //Añadimos la fecha que eliga el cliente
    selecionarHora(); //Añadimos la hora que eliga el cliente

    mostrarResumen(); //Muestra el resumen de la cita
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
        mostrarResumen();
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

async function consultarAPI() {
    try {
        //Obtenemos los datos de una api que se conecta con la base de datos
        const url = "http://localhost:8000/api/servicios";
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        //LLamamos a la funcion de mostrarServicios y le pasamos los servicios en formato json
        mostrarServicios(servicios);
    } catch(error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    // console.log(servicios);
    //Iteramos los servicios para crear un parrafo y unos div por cada uno de ellos
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            selecionarServicio(servicio);
        };
        //Creamos un div y le añadimos el nombreServico y precioServicio 
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        // Y por ultimo le añadimos al div de servicios todos los datos
        document.querySelector("#servicios").appendChild(servicioDiv); 
        // console.log(servicioDiv);
    })
}

function selecionarServicio(servicio) {
    const {id} = servicio;
    const { servicios } = cita;
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado
    if( servicios.some(agregado => agregado.id === id) ) {
        //Si esta agragdo lo elminamos
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove("seleccionado");
    } else {
        //Si no esta agregado lo agregamos
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add("seleccionado");
    }
}

function nombreCliente() {
    const nombre = document.querySelector("#nombre").value;
    cita.nombre = nombre;
}

function selecionarFecha() {
    const inputFecha = document.querySelector("#fecha");
    inputFecha.addEventListener("input", function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        if ([6, 0].includes(dia)) {
            cita.fecha = "";
            e.target.value = "";
            mostrarAlerta("Fines de semana no permitidos", "error", ".formulario");
        } else {
            cita.fecha = e.target.value;
        }
    })
}

function selecionarHora() {
    const inputHora = document.querySelector("#hora");
    inputHora.addEventListener("input", function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 9 || hora > 19) {
            cita.hora = "";
            e.target.value = "";
            mostrarAlerta("Hora No Valida", "error", ".formulario")
        } else {
            cita.hora = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //Previene que se genere mas de una alerta
    const alertaPrevia = document.querySelector(".alerta");
    if(alertaPrevia) {
        alertaPrevia.remove();
    }
    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const referencia =  document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 5000);    
    }
    }    

function mostrarResumen() {
    const resumen = document.querySelector(".contenido-resumen");
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    if(Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta("Hacen falta datos o selecionar servicios", "error", ".contenido-resumen", false);
        return;
    }

    //Formatear el div de Resumen
    const {nombre, fecha, hora, servicios} = cita;

    //Heading para servicios en resumen
    const headingServicios = document.createElement("H3");
    headingServicios.textContent = "Resumen de Servicios";
    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement("DIV");
        contenedorServicio.classList.add("contenedor-servicio");

        const textoServicio = document.createElement("P");
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio)
    }) 


    //Heading para los datos del cliente en el resumen
    const headingDatos = document.createElement("H3");
    headingDatos.textContent = "Datos de la Cita";
    resumen.appendChild(headingDatos);

    //Creando y añadiendo datos del cliente 
    const nombreCliente = document.createElement("P");
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate();
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    const opciones = {weekday: "long", year: "numeric", month: "long", day: "numeric"};
    const fechaFormateada = fechaUTC.toLocaleDateString("es-ES", opciones);
    console.log(cita);

    const fechaCita = document.createElement("P");
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement("P");
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    //Creando Boton para reservar Cita
    const botonReservar = document.createElement("BUTTON");
    botonReservar.classList.add("boton");
    botonReservar.textContent = "Reservar Cita";
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(horaCita);
    resumen.appendChild(fechaCita);
    resumen.appendChild(botonReservar);
}

function reservarCita() {
    console.log("Reservando cita .....");
}