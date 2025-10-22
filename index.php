<?php
session_start();
require_once("config.php"); 
include './includes/BD_con/db_con.php';

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Buscar usuario por nombre y estado activo
    $sql = "SELECT * FROM tb_usuario WHERE nombre = ? AND estado != 0 LIMIT 1";
    $stmt = $general->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Validar contraseña con MD5
        if (md5($clave) === $user['pass']) {
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['id'] = $user['id'];
            header('Location: ' . urlsite.'home.php'); // Redirige correctamente header("Location: home.php");
            exit();
        } else {
             header('Location: ' . urlsite.'index.php?error=1'); // Redirige correctamente
             session_destroy();
             exit();
        }
    } else {
        header('Location: ' . urlsite.'index.php?error=1'); // Redirige correctamente
        session_destroy();
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./includes/CSS/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Usuario o contraseña incorrectos</p>';
        }
        ?>

        <form action="" method="POST">
            <img src="./src/img/logo_frt_1.png" alt="Logo" style="height: 40px;">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
