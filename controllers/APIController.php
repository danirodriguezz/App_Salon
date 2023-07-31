<?php
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }
    public static function guardar() {
        //Almacena la cita y devulve el id
        $cita = new Cita($_POST);
        $respuesta = $cita->guardar();
        $cita_id = $respuesta["id"];
        //Almacenamos los servicios con id de la cita
        $idServicios = explode(",", $_POST["servicios"]);
        // Iteramos el arreglo de $idServicios para guardarlos en la base de datos
        foreach($idServicios as $idServicio) {
            $args = [
                "cita_id" => $cita_id,
                "servicio_id" => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode([ "resultado" => $respuesta ]);
    }
}