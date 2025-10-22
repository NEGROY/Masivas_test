<?php
/*$host = "65.109.88.87"; $port = 3306; $user = "pawsoyos_escalaciones"; $password = "Vmgvx~vgxn[("; $database = "pawsoyos_escalaciones_no_eliminar"; */

/* VARIABLES  ANTES 
$host = "172.20.97.102";
$port = 3306;
$user = "root";
$password = "KDM3HS1$6;cnoc";
$database = "esacalaciones_cnoc";  */

/* VARIABLES COMO ENTORNO VIRTUAL */
// Incluir la configuración global
require_once __DIR__ . '/../../config.php';

// Obtener credenciales desde variables de entorno
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');
//  PARA TODAS LAS INTANCIAS CONDE SE NECESITA 
// require_once __DIR__ . '/../includes/BD_con/db_con.php'; 

$general = mysqli_connect($host, $user, $password, $database, $port);

// Verifica conexión
if (!$general) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
