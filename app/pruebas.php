<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .dashboard-card {
      transition: transform 0.2s ease-in-out;
    }
    .dashboard-card:hover {
      transform: scale(1.02);
    }
    .border-accent {
      border-left: 4px solid #0d6efd;
    }
  </style>
</head>

<body class="bg-light">
<div class="container py-4">

  <!-- Título principal -->
  <div class="mb-4 text-center">
    <h2 class="fw-bold text-primary"><i class="fas fa-satellite-dish me-2"></i>Centro de Monitoreo de Fallas</h2>
    <p class="text-muted">Consulta fallas asociadas</p>
  </div>

  <!-- BUSCADOR AVANZADO -->
  <div class="card shadow-sm mb-4 dashboard-card">
    <div class="card-body">
      <div class="row g-3 align-items-end">

        <div class="col-md-4">
          <label for="falla" class="form-label"><i class="fas fa-search me-1"></i>ID de Falla</label>
          <input type="text" id="falla" class="form-control" placeholder="Ej. F6131072">
        </div>

        <div class="col-md-4">
          <label for="areasxpais" class="form-label"><i class="fas fa-network-wired me-1"></i>Área asignada</label>
          <select id="areasxpais" class="form-select">
            <option value="">Seleccionar...</option>
          </select>
        </div>

        <div class="col-md-4 d-grid">
          <button class="btn btn-primary" id="buscarFalla"><i class="fas fa-search-location me-1"></i>Buscar Falla</button>
        </div>

      </div>
    </div>
  </div>

  <!-- DASHBOARD RESUMEN -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-success shadow-sm dashboard-card">
        <div class="card-body">
          <h6 class="card-title"><i class="fas fa-check-circle me-1"></i>Fallas Activas</h6>
          <h2 class="fw-bold">3</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-warning shadow-sm dashboard-card">
        <div class="card-body">
          <h6 class="card-title"><i class="fas fa-clock me-1"></i>Tiempo Promedio</h6>
          <h2 class="fw-bold">00:37:15</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-danger shadow-sm dashboard-card">
        <div class="card-body">
          <h6 class="card-title"><i class="fas fa-exclamation-triangle me-1"></i>Fallas Críticas</h6>
          <h2 class="fw-bold">1</h2>
        </div>
      </div>
    </div>
  </div>

  <!-- DETALLES DE LA FALLA -->
  <div class="card shadow-sm mb-4 dashboard-card border-accent">
    <div class="card-body">
      <h5 class="text-primary"><i class="fas fa-info-circle me-1"></i>Detalles de la Falla</h5>
      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <label for="horaActual" class="form-label"><i class="fas fa-clock me-1"></i>Hora Apertura</label>
          <input type="time" id="horaActual" class="form-control">
        </div>
        <div class="col-md-6">
          <label for="tiempoAcumulado" class="form-label"><i class="fas fa-hourglass-half me-1"></i>Tiempo Acumulado</label>
          <input type="text" id="tiempoAcumulado" class="form-control" placeholder="00:45:00">
        </div>
        <div class="col-12">
          <label class="form-label"><i class="fas fa-heading me-1"></i>Título:</label>
          <p class="lead mb-0 text-dark fw-semibold" id="tituloFalla">Ninguna falla seleccionada</p>
        </div>
      </div>
    </div>
  </div>

  <!-- LISTADO DE FALLAS -->
  <div class="card shadow-sm dashboard-card">
    <div class="card-body">
      <h5 class="mb-4 text-dark"><i class="fas fa-list me-1"></i>Fallas Asociadas</h5>

      <div class="list-group">

        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light rounded border-start border-4 border-primary mb-2">
          <div class="ms-2 me-auto">
            <div class="fw-bold">F6131072 - Corte de energía</div>
            Área: Soporte | 00:45:00
          </div>
          <span class="badge bg-success rounded-pill">Activa</span>
        </div>

        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-white rounded mb-2">
          <div class="ms-2 me-auto">
            <div class="fw-bold">F6119058 - Fallo DNS</div>
            Área: Infraestructura | 01:20:00
          </div>
          <span class="badge bg-secondary rounded-pill">Histórica</span>
        </div>

        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-white rounded">
          <div class="ms-2 me-auto">
            <div class="fw-bold">F6100235 - Pérdida de conexión</div>
            Área: Redes | 00:35:00
          </div>
          <span class="badge bg-danger rounded-pill">Crítica</span>
        </div>

      </div>
    </div>
  </div>

</div>


<!-- Opcional: Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
