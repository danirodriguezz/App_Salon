<?php
namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        //Creamos el Array de alertas
        $alertas = [];
        //Codigo que se ejcutará cuando se envie el formulario de login
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)) {
                //Comprobar que exista el usuario
                $usuario = Usuario::where("email", $auth->email);
                if($usuario) {
                    //Verificar password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //Autenticar el Usuario
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " "  . $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        //Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION["admin"] = $usuario->admin ?? null;
                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }
                    };
                } else {
                    Usuario::setAlerta("error", "Usuario o contraseña invalidos");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/login", [
            "alertas" => $alertas
        ]);
    }
    public static function logout() {
        echo "Desde Logout";
    }
    public static function olvide(Router $router) {
        $router->render("auth/olvide-password");
    }
    public static function recuperar() {
        echo "Desde recuperar";
    }
    public static function crear(Router $router) {
        //Creamos el objeto vacio de usuario
        $usuario = new Usuario;
        //Alertas vacias
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //Sincronizamos el objeto vacio con los datos que se envian 
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //Revisamos que las alertas estan vacias
            if(empty($alertas)) {
                //Verificamos que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //No esta registrado Hasheamos el password
                    $usuario->hashPassword();
                    //Generamos un token unico
                    $usuario->crearToken(); 
                    //Enviamos el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header("Location: /mensaje");
                    }
                }
            }
        }
        $router->render("auth/crear-cuenta", [
            //Enviamos los datros a la vista
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    public static function mensaje(Router $router) {
        $router->render("auth/mensaje");
    }
    public static function confirmar(Router $router) {
        $alertas = [];
        //Obtenemos el token
        $token = s($_GET["token"]);
        //Obtenemos el usuario que coincida con el token obtenido
        $usuario = Usuario::where("token", $token);
        if(empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta("error", "Este usuario no existe o ya ha sido confirmado");
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta confirmada correctamente");
        }
        //Obtenemos alertas
        $alertas = Usuario::getAlertas();
        //Renderizamos la vista
        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);
    }
}