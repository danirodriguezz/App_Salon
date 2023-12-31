<?php

namespace Clases;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    //Enviamos el email de confirmacion
    public function enviarConfirmacion(){
        try {
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = $_ENV["EMAIL_HOST"];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = $_ENV["EMAIL_PORT"];
            $phpmailer->Username = $_ENV["EMAIL_USER"];
            $phpmailer->Password = $_ENV["EMAIL_PASS"];

            $phpmailer->setFrom("cuentas@appsalon.com");
            $phpmailer->addAddress("cuentas@appsalon.com", "AppSalon.com");
            $phpmailer->Subject = "Confirma tu cuenta";

            //Set html
            $phpmailer->isHTML(TRUE);
            $phpmailer->CharSet = "UTF-8";

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando
            el siguiente enlace</p>";
            $contenido .= "<p>Presione aquí <a href='" . $_ENV["APP_URL"] . "/confirmar-cuenta?token=" . $this->token . "'>Confirma Cuenta</a></p>";
            $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= "</html>";
            $phpmailer->Body = $contenido;
            
            //Enviamos el email
            $phpmailer->send();
        } catch (Exception $e) {
            echo "El mendaje no se ha podidio enviar . Mailer Error : {$phpmailer->ErrorInfo}";
        }
    }

    //Enviamso el email con intrucciones para recuperar cuenta
    public function enviarIntrucciones() {
        try {
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = $_ENV["EMAIL_HOST"];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = $_ENV["EMAIL_PORT"];
            $phpmailer->Username = $_ENV["EMAIL_USER"];
            $phpmailer->Password = $_ENV["EMAIL_PASS"];

            $phpmailer->setFrom("cuentas@appsalon.com");
            $phpmailer->addAddress("cuentas@appsalon.com", "AppSalon.com");
            $phpmailer->Subject = "Restablece tu Password";

            //Set html
            $phpmailer->isHTML(TRUE);
            $phpmailer->CharSet = "UTF-8";

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado una reestablecer tu passwod, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presione aquí <a href='" . $_ENV["APP_URL"] . "/recuperar?token=" . $this->token . "'>Reestablece Password</a></p>";
            $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
            $contenido .= "</html>";
            $phpmailer->Body = $contenido;
            
            //Enviamos el email
            $phpmailer->send();
        } catch (Exception $e) {
            echo "El mendaje no se ha podidio enviar . Mailer Error : {$phpmailer->ErrorInfo}";
        }
    }
}