<?php
namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class CitaController extends ActiveRecord {
    public static function index(Router $router) {
        session_start();
        $router->render("cita/index", [
            "nombre" => $_SESSION["nombre"]
        ]);
    }
}