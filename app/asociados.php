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
            <input type="text" id="falla" class="form-control" placeholder="F6131072">
            <button class="btn btn-outline-primary" type="button" id="buscarFalla" onclick="valdiaFAlla()">Buscar</button>
          </div>
        </div>

        <!-- Información de la falla -->
        <div class="col-lg-6">
          <div class="p-3 border-start border-4 border-primary bg-white rounded h-100">
            <h6 class="text-primary mb-1">Título de la Falla:</h6>
            <p class="mb-0 fw-semibold text-dark" id="tituloFalla">Ninguna falla seleccionada</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <!-- Sección de listado -->
  <div class="card shadow-sm p-4">
    <h5 class="mb-3">Fallas asociadas</h5>
    
    <!-- Cuadro resaltado -->
    <div class="border-start border-5 border-primary ps-3 py-2 bg-light rounded mb-2">
      <p class="mb-1 fw-semibold">Falla #1: Corte de energía en zona 5</p>
      <small class="text-muted">Área: Soporte | Tiempo acumulado: 00:45:00</small>
    </div>

    <!-- Ejemplo de más fallas (puedes hacer esto dinámico) -->
    <div class="border-start border-5 border-secondary ps-3 py-2 bg-white rounded mb-2">
      <p class="mb-1">Falla #2: Interrupción en enlace principal</p>
      <small class="text-muted">Área: Redes | Tiempo acumulado: 01:15:00</small>
    </div>

    <div class="border-start border-5 border-warning ps-3 py-2 bg-white rounded">
      <p class="mb-1">Falla #3: Problema de DNS</p>
      <small class="text-muted">Área: Infraestructura | Tiempo acumulado: 00:20:00</small>
    </div>
  </div>
</div>


    
</body>
</html>