<?php
namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class CitaController extends ActiveRecord {
    public static function index(Router $router) {
        session_start();
        isAuth();
        $router->render("cita/index", [
            "nombre" => $_SESSION["nombre"],
            "id" => $_SESSION["id"]
        ]);
    }
}