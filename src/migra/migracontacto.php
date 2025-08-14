<?php
// Configuración de la base de datos
$host = "65.109.88.87";
$port = 3306;
$user = "pawsoyos_escalaciones";
$password = "Vmgvx~vgxn[(";
$database = "esacalaciones_cnoc";

echo "hola";

// Conexión PDO
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa.\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Leer el archivo JSON
$jsonFile = './contactos.json';
if (!file_exists($jsonFile)) {
    die("El archivo contactos.json no existe.");
}

$jsonData = file_get_contents($jsonFile);
$contactos = json_decode($jsonData, true);

// Preparar la consulta de inserción
$sql = "INSERT INTO tb_contactos (id_contacto, nombre, correo, telefono) 
        VALUES (:id, :nombre, :correo, :telefono)";

$stmt = $pdo->prepare($sql);

// Insertar cada contacto
foreach ($contactos as $contacto) {
    $id = $contacto['id'] ?? null;
    $nombre = $contacto['nombre'] ?? '';
    $correo = $contacto['correo'] ?? null;
    $telefono = $contacto['numero'] ?? null;

    // Si el ID o el nombre están vacíos, saltamos
    if (!$id || !$nombre) {
        echo "Saltando contacto sin ID o nombre válido.\n";
        continue;
    }

    try {
        $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':telefono' => $telefono,
        ]);
        echo "Insertado contacto: $nombre (ID: $id)\n";
    } catch (PDOException $e) {
        echo "Error insertando contacto ID $id: " . $e->getMessage() . "\n";
    }
}
