<?php
  require_once 'includes/phpFun/fun.php';
  require_once './includes/incl.php';
  require_once './views/miscelana/general.php';
  
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

    
</body>
</html>