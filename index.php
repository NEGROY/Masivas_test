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

<!-- Documentos de pruewba -->
<div class="container py-3">
  <div class="row g-3">
    
  <!-- Columna de selección de país -->
    <div class="col-md-3">
      <div class="p-3 border rounded-3 shadow-sm bg-light ">
        
      <!-- Input Falla -->
        <label for="falla" class="form-label">Falla ID:</label>
        <input type="text" id="falla" name="falla" class="form-control mb-3" placeholder="Fxxxx">

      <!-- Select de país -->
        <label for="pais" class="form-label">País:</label>
        <select id="pais" name="pais" class="form-select" onchange="desig(this.value)">
          <?php listarPaises(); ?>
        </select>
        <input type="hidden" id="ids" name="ids">

      <!-- Botón de búsqueda -->
        <br>
        <button type="button" class="btn btn-primary w-100" onclick="buscarDatos()">
        <i class="bi bi-search me-1"></i> Buscar </button>
      </div>
    </div>

    <div class="col-9">
  <div id="cuadro1" class="bg-white border rounded-4 shadow-sm p-4">
    <h4 class="mb-4">Seleccione su tabla de escalación</h4>
    
    <div class="areasxpais" id="areasxpais">
      <?php tbvoid(); ?>
    </div>
  </div>
</div>

  </div>
</div>


<!-- ESPACIO PARA LAS TABLAS DE AREAS -->


<!-- ESPACIO PARA la tabla de esclacion ya seleciionada  -->
  <div class="container-lg">
    <br>
    <h3> PRUEBA DE COMO SE MIRARIA LAS TABLAS  </h3>
    <?php printtables(); ?>
 </div>


</body>
</html>

<script>
    /* Inicializar DataTable
    $(document).ready(function () {
        $('#tablaContactos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
    */
</script>
