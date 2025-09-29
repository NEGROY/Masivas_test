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

// Cargar JSON
$jsonFile = './areas.json';
if (!file_exists($jsonFile)) {
    die("El archivo areas.json no existe.");
}

$jsonData = file_get_contents($jsonFile);
$areas = json_decode($jsonData, true);

// Consulta de inserción
$sql = "INSERT INTO tb_area_escalacion (id, nombre_area, id_pais) 
        VALUES (:id, :nombre, :pais)";

$stmt = $pdo->prepare($sql);

// Insertar datos
foreach ($areas as $area) {
    $id     = $area['id'] ?? null;
    $nombre = $area['area'] ?? null;
    $pais   = $area['pais'] ?? null;

    if (!$id || !$nombre || !$pais) {
        echo "Datos incompletos, se omite un registro.\n";
        continue;
    }

    try {
        $stmt->execute([
            ':id'     => $id,
            ':nombre' => $nombre,
            ':pais'   => $pais,
        ]);
        echo "Área insertada: $nombre (ID: $id)\n";
    } catch (PDOException $e) {
        echo "Error insertando área ID $id: " . $e->getMessage() . "\n";
    }
}
