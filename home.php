<?php
session_start();
require_once("config.php");

if (!isset($_SESSION['usuario'])) {
    echo $_SESSION['usuario'] . " No has iniciado sesión. Redirigiendo al login...";  
    header('Location: ' . urlsite . 'index.php');
    exit();
}


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
    <title>masivas Prueba</title>
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
        <label for="falla" class="form-label">HORA DE CIERRE</label>
        <input type="text" id="CIERRE" class="form-control mb-3"  placeholder="-" disabled autocomplete="off" >

      <!-- Select de país -->
        <label for="pais" class="form-label">País:</label>
        <select id="pais" name="pais" class="form-select" onchange="desig(this.value)">
          <option autocomplete="off" value="0" selected >Busque un Pais</option>
          <?php listarPaises(); ?>
        </select>
        <input type="hidden" id="ids" name="ids">

      <!-- Select de Área de Escalación -->
          <br>
        <label for="areasxpais" class="form-label">Áreas de Escalación:</label>
        <select id="areasxpais" name="areasxpais" class="js-example-basic-single" 
        onchange="habilitarBuscar()">
          <option value="">---Seleccione un Pais---</option>
        </select>
        <br>

      <!-- Botón de búsqueda -->
        <br>
        <button type="button" class="btn btn-primary w-100" onclick="buscarDatos_api()" id="btnBuscar"  disabled>
        <i class="bi bi-search me-1"></i> Buscar </button>
      </div>
    </div>

  <!-- Columna de  TB escalación  -->
  <div class="col-9">
    <small class="text-muted d-block pr-2" id='titulo'> - </small>  
    
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

<?php mensajes(); ?>

<div><?php loader()?></div>

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

    /* FUNCION PARA QUE RELLENE LA HORA ACTUAL DE FORMA AUTOMATICA 
    document.addEventListener("DOMContentLoaded", () => {
    const horaActual = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById("horaActual").value = horaActual;
    }); */

    window.addEventListener('DOMContentLoaded', () => {
      const paisSelect = document.getElementById('pais');
      if (paisSelect) {
        paisSelect.selectedIndex = 0; // Asegura que "Busque un Pais" esté seleccionado
      }
    });

    // Select2 
    $(document).ready(function() {
    $('.js-example-basic-single').select2();
    });
    

window.onload = function () {
  const params = new URLSearchParams(window.location.search);
  const fallaID = params.get("Fid"); 
  // Validar fallaID únicamente para iniciar
    if (fallaID && fallaID.trim() !== "") {
    // Mostrar loader global
    document.getElementById('global-loader').style.display = 'flex';
    // console.log(`ID: ${fallaID} | Área: ${areaSlct} | Hora apertura: ${hrActual} | Tiempo acumulado: ${tmpAcumu}`);
    recarga(fallaID);
    /* recarga(fallaID).then(() => {
      calcularTiempos(); }); */
    // Aquí podrías llamar tu función AJAX o continuar con el flujo*/
    } else {
      console.warn("fallaID inválido o no proporcionado "+fallaID);  
      // Ocultar loader global después de la petición
      document.getElementById('global-loader').style.display = 'none';
    }
    // Ocultar loader global después de la petición
    document.getElementById('global-loader').style.display = 'none';
};

/// PARA QUE AL MOMENTO DE ENVIAR EL ID DE FALLA, SE RELLENE LOS CAMPOS
function recarga(fallaID){
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {fallaID: fallaID, condi:'recargash'},
        success: function(data) {        
          console.log("Respuesta del servidor:", data);
          const json = JSON.parse(data);
          //console.log("Objeto JSON:", json);
          const info = json.data[0]; // Primer objeto del array
          console.log("Información de la falla:", info); // para imprimir toda la info
          desig(info.id, info.area_id);
          document.getElementById("pais").value = info.id_pais;
            // Asignar valores a inputs o elementos HTML
          document.getElementById("falla").value = info.falla_id;
          document.getElementById("horaActual").value = info.hora_apertura;
          document.getElementById("tiempoAcumulado").value = info.tiempo_acumulado;
          document.getElementById("titulo").textContent = decodeURIComponent(info.titulo);
          // select 
          //$('#areasxpais').val(info.area_id).trigger('change');
          // document.getElementById("areasxpais").value = info.area_id;
          calcularTiempos2(info.titulo, info.falla_id, info.hora_apertura, info.tiempo_acumulado, info.area_id);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error AJAX:", textStatus, errorThrown);
    }
  }) 

}

function  calcularTiempos2(titulo,fallaID,hrActual,tmpAcumu,areaSlct) {
condi = "TB_calculadora"; 
dashboard = 1;
    $.ajax({
        url: "./views/crud/escalaciones.php",
        method: "POST",
        data: {titulo,fallaID,hrActual,tmpAcumu,areaSlct,condi, dashboard},
        success: function(data) {
            $("#TB_calcu").html(data);
    } })
}

 

</script>
