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

</head>
<body>

<div>
  <?php listarHeader(); ?>
</div>

<div class="container mt-4">
  <h4 class="mb-3">Tablero de Escalaciones</h4>
</div>  

<div class="container mt-4">
  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Falla</th><th>Área</th><th>Título</th><th>Nivel</th>
          <th>Nombre</th><th>Teléfono</th><th>Tiempo</th><th>Hora Apertura</th>
          <th>Hora Sumada</th><th>Tiempo Acumulado</th><th>Comentario</th>
          <th>Estado</th><th>Fecha</th>
        </tr>
      </thead>
      <?php 
        include '../includes/BD_con/db_con.php';
        $query = "SELECT id_registro, falla_id, area_id, titulo, nivel, nombre, telefono, tiempo, 
          hora_apertura, hora_sumada, tiempo_acumulado, comentario, estado, fecha_registro 
          FROM tb_escalaciones_registro";

          $resultado = mysqli_query($general, $query);

          // Estilos en línea simples para los colores
          function color_por_tiempo($horaSumada) {
              $ahora = strtotime(date("H:i:s"));
              $horaLimite = strtotime($horaSumada);
              $diferencia = $horaLimite - $ahora;
          
              if ($diferencia >= 15 * 60) {
                  return 'background-color: #d4edda'; // verde
              } elseif ($diferencia >= 10 * 60) {
                  return 'background-color: #fff3cd'; // amarillo
              } elseif ($diferencia >= 5 * 60) {
                  return 'background-color: #ffeeba'; // naranja claro
              } elseif ($diferencia >= 0) {
                  return 'background-color: #f8d7da'; // rojo claro (peligro inminente)
              } else {
                  return 'background-color: #f5c6cb; font-weight: bold;'; // ya se pasó - rojo fuerte
              }
          }

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $color = color_por_tiempo($fila['hora_sumada']);
        
            echo "<tr style='$color'>";
            foreach ($fila as $columna => $valor) {
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }
            echo "</tr>";
        }
?>
      <tbody>
        <!-- Aquí irán los registros dinámicos -->
      </tbody>
    </table>
  </div>
</div>


    
</body>
</html>