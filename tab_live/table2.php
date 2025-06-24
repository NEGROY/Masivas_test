<?php
  require_once '../includes/phpFun/fun.php';
  require_once '../includes/incl.php';
  require_once '../views/miscelana/general.php';
  
    date_default_timezone_set('America/Guatemala');
    $fecha = date('m-d-Y');
    $hora  = date('H:i');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tablero test </title>
    <link rel="stylesheet" href="../includes/CSS/tablero.css">
</head>
<body>

<div>
  <?php listarHeader('.'); ?>
</div>

<div>
  <div class='kanban-container'>
    <div class='kanban-column' id='atiempo'>
      
    </div>
    <div class='kanban-column' id='verde'>
      <h2>🟢 &lt; 15 min</h2>
    </div>
    <div class='kanban-column' id='amarillo'>
      <h2>🟡 15 - 10 min</h2>
    </div>
    <div class='kanban-column' id='rojo'>
      <h2>🔴 &gt; 10 min</h2>
    </div>
  </div>
</div>


*YA VENCIDOS  QUEDAN 10 MIN PARA QUE SE ESCALE DE COLOR ROJO * 
SELECT id_registro, falla_id, area_id, titulo, nivel, nombre, telefono, tiempo, 
       hora_apertura, hora_sumada, tiempo_acumulado, comentario, estado, fecha_registro 
FROM tb_escalaciones_registro 
WHERE estado = 1  
  AND hora_sumada < SUBTIME(NOW(), '00:09:00') 
ORDER BY hora_sumada;
  
*FALTAN ENTRE 15Y 10  MIN  SE DEBED DE AGREAR EN PHP LA HORA, POR EJEMPLO> LA HORA ACTUAL (15:10)-15 MIN *
SELECT id_registro, falla_id, area_id, titulo, nivel, nombre, telefono, tiempo, 
       hora_apertura, hora_sumada, tiempo_acumulado, comentario, estado, fecha_registro 
FROM tb_escalaciones_registro 
WHERE estado = 1
  AND hora_sumada BETWEEN '15:10:00' AND '15:14:00'
ORDER BY hora_sumada;




    
</body>
</html>

<script>
  // Recarga automática cada 60 segundos 
  setInterval(() => {
    location.reload(); // Recarga toda la página
  }, 120000); // (60000 milisegundos)

</script>
