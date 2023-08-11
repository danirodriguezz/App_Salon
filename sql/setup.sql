DROP DATABASE IF EXISTS app_salon;
CREATE DATABASE app_salon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_salon;

CREATE TABLE usuarios (
    id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    admin TINYINT,
    confirmado TINYINT,
    token VARCHAR(15),
    password VARCHAR(255) NOT NULL
);

CREATE TABLE servicios (
    id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(5,2) NOT NULL
);

INSERT INTO servicios (id, nombre, precio) 
VALUES (4, "Peinado Mujer", 80.00), 
(5, "Peinado Hombre", 60.00), 
(6, "Peinado Niño", 60.00), 
(7, "Corte de Barba", 50.00), 
(8, "Tinte Mujer", 300.00), 
(9, "Uñas", 400.00), 
(10, "Lavado de Cabello", 50.00), 
(11, "Tratamiento Capilar", 90.00);

CREATE TABLE citas (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    usuario_id INT(11) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE citasServicios (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cita_id INT(11) NOT NULL,
    servicio_id INT(11) NOT NULL,
    FOREIGN KEY (cita_id) REFERENCES citas(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- Utilizamos los Join para unir la informacion que quiero traerme de la base de datos
SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) as cliente,
usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio
FROM citas 
LEFT OUTER JOIN usuarios ON citas.usuario_id=usuarios.id 
LEFT OUTER JOIN citasServicios ON citasServicios.cita_id=citas.id 
LEFT OUTER JOIN servicios ON servicios.id=citasServicios.servicio_id;