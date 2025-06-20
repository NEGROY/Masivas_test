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
    <title>masivas Prueba</title>
    <script src="./includes/JS/index.js"></script>
</head>
<body>

<div>
  <?php listarHeader(); ?>
</div>

<!-- Documentos de pruewba -->
<div class="container " style="max-width: inherit;">
  <div class="row g-3">
    
  <!-- Columna de selección de país -->
    <div class="col-md-3">
      <div class="p-3 border rounded-3 shadow-sm bg-light ">
        
      <!-- Input Falla -->
        <label for="falla" class="form-label">Falla ID:</label>
        <input type="text" id="falla" name="falla" class="form-control mb-3" value='F6131074' placeholder="Fxxxx">

      <!-- Select de país -->
        <label for="pais" class="form-label">País:</label>
        <select id="pais" name="pais" class="form-select" onchange="desig(this.value)">
          <?php listarPaises(); ?>
        </select>
        <input type="hidden" id="ids" name="ids">

      <!-- Select de Área de Escalación -->
          <br>
        <label for="areasxpais" class="form-label">Áreas de Escalación:</label>
        <select id="areasxpais" name="areasxpais" class="js-example-basic-single">
          <option value="">---Seleccione un Pais---</option>
        </select>
        <br>

      <!-- Botón de búsqueda -->
        <br>
        <button type="button" class="btn btn-primary w-100" onclick="buscarDatos_api()">
        <i class="bi bi-search me-1"></i> Buscar </button>
      </div>
    </div>

  <!-- Columna de  TB escalación  -->
  <div class="col-9">
    <?php fila_hras(); ?>
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


<?php  mensajes(); ?>


</body>
</html>

<script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });


    // FUNCION PARA QUE RELLENE LA HORA ACTUAL DE FORMA AUTOMATICA 
    document.addEventListener("DOMContentLoaded", () => {
    const horaActual = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById("horaActual").value = horaActual;
    });

    // Select2 
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
    });



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
