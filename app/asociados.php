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
    <title>Asociados</title>
    <link rel="stylesheet" href="../includes/CSS/asoc.css">
</head>
<body>

<div>
  <?php listarHeader('.'); ?>
</div>

<div class="container my-4">
  <!-- Sección de entrada -->
<div class="container my-4">
  <div class="card border-0 shadow mb-4">
    <div class="card-body bg-light rounded p-4">
      <div class="row g-3 align-items-center">
        
        <!-- Input de falla -->
        <div class="col-lg-6">
          <label for="falla" class="form-label text-muted">Ingresar ID de Falla</label>
          <div class="input-group">
          <form id="formExport" method="POST" action="./expot.php" target="_blank"  >
            <input type="text" name="tkid" id="fallaIDInput" class="form-control" placeholder="F6144046" value="F5875158">
          </form>
            <button class="btn btn-outline-primary" type="button" id="buscarFalla" onclick="fallamasiva()">Buscar</button>
          </div>
        </div>

              <!-- Información de la falla -->
        <div class="col-lg-6 d-flex justify-content-between align-items-center">
          <div class="p-3 border-start border-4 border-primary bg-white rounded w-100 me-2">
            <h6 class="text-primary mb-1">Falla Seleccionada:</h6>
            <p class="mb-0 fw-semibold text-dark" id="tituloFalla">descarga en excel</p>
          </div>
          <button id="btnExportar" class="btn btn-success shadow-sm" onclick="exportarExcel()" disabled>Exportar Excel</button>
      
        </div>

      </div>
    </div>
  </div>
</div>

  <!-- Sección de listado -->
  <div class="fallas"></div>

</div>
    

<div><?php loader()?></div>
</body>
</html>

<script>
// BOTON PARA MBUSCAR LOS DATOS DE LA FALLA 
function fallamasiva(){
    const fallaID = document.getElementById('fallaIDInput').value.trim();
    const condi = "asocciadas"; 
    console.log(fallaID);

    if (!fallaID) {
      $(".fallas").html('<div class="alert alert-warning">Debe ingresar un ID de falla.</div>');
      return;
    }
    // Mostrar loader global
    document.getElementById('global-loader').style.display = 'flex';

    $.ajax({
        url: "./api_asocia2.php",
        method: "POST",
        data: { fallaID, condi },
        success: function(data) {
            $(".fallas").html(data);
            const btn = document.getElementById("btnExportar");
            btn.disabled = false;
            btn.innerHTML = "Exportar Excel";
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", status, error);
            $(".fallas").html('<div class="alert alert-danger">Error al buscar la falla.</div>');
            const btn = document.getElementById("btnExportar");
            btn.disabled = true;
            btn.innerHTML = "Busca una falla...";
        },
        complete: function() {
            // Ocultar loader global después de la petición
            document.getElementById('global-loader').style.display = 'none';
        }
    });
}

// funcion para traer la ifgno 
function guardarInputs(event) {
  event.preventDefault(); // Detener el envío del formulario

  const form = event.target;
  const formData = new FormData(form);
  const tkid = event.target.id;

  // Convertir FormData en un objeto legible
  const datos = Object.fromEntries(formData.entries());
  console.log("Valores del formulario:", tkid , datos);

  $.ajax({
    url: "../views/miscelana/asoc_insert.php",
    method: "POST",
    data: {tkid , datos },
    success: function(data) {
      //$(".fallas").html(data);
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: data,
        showConfirmButton: false,
        timer: 1000
      });
      console.log(data);
    }
  });

  /*return;
  sleep(60000);*/
}

// funcion para exportar a excel 
 // Exportar Excel
    function exportarExcel() {
      const tkidActual = document.getElementById("fallaIDInput").value;
        if (!tkidActual) return;
      document.getElementById("formExport").submit();
}

</script>
