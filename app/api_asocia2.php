<?php
    include './includes/BD_con/db_con.php';

    // SELECT COUNT(*) FROM TB_fallas_asociadas WHERE tk_masiva = 
    // INSERT INTO TB_fallas_asociadas (tk_masiva, ENLACE, COMPANY, CLOSE_TIME, DESCRIPTION, PAIS) VALUES (?, ?, ?, ?, ?, ?)
    // Preparar consultas
    
    $check = $conexion->prepare("SELECT COUNT(*) FROM TB_fallas_asociadas WHERE tk_masiva = ?");
    // INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
    $insert = $conexion->prepare("INSERT INTO TB_fallas_asociadas (tk_masiva, ENLACE, COMPANY, CLOSE_TIME, DESCRIPTION, PAIS)
                VALUES (?, ?, ?, ?, ?, ?)");

// URL de la API
    //$url = 'http://127.0.0.1:8000/masivas/F6144046?token=masivas2025'; // ← cambia esto a la URL real
    $url =('../src/api_data/relacionadas.json');

// Consumir la API con file_get_contents
$response = file_get_contents($url);

// Verificar si hubo respuesta 
// se debera de validar con / la concexion el marlon  
if ($response === false) {
    echo "Error al consumir la API.";
    exit;
}

// Decodificar el JSON
$data = json_decode($response, true);

// Verificar si la respuesta es válida
if (!isset($data['code']) || $data['code'] !== 200) {
    echo "Error en la respuesta de la API.";
    exit;
}

// Recorrer los tickets
foreach ($tickets as $ticket) {
    $tk         = $ticket['NUMBER'] ?? '';
    $enlace     = $ticket['TG_ENLACE'] ?? null;
    $company    = $ticket['COMPANY'] ?? null;
    $close_time = $ticket['CLOSE_TIME'] ?? null;
    $desc       = $ticket['BRIEF_DESCRIPTION'] ?? null;
    $pais       = $ticket['PAIS'] ?? null;

    // Validar si ya existe
    $e->bind_param("s", $tk);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->store_result();

    if ($existe == 0) {
        // Insertar si no existe
        $insert->bind_param("ssssss", $tk, $enlace, $company, $close_time, $desc, $pais);
        $insert->execute();
        echo "Insertado: $tk<br>";
    } else {
        echo "Ya existe: $tk<br>";
    }
}


// Extraer los tickets
$tickets = $data['data'];
$total = $data['total'];

/* CREATE TABLE TB_fallas_asociadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tk_masiva VARCHAR(20) NOT NULL,
    ENLACE VARCHAR(50),
    COMPANY VARCHAR(100),
    CLOSE_TIME DATETIME,
    DESCRIPTION TEXT,
    PAIS VARCHAR(5),
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); */
?>