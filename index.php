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

<div class="container-md mb-5">
  <h2> Elige un pais</h2>
    <label for="pais">País:</label>
    <select id="pais" name="pais" class="form-control" onchange="desig(this.value)" >
        <?php listarPaises(); ?>
    </select>
    <input type="hidden" id="ids" name="ids">
</div>

<!-- ESPACIO PARA LAS TABLAS DE AREAS -->
<div class="container-lg" id='cuadro1'>
  <br>
  <h3 class="text-primary mb-4">Seleccione su <span class="text-danger">*</span> tabla de escalación</h3>
  <div class="areasxpais" id="areasxpais">
    <table id="tablaContactos" class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Tiempo</th>
          <th>Acción</th>
        </tr>
      </thead>
      </table>
  </div>
</div>

<!-- ESPACIO PARA la tabla de esclacion ya seleciionada  -->
  <div class="container-lg">
    <br>
    <h3> PRUEBA DE COMO SE MIRARIA LAS TABLAS  </h3>
    <?php printtables(); ?>
 </div>


</body>
</html>

<script>
    // Inicializar DataTable
    $(document).ready(function () {
        $('#tablaContactos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
