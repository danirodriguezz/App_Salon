<div class="centrar">
    <h1 class="nombre-pagina">Recuperar Password</h1>
    <p class="descripcion-pagina">Coloca tu nuevo pasword acontinuacion</p>
    <?php include_once __DIR__ . "/../templates/alertas.php" ?>
    <?php
        //Si la variable de error es true no se imprime el formulario
        if(!$error):
    ?>
    <form class="formulario" method="POST">
        <div class="campo">
            <label for="password">Password</label>
            <input 
                type="password"
                id="password"
                name="password"
                placeholder="Tu Nuevo Pasword"
            />
        </div>
        <input type="submit" class="boton" value="Guardar Nuevo Password">
    </form>
    <div class="acciones">
        <a href="/">¿Recordaste la contraseña? Inicia Sesión</a>
        <a href="/crear-cuenta">¿Quieres Crear una nueva Cuenta? Crea una</a>
    </div>
    <?php endif?>
</div>