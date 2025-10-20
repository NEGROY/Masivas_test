<?php
  require_once 'includes/phpFun/fun.php';
  require_once './includes/2incl.php';
  require_once './views/miscelana/general.php';
  
    date_default_timezone_set('America/Guatemala');
    $fecha = date('m-d-Y');
    $hora  = date('H:i');

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLO MENSAJES </title>
    <script src="./includes/JS/index.js"></script>
    <link rel="stylesheet" href="./includes/CSS/index.css">
</head>
<body>

<div>
  <?php listarHeader(''); ?>
</div>

<!-- Documentos de pruewba -->
<div class="container " style="max-width: inherit;">
  <div class="row g-3">
    
  <!-- Columna de selección de país -->
    <div class="col-md-3">
      <div class="p-3 border rounded-3 shadow-sm bg-light ">
      <!-- Input Falla -->
        <label for="falla" class="form-label">Falla ID:</label>
        <input type="text" id="falla" name="falla" class="form-control mb-3" value='F6144046' placeholder="Fxxxx">

      <!-- Input Falla -->
        <label for="falla" class="hidden" hidden>HORA DE CIERRE</label>
        <input type="text" id="CIERRE" class="hidden"  placeholder="-" disabled autocomplete="off" hidden >

      <!-- Select de país -->
        <label for="pais" class="form-label">País:</label>
        <select id="pais" name="pais" class="form-select" onchange="desig(this.value)">
          <option autocomplete="off" value="0" selected >Busque un Pais</option>
          <?php listarPaises(); ?>
        </select>
        <input type="hidden" id="ids" name="ids">

      <!-- Select de Área de Escalación -->
        <br>
        <label for="areasxpais" class="form-label">Áreas de Escalación:</label>         <br>
        <select id="areasxpais" name="areasxpais" class="js-example-basic-single" onchange="habilitarBuscar()">
          <option value="" >---Seleccione un Pais---</option>
        </select>
        <br>

      <!-- Botón de búsqueda -->
        <br>
        <button type="button" class="btn btn-primary w-100" onclick="calcularTiempos(0, 'notaGenerada')" id="btnBuscar"  >
        <i class="bi bi-search me-1"></i> Calcular </button>
      </div>
    </div>

  <!-- Columna de  TB escalación  -->
  <div class="col-9">
    <small class="text-muted d-block pr-2" id='titulo'> - </small>  
    
    <?php fila_hras2(); ?>
    <div class="container">
      <small class="text-muted d-block pr-2" id='titulos'> - </small>
    </div>
    
    <div class="container TB_calcu mt-9" id='TB_calcu'>
      <br>
      
    </div>
  </div>

  </div>
</div>

  <div id="resultado" class="mt-3"></div>

<?php msj_tb(); ?>

<div><?php loader()?></div>

</body>
</html>

<script>



  //  FUNCION PARA QUE MUESTRE TABLA PARA COPIAR 
     

    // Select2 
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
    });
    
</script>
