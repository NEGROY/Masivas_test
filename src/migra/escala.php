<?php
// Configuración de la base de datos
$host = "65.109.88.87";
$port = 3306;
$user = "pawsoyos_escalaciones";
$password = "Vmgvx~vgxn[(";
$database = "esacalaciones_cnoc";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa.\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Leer archivo JSON
$jsonFile = './escala.json';
if (!file_exists($jsonFile)) {
    die("El archivo escala.json no existe.");
}

$jsonData = file_get_contents($jsonFile);
$escalaciones = json_decode($jsonData, true);

// Consulta SQL para insertar
$sql = "INSERT INTO tb_escalacion (
            id, tiempo, Tipo_contacto, comentario,
            nivel, id_contacto, id_tipo_escalacion, id_area
        )
        VALUES (
            :id_escalacion, :tiempo, :tipo_contacto, :comentario,
            :nivel, :id_contacto, :id_tipo_escalacion, :id_area
        )";

$stmt = $pdo->prepare($sql);

// Insertar cada escalación
foreach ($escalaciones as $esc) {
    $id_escalacion      = $esc['id_escalacion'] ?? null;
    $tiempo             = $esc['tiempo'] ?? null;
    $tipo_contacto      = $esc['Tipo_contacto'] ?? null; // Puede venir vacío
    $comentario         = $esc['comentario'] ?? null;
    $nivel              = $esc['nivel'] ?? null;
    $id_contacto        = $esc['id_contacto'] ?? null;
    $id_tipo_escalacion = $esc['id_tipo_escalacion'] ?? null;
    $id_area            = $esc['id_area'] ?? null;

    // Validación básica
    if (!$id_escalacion || !$id_contacto || !$id_area) {
        echo "Registro incompleto (ID: $id_escalacion), se omite.\n";
        continue;
    }

    try {
        $stmt->execute([
            ':id_escalacion'      => $id_escalacion,
            ':tiempo'             => $tiempo,
            ':tipo_contacto'      => $tipo_contacto,
            ':comentario'         => $comentario,
            ':nivel'              => $nivel,
            ':id_contacto'        => $id_contacto,
            ':id_tipo_escalacion' => $id_tipo_escalacion,
            ':id_area'            => $id_area,
        ]);
        echo "Escalación insertada: ID $id_escalacion\n";
    } catch (PDOException $e) {
        echo "Error en ID $id_escalacion: " . $e->getMessage() . "\n";
    }
}
