<?php
/*$host = "65.109.88.87";
$port = 3306;
$user = "pawsoyos_escalaciones";
$password = "Vmgvx~vgxn[(";
$database = "pawsoyos_escalaciones_no_eliminar"; */


$host = "localhost";
$port = 3306;
$user = "root";
$password = "";
$database = "pawsoyos_escalaciones_no_eliminar";



$general = mysqli_connect($host, $user, $password, $database, $port);

// Verifica conexión
if (!$general) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
