<?php
// importatr la conexion
require 'includes/app.php';
$db=conetarDB();

//email & password
$email="correo@correo.com";
$password="123456";
$passwordHash=password_hash($password,PASSWORD_BCRYPT);

//query para crear el usuario
$query= "INSERT INTO usuarios (email,password) VALUES ('${email}','${passwordHash}') ";

//agregar a la bd de datos
mysqli_query($db,$query);