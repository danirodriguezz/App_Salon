<?php
namespace Controllers;

use Model\ActiveRecord;
use Model\AdminCita;
use MVC\Router;

class AdminController  {
    public static function index(Router $router) {
        session_start();
        isAdmin();
        $fecha = $_GET["fecha"] ?? date("Y-m-d");
        $fechasArray = explode("-", $fecha);
        if(!checkdate($fechasArray[1], $fechasArray[2], $fechasArray[0])) {
            header("Location: /404");
        };
        //Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuario_id=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.cita_id=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicio_id ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        // Obtenemos las citas con la funcion SQL de ActiveRecord
        $citas = AdminCita::SQL($consulta);

        //Renderizamos la vista y le pasamos las variables
        $router->render("admin/index", [
            "nombre" => $_SESSION["nombre"],
            "citas" => $citas,
            "fecha" => $fecha
        ]);
    }
}