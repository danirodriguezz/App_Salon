<?php
namespace Model;

class Usuario extends ActiveRecord {
    //Base de datos
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "email", "telefono", "admin", "confirmado", "token", "password"];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $password;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? null;
        $this->confirmado = $args["confirmado"] ?? null;
        $this->token = $args["token"] ?? "";
        $this->password = $args["password"] ?? "";
    }
    //Mensajes de validacion para la creacion de una cuenta de usuario
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "Debe introducir un nombre para continuar";
        }
        if(!$this->apellido) {
            self::$alertas["error"][] = "Debe introducir el apellido para continuar";
        }
        if(!$this->email) {
            self::$alertas["error"][] = "Debe introducir el email para continuar";
        }
        if(!$this->password || strlen($this->password) < 6) {
            self::$alertas["error"][] = "Debe introducir un password seguro";
        }
        
        return self::$alertas;
    }
    //Revisa si el usuario ya exite
    public function existeUsuario() {
        $query = "SELECT * FROM ". self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado->num_rows) {
            self::$alertas["error"][] = "El usuario ya esta registrado";
        }
        return $resultado;
    }
}