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

    loader();
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
        <input type="text" id="falla" name="falla" class="form-control mb-3"   placeholder="F6144046">

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
    <b><small class="text-muted d-block pr-2" id='titulo'  > - </small></b>
    
    <?php fila_hras(); ?>
    <div class="container">
      <h3 class="text-muted d-block pr-2" id='titulos'> - </h3>
    </div>
    <div class="container TB_calcu mt-9" id='TB_calcu'>
      <br>
      
    </div>
  </div>

  </div>
</div>

  <div id="resultado" class="mt-3"></div>

<?php mensajes(); ?>

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
    } else {
      console.warn("fallaID inválido o no proporcionado "+fallaID);  
      // Ocultar loader global después de la petición // document.getElementById('global-loader').style.display = 'none';
    }
};

  
// Función que carga la información de la falla y muestra un loader mientras tanto
function recarga(fallaID) {

  console.log("Cargando datos para falla ID:", fallaID);
    const loader = document.getElementById('global-loader');
    loader.style.display = 'flex'; // Mostrar loader

  $.ajax({
    url: "./views/crud/escalaciones.php",
    method: "POST",
    data: { fallaID: fallaID, condi: 'recargash' },
    dataType: "json", // 👈 hace que jQuery lo parsee automáticamente
    success: function (json) {
      console.log("Respuesta del servidor:", json);

      if (!json || !json.data || !json.data.length) {
        console.warn("No se encontraron datos válidos");
        return;
      }

      const info = json.data[0];
      console.log("Información de la falla:", info);

      // Llamadas a otras funciones o asignaciones
      desig(info.id, info.area_id);
      document.getElementById("pais").value = info.id_pais;
      document.getElementById("falla").value = info.falla_id;
      document.getElementById("horaActual").value = info.hora_apertura;
      document.getElementById("tiempoAcumulado").value = info.tiempo_acumulado;
      document.getElementById("titulo").textContent = decodeURIComponent(info.titulo);
      document.getElementById('acumulado').value =  info.nivel;
      // OPEN TIME, PARA INGRESAR LOS VALORES 
      document.getElementById("open_time").value = decodeURIComponent(info.OPEN_TIME);
      
        const esFallaAbierta = !info.CLOSE_TIME || info.CLOSE_TIME.trim() === "";
        const botonCalcular = document.getElementById('btnCalcular');
        const campoCierre = document.getElementById('CIERRE');
        
        console.log(`TK encontrado: ${esFallaAbierta}`);

        validarFallaOpen(esFallaAbierta, campoCierre, botonCalcular, info.CLOSE_TIME); // Actualiza el estado del botón y campo de cierre

      calcularTiempos2(
        info.titulo,
        info.falla_id,
        info.hora_apertura,
        info.tiempo_acumulado,
        info.area_id,
        "notaGenerada",
        info.nivel
      );
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error AJAX:", textStatus, errorThrown);
      alert("Ocurrió un error al cargar los datos. Intente nuevamente.");
    },
    complete: function () {
      // ✅ Se ejecuta tanto si fue éxito como error
      loader.style.display = 'none';
      console.log("Carga de datos completada");
    }
  });
}


</script>
