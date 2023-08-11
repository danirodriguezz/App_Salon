<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Esta funcion evalua dos variables y responde true cuando sean distintas
function esUltimo(string $actual, string $proximo) :bool {
    if($actual !== $proximo) {
        return true;
    } else {
        return false;
    }
}

// Funcion que revisa que el usuario esta autenticado
function isAuth() :void {
    if (!isset($_SESSION["login"])) {
        header("Location: /");
    }
}

// Funcion que revisa que el usuario es el admin
function isAdmin() :void {
    if(!isset($_SESSION["admin"])) {
        header("Location:/");
    }
}