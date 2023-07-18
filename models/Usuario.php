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
        $this->admin = $args["admin"] ?? "0";
        $this->confirmado = $args["confirmado"] ?? "0";
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
    //Validamos que exista el usuario y que la contraseña sea correcta
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas["error"][] = "El Email es obligatorio";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "El Password es obligatorio";
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

    //Hashea el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    //Creamos un token unico
    public function crearToken() {
        $this->token = uniqid();
    }

    //Comprobamos el passsword y comprobamos si esta verificado
    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado) {
            self::$alertas["error"][] = "Usuario o contraseña invalidos o no has confirmado tu cuenta";
        } else {
            return true;
        }
    }

}