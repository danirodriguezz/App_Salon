<div class="centrar">
    <h1 class="nombre-pagina">Login</h1>
    <p class="descripcion-pagina">Inicia Sesion con tus datos</p>
    <?php include_once __DIR__ . "/../templates/alertas.php" ?>
    <?php if($_GET["exito"]) :?>
        <div class="alerta exito">
            Ha salido todo correctamente Inicia Sesion
        </div>
    <?php endif?>
    <form action="/"  method="POST" class="formulario"">
        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="email"
                id="email"
                placeholder="Tu Email"
                name="email"
            />
        </div>
        <div class="campo">
            <label for="password">Password</label>
            <input 
                type="password"
                id="password"
                placeholder="Tu Password"
                name="password"
            />
        </div>
        <input type="submit" class="boton" value="Iniciar Sesión">
    </form>

    <div class="acciones">
        <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
        <a href="/olvide">¿Olvidaste tu password?</a>
    </div>
</div>


