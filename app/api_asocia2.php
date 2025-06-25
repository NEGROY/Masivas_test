<?php
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

// Extraer los tickets
$tickets = $data['data'];
$total = $data['total'];
?>