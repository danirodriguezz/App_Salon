<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuacion</p>
<form action="/olvide" class="fornulario">
    <div class="campo">
        <label for="email">E-mail</label>
        <input 
            type="email"
            name="email"
            id="email"
            placeholder="Tu E-mail"
        />
    </div>
    <input type="submit" value="Enviar Insatruciones" class="boton">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes una contraseña? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No tienes una cuenta? Crea una</a>
</div>