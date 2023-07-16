<?php
namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $router->render("auth/login");
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
                    //No esta registrado
                    debuguear("No esta registrado");
                }
            }
        }
        $router->render("auth/crear-cuenta", [
            //Enviamos los datros a la vista
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
}