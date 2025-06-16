<?php 
  require_once 'includes/phpFun/fun.php';
  require_once './includes/incl.php'; 
  require_once './views\miscelana\general.php';
  
    date_default_timezone_set('America/Guatemala');  
    $fecha = date('m-d-Y');
    $hora  = date('H:i');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>masivas Prueba</title>
    <script src="./includes/JS/index.js"></script>
</head>
<body>

<div>
  <?php listarHeader(); ?>
</div>

<div class="container">
    <label for="pais">País:</label>
    <select id="pais" name="pais" class="form-control" onchange="desig(this.value)" >
        <?php listarPaises(); ?>
    </select>
    <input type="hidden" id="ids" name="ids">
</div>


<div class="container">
  <div class="areasxpais" id="areasxpais"></div>
</div>

</body>
</html>

