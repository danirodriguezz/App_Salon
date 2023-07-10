<?php
namespace Controllers;

use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $router->render("auth/login");
    }
    public static function logout() {
        echo "Desde Logout";
    }
    public static function olvide() {
        echo "Desde olvide";
    }
    public static function recuperar() {
        echo "Desde recuperar";
    }
    public static function crear() {
        echo "Desde crear";
    }
}