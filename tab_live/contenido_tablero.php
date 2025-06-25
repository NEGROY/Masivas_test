<?php
include '../includes/BD_con/db_con.php';
date_default_timezone_set('America/Guatemala');
$lastId = $_POST["lastId"];

// Función para convertir hora_sumada a timestamp
  function calcularsegundos($hora_sumada, $ahora) {
  $target = strtotime($hora_sumada);
  return $target - $ahora;
  }


// Inicializar arrays por columna
$tarjetas = [
  'gris' => [],
  'verde' => [],
  'amarillo' => [],
  'rojo' => []
];

$query = "SELECT id_registro, falla_id, area_id, titulo, nivel, nombre, telefono, tiempo, 
hora_apertura, hora_sumada, tiempo_acumulado, comentario, estado, fecha_registro 
FROM tb_escalaciones_registro 
WHERE estado = 1 
ORDER BY hora_sumada";

$resultado = mysqli_query($general, $query);

while ($fila = mysqli_fetch_assoc($resultado)) {
  $ahora = strtotime(date("H:i:s")); //
  $dif_segundos = calcularsegundos($fila['hora_sumada'],$ahora);

    // Clasificación por tiempo restante
    if ($dif_segundos > 1200) {
        $columna = "gris";
    } elseif ($dif_segundos > 900) {
        $columna = "verde";
    } elseif ($dif_segundos > 600) {
        $columna = "amarillo";
    } else {
        $columna = "rojo";
    }

    $card = "<div class='card $columna' 
      data-falla-id='" . htmlspecialchars($fila['falla_id']) . "'
      data-area-id='" . htmlspecialchars($fila['area_id']) . "'
      data-hora-apertura='" . htmlspecialchars($fila['hora_apertura']) . "'
      data-tiempo-acumulado='" . htmlspecialchars($fila['tiempo_acumulado']) . "'>
      <p><strong>ID:</strong> {$fila['falla_id']} || <strong>Hora:</strong> {$fila['hora_sumada']} Hrs </p>
      <p><strong>Título: </strong> {$fila['titulo']}</p>
    </div>";

    $tarjetas[$columna][] = $card;
}

// Imprimir contenedor
echo "
<div class='kanban-container'>
  <div class='kanban-column' id='gris'>
    " . implode("\n", $tarjetas['gris']) . "
  </div>
  <div class='kanban-column' id='verde'>
    <h2>🟢 &lt; 15 min</h2>
    " . implode("\n", $tarjetas['verde']) . "
  </div>
  <div class='kanban-column' id='amarillo'>
    <h2>🟡 15 - 10 min</h2>
    " . implode("\n", $tarjetas['amarillo']) . "
  </div>
  <div class='kanban-column' id='rojo'>
    <h2>🔴 &gt; 10 min</h2>
    " . implode("\n", $tarjetas['rojo']) . "
  </div>
</div>";


?>
