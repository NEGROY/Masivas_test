<?php
    
    // URL de la API
      $url = 'http://172.20.97.102:8503/masivas/F6144046?token=masivas2025'; // ← cambia esto a la URL real
    //$url =('../src/api_data/relacionadas.json');

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

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Tickets</title>
  <style>
    .kanban-column {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      background: #f0f2f5;
      padding: 1rem;
    }

    .tarjeta {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      width: 300px;
    }

    h3 {
      margin-top: 0;
      color: #004080;
    }

    p, small {
      margin: 0.3rem 0;
    }
  </style>
</head>
<body>

<h2>Tickets encontrados: <?= $total ?></h2>


<div class="kanban-column">
  <?php foreach ($tickets as $ticket): ?>
    <div class="tarjeta">
      <h3>Ticket: <?= htmlspecialchars($ticket['NUMBER'] ?? 'Sin dato') ?></h3>
      <p><strong>Empresa:</strong> <?= htmlspecialchars($ticket['COMPANY'] ?? 'Sin dato') ?></p>
      <p><strong>Enlace:</strong> <?= htmlspecialchars($ticket['TG_ENLACE'] ?? 'N/A') ?></p>
      <p><strong>Descripción:</strong> <?= htmlspecialchars($ticket['BRIEF_DESCRIPTION'] ?? 'Sin dato'  ) ?></p>
      <p><strong>País:</strong> <?= htmlspecialchars($ticket['PAIS']?? 'Sin dato') ?></p>
      <p><strong>Falla Masiva:</strong> <?= htmlspecialchars($ticket['FALLA_MASIVA']?? 'Sin dato') ?></p>
      <small><strong>Cierre:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['CLOSE_TIME']?? 'Sin dato')) ?></small>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
