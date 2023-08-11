<div class="centrar">
    <h1 class="nombre-pagina">Olvide Password</h1>
    <p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuacion</p>
    <?php include_once __DIR__ . "/../templates/alertas.php" ?>
    <form  class="formulario" action="/olvide" method="POST">
        <div class="campo">
            <label for="email">E-mail</label>
            <input 
                name="email"
                type="email"
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
</div>
