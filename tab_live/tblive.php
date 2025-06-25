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

<div id="contenedor-tablero">
  <?php //   include './contenido_tablero.php'; ?>
</div>
    
</body>
</html>

<script>
  //Recarga automática cada 60 segundos 
  setInterval(() => {
    location.reload(); // Recarga toda la página
  }, 120000); // (60000 milisegundos) 


let lastId = 0;

const source = new EventSource('./sse.php');

source.onmessage = function(event) {
  const newId = parseInt(event.data);
  if (newId !== lastId) {  
    lastId = newId;
    //console.log(lastId);
    updateTablas(lastId); // Llama a una función AJAX para refrescar el contenido
  }
};

//metodo con AJAX 
function updateTablas(lastId) {
  $.ajax({
        url: "./contenido_tablero.php",
        method: "POST",
        data: {lastId},
        success: function(data) {
            $("#contenedor-tablero").html(data);
    } })
}



/*
  en las validaciones para recargar el tablero, 
  - no siempre se ingresara una masiva pueden haber intervalos de timepo sin uyn insert (newId > lastId)
  - no puede ser diferente que ya que si actulizan una tabla simplemente mantien elk mismo numero de activos (newId !== lastId)
  - no puede ser por el numeor de regutros totales
  - ni por actualizaciones en la tabla. 
  - podria validar el tiempo de cada una de las horas vencidas para validar cuando se vence una
  - 
*/

/* funcion anterior 
function actualizarTablero() {
  fetch('./contenido_tablero.php')
    .then(response => response.text())
    .then(html => {
      document.getElementById('contenedor-tablero').innerHTML = html;
    });
}*/
</script>

