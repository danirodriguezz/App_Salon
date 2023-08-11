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
                        //Si existe un token eliminamos y guardamos el cambio por seguridad
                        $usuario->nullToken();
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
        session_start();
        $_SESSION = [];
        header("Location: /");
    }
    public static function olvide(Router $router) {
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where("email", $auth->email);
                if($usuario && $usuario->confirmado === "1") {
                    //Generamos un token de un solo uso
                    $usuario->crearToken();
                    //Actualizamos el usuario con el token 
                    $usuario->guardar();
                    //Enviamos un email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarIntrucciones();
                    //Alerta de exito
                    Usuario::setAlerta("exito", "Solicitud confirmada Revisa tu email");
                    $alertas = Usuario::getAlertas();
                } else {
                    Usuario::setAlerta("error", "El Usuario no exite o no esta confirmado");
                    $alertas = Usuario::getAlertas();
                }
            }
        }
        $router->render("auth/olvide-password", [
            "alertas" => $alertas
        ]);
    }
    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;
        $token = s($_GET["token"]);
        $usuario = Usuario::where("token", $token);
        //Validamos que exista un usuario con el token obtenido
        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido");
            $error = true;
        }
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword($usuario->password);
            if(empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header("Location: /?exito=true");
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/recuperar-password", [
            "alertas" => $alertas,
            "error" => $error
        ]);
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