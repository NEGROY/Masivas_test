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
<div class="container ">
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

      <!-- Select de Área de Escalación -->
          <br>
        <label for="areaEscalacion" class="form-label">Áreas de Escalación:</label>
        <select id="areaEscalacion" name="areaEscalacion" class="js-example-basic-single">
          <option value="">Seleccione un área</option>
          <option value="CNOC_CBO_REACTIVO">CNOC_CBO_REACTIVO</option>
          <option value="GESTION N1_CBO">GESTION N1_CBO</option>
          <option value="MESA DE AYUDA">MESA DE AYUDA</option>
        </select>
        <br>

      <!-- Botón de búsqueda -->
        <br>
        <button type="button" class="btn btn-primary w-100" onclick="buscarDatos()">
        <i class="bi bi-search me-1"></i> Buscar </button>
      </div>
    </div>
  

  <!-- Columna de  TB escalación  -->
  <div class="col-9">
  <div id="cuadro1" class="bg-white border rounded-4 shadow-sm p-4">
    <div class="row align-items-center mb-3">
      <div class="col-md-6">
        <h4 class="mb-0">Seleccione su tabla de escalación</h4>
      </div>
      <div class="col-md-6">
        <input class="form-control" id="myInput" type="text" placeholder="Buscar">
      </div>
    </div>

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

    <!--HORA ACTUAL Y MODIFICABLE -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label for="horaActual" class="form-label">Hora actual</label>
        <input type="text" id="horaActual" class="form-control" value='<?= $hora ?>'
        pattern="^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$" placeholder="Ej. 14:35:00">
      </div>

      <!-- HORA ACUMULADA TRAIDA DEL JSON  -->
      <div class="col-md-6">
        <label for="tiempoAcumulado" class="form-label">Tiempo acumulado</label>
        <input type="text" id="tiempoAcumulado" class="form-control" placeholder="Ej. 3 horas 15 min">
      </div>
    </div>

    <h3> PRUEBA DE COMO SE MIRARIA LAS TABLAS  </h3>
    <?php printtables(); ?>
 </div>


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
