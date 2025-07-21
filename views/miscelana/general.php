<?php
  #  include_once './includes/BD_con/db_con.php';

#HEADER GENERICO PARA QUE SE VEA MAS BONITO 
function listarHeader($url) {
    ?>
  <header class="shadow-sm mb-4">
    <!-- Top Bar -->
    <div class="bg-dark text-white py-3">
      <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4 fw-bold m-0">Masivas Escalaciones</h1>
        <div>
        <!-- Navigation Bar -->
        <nav class="bg-light border-top border-bottom" style="border-radius: 20px;">
          <div class="container">
            <ul class="nav justify-content-start py-2">
              
              <li class="nav-item">
                <a class="nav-link text-dark fw-semibold px-3" href="<?= $url ?>./index.php">
                  <i class="bi bi-upload me-1"></i> Ingreso de Masiva
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-dark fw-semibold px-3" href="<?= $url ?>./app/tablero.php">
                  <i class="bi bi-kanban me-1"></i> Tablero
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-dark fw-semibold px-3" href="<?= $url ?>./app/asociados.php">
                  <i class="bi bi-exclamation-triangle me-1"></i> Fallas Asociadas
                </a>
              </li>

            </ul>
          </div>
        </nav>
        </div>

        <img src="<?= $url ?>./src/img/logo_frt_1.png" alt="Logo" style="height: 40px;">
      </div>
    </div>

  </header>
<?php
}

#PRUEBA como se veria la tablas
function printtables() {
    include './includes/BD_con/db_con.php';
    #esta es la consulta de sql de pruebas esta
        $query = "SELECT
            e.nivel,c.nombre,c.telefono,e.tiempo,e.comentario,tte.tipo
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id_contacto
        INNER JOIN tb_tipo_escalacion tte ON  e.id_tipo_escalacion = tte.id_tipo_escalacion 
        WHERE e.id_area  = 2 ";
        #realiza la consulta 
        $resultado = mysqli_query($general, $query);
    ?>
    <div class="container mt-8">
    <table id="TBescala" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Tiempo</th>
                <th>Caculadora</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contador = 1;  $hora=0; 
            while ($fila = mysqli_fetch_assoc($resultado)) {
               // Verifica si hay comentario
              $comentarioBadge = '';
              if (!empty($fila['comentario'])) {
                $comentarioBadge = "<span class='badge bg-light text-dark'>{$fila['comentario']}</span>";
              }
                // Alternar clases de color
                $claseFila = ($contador % 2 == 0) ? 'table-success' : 'table-danger';
                $hora = ($fila['tiempo']) + $hora;
                echo "<tr class='{$claseFila}'>";
                echo "<td>{$contador}/4</td>";
                echo "<td>{$fila['nombre']} {$comentarioBadge}</td>";
                echo "<td>{$fila['telefono']}</td>";
                echo "<td>{$fila['tiempo']} Horas</td>";
                echo "<td> <label class='form-label'> {$hora}:00:00 Hrs </label> </td>";
                echo "</tr>";
                $contador++;
            }
        #<button class='btn btn-primary btn-sm'>Mensaje</button>
            ?>

        </tbody>
    </table>
</div>
    <?php
}

#imprime solo una tabla vacia 
function tbvoid()  {
    ?>
    <table id="tablaContactos" class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>Nombre Área</th>
                <th>opcion</th>
              </tr>
            </thead>
          </table>
    <?php
}

# fila de los imputs para calcular las horas 
function fila_hras() {
?>
<div class="container-lg ">
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body">

      <div class="row g-3 justify-content-center align-items-end">

        <!-- HORA ACTUAL -->
        <div class="col-md-3">
          <div class="input-group">
            <span class="input-group-text">Hora Apertura</span>
            <input type="text" id="horaActual" class="form-control text-center" step="2" type="time"
            placeholder="14:00:00" readonly>
          </div>
        </div>

        <!-- TIEMPO ACUMULADO -->
        <div class="col-md-3">
          <div class="input-group">
            <span class="input-group-text">Tiempo Acumulado</span>
            <input type="text" id="tiempoAcumulado" class="form-control text-center" 
            placeholder="00:00:00" data-bs-toggle="tooltip" title="Tiempo acumulado del TK">
          </div>
        </div>

        <!-- BOTÓN -->
        <div class="col-md-2 d-grid">
          <button type="button" class="btn btn-secondary" onclick="calcularTiempos()"
          data-bs-toggle="tooltip" id="btnCalcular" title="Muestra la tabla de escalación." >Calcular</button>
        </div>

        <div class="col-md-2 d-grid">
          <button type="button" class="btn btn-danger" onclick="cerrarMasiva()"
          data-bs-toggle="tooltip" title="Cierra seguimiento Masiva" >Terminar </button>
        </div>

      </div>

    </div>
  </div>
</div>
<?php
}


