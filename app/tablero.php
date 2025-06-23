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
  <?php tablero1();?>
</div>


    
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".card").forEach(function (card) {
    card.addEventListener("click", function () {
      const fallaID = card.dataset.fallaId;
      const areaSlct = card.dataset.areaId;
      const horaAper = card.dataset.horaApertura;
      const tmpAcumu = card.dataset.tiempoAcumulado;

      if (fallaID && areaSlct && horaAper && tmpAcumu) {
        const url = `../index.php?Fid=${encodeURIComponent(fallaID)}&slct=${encodeURIComponent(areaSlct)}&horaAper=${encodeURIComponent(horaAper)}&tmpAcumu=${encodeURIComponent(tmpAcumu)}`;
        window.location.href = url;
      } else {
        console.warn("Alguno de los datos no está definido");
      }
    });
  });
});


  // Recarga automática cada 60 segundos 
  setInterval(() => {
    location.reload(); // Recarga toda la página
  }, 120000); // (60000 milisegundos)

</script>
