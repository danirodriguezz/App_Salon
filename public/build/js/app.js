let paso=1;const pasoInicial=1,pasoFinal=3;function iniciarApp(){tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior()}function tabs(){document.querySelectorAll(".tabs button").forEach(t=>{t.addEventListener("click",(function(t){paso=parseInt(t.target.dataset.paso),mostrarseccion(paso),botonesPaginador()}))})}function mostrarseccion(t){for(var a=document.getElementsByClassName("seccion"),e=0;e<a.length;e++)a[e].classList.remove("mostrar"),a[e].classList.add("ocultar");const o=document.querySelector("#paso-"+t);o.classList.add("mostrar"),o.classList.remove("ocultar");const s=document.querySelector(".actual");s&&s.classList.remove("actual");document.querySelector(`[data-paso="${t}"]`).classList.add("actual")}function botonesPaginador(){const t=document.getElementById("siguiente"),a=document.getElementById("anterior");1===paso?(a.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(a.classList.remove("ocultar"),t.classList.add("ocultar")):(a.classList.remove("ocultar"),t.classList.remove("ocultar")),mostrarseccion(paso)}function paginaSiguiente(){document.getElementById("siguiente").addEventListener("click",(function(){paso>=3||(paso++,botonesPaginador())}))}function paginaAnterior(){document.getElementById("anterior").addEventListener("click",(function(){paso<=1||(paso--,botonesPaginador())}))}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));