#imprime los TEXT AREA PARA LOS MENSAJES 
function mensajes(){
  ?>
  <div class="container my-3" style="max-width: inherit;">
  <div class="row g-3 align-items-start ">
    
    <!-- Columna izquierda (7/12) -->
    <div class="col-md-6">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-2">
          <i class="fas fa-comment-dots text-muted"></i>
        </span>
        <textarea id="notaGenerada" class="form-control border-start-0 small " rows="6" placeholder="Mensaje de escalación..."></textarea>
        <button class="btn btn-outline-secondary" type="button" onclick="copiarTextoWhatsApp('notaGenerada')"
        data-bs-toggle="tooltip" title="copialo tu mensaje!">
          <i class="fa-solid fa-copy"></i>
          </button>
      </div>
    </div>

    <!-- Columna derecha (5/12) -->
  <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-text bg-light border-end-2">
        <i class="fa-brands fa-whatsapp text-muted"></i>
      </span>
      <textarea id="wasapp" 
              class="form-control border-start-0 small auto-ajuste-textarea" 
              rows="1" 
              placeholder="Mensaje WhatsApp..."
              oninput="ajustarAltura(this)"></textarea>
      <button class="btn btn-outline-secondary" type="button" onclick="copiarTextoWhatsApp('wasapp')"
        data-bs-toggle="tooltip" title="¡Copia tu mensaje!">
        <i class="fa-solid fa-copy"></i>
      </button>
    </div>
  </div>

  </div>
</div>

  <?php
}


# - PRUEBAS PARA EL TABLERO  - 
function tablero1(){
  include '../includes/BD_con/db_con.php';

    $query = "SELECT id_registro, falla_id, area_id, titulo, nivel, nombre, telefono, tiempo, 
    hora_apertura, hora_sumada, tiempo_acumulado, comentario, estado, fecha_registro 
    FROM tb_escalaciones_registro WHERE estado = 1
    order by hora_sumada" ;

    $resultado = mysqli_query($general, $query);

    echo "
    <div class='kanban-container'>
      <div class='kanban-column' id='gris'>

      </div>
      <div class='kanban-column' id='verde'>
        <h2>🟢 < 15 min</h2>
      </div>
      <div class='kanban-column' id='amarillo'>
        <h2>🟡 15 - 10 min</h2>
      </div>
      <div class='kanban-column' id='rojo'>
        <h2>🔴 > 10 min</h2>
      </div>
    </div> ";

  // Crear tarjetas
  while ($fila = mysqli_fetch_assoc($resultado)) {
    $dif_segundos = calcular_diferencia_segundos($fila['hora_sumada']);
     $quemado = '';
    // Clasificar por tiempo restante
    if ($dif_segundos > 1200){
      $columna = "gris";
    } elseif ($dif_segundos > 900) {
        $columna = "verde";
    } elseif ($dif_segundos > 600) {
        $columna = "amarillo";
    } elseif ($dif_segundos > 0) {
        $columna = "rojo";
    } else {
        $quemado = "quemado";
        $columna = "rojo";
    } 

      $url = 'index.php?fallaID=' . urlencode($fila['falla_id']) .
      '&areaSlct=' . urlencode($fila['area_id']) .
      '&horaAper=' . urlencode($fila['hora_apertura']) .
      '&tmpAcumu=' . urlencode($fila['tiempo_acumulado']).
      '&titulo=' . urlencode($fila['titulo']);
      
      $card = "<div class='card $columna' id='$quemado' 
      data-falla-id='" . htmlspecialchars($fila['falla_id']) . "'
      data-area-id='" . htmlspecialchars($fila['area_id']) . "'
      data-hora-apertura='" . htmlspecialchars($fila['hora_apertura']) . "'
      data-tiempo-acumulado='" . htmlspecialchars($fila['tiempo_acumulado']) . "'
      data-titulo='".htmlspecialchars($fila['titulo'])." '>
      
      <p><strong>ID:</strong> {$fila['falla_id']} || <strong>Hora:</strong> {$fila['hora_sumada']} Hrs </p>
      <p><strong>Título:</strong> {$fila['titulo']} </p> </div>";

    echo "<script>document.getElementById('$columna').innerHTML += `" . $card . "`;</script>";
  }
}

// Función para convertir hora_sumada a timestamp
function calcular_diferencia_segundos($hora_sumada) {
    $ahora = strtotime(date("H:i:s"));
    $target = strtotime($hora_sumada);
    return $target - $ahora;
}

// CONTENIDO DEL LOADER 
function loader() {
  ?>
  <!-- Loader en pantalla completa -->
  <div id="global-loader" style="display: none;">
    <div class="loader-overlay">
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-3 text-white">Procesando, por favor espere...</p>
    </div>
  </div>
<?php
}

// tablero copia 
function tablerohueco() {
  ?>
    <table id="tablaIncidencias" class="table table-bordered table-striped table-hover dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th colspan="5">Detalle</th>
          <th colspan="5">NOC</th>
          <th colspan="5">Gerencia Técnica</th>
          <th>CNOC</th>
        </tr>

        <tr>
            <th>País</th>
            <th>Ticket</th>
            <th>Área</th>
            <th>Hora Total</th>
            <th>Afectación</th>

            <th>n1</th>
            <th>n2</th>
            <th>n3</th>
            <th>n4</th>
            <th>n5</th>

            <th>n1</th>
            <th>n2</th>
            <th>n3</th>
            <th>n4</th>
            <th>n5</th>

          <th>NX</th>
        </tr>
    </thead>
  <?php
}

/*  apara colocar horas aleatorias 
UPDATE tb_escalaciones_registro
SET hora_sumada  = SEC_TO_TIME(FLOOR(3600 * (11 + RAND() * 10))); */

?>
