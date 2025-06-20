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

<!-- TABLA PROXY PARA  -->
<div class="container">
  <table>
    <thead>
      <tr>
        <th>area_id</th>
        <th>nivel</th>
        <th>nombre</th>
        <th>telefono</th>
        <th>tiempo</th>
        <th>hora_actual</th>
        <th>hora_sumada</th>
        <th>tiempo_acumulado</th>
        <th>titulo</th>
        <th>comentario</th>
        <th>falla_id</th>
        <th>estado</th>
      </tr>
    </thead>
    <tbody>
      <!-- Aquí irían los datos -->
    </tbody>
  </table>
</div>


    
</body>
</html>