<?php
  #  include_once './includes/BD_con/db_con.php';

#HEADER GENERICO PARA QUE SE VEA MAS BONITO 
function listarHeader() {
    ?>
    <header class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h3 m-0">Mi Aplicación</h1>
        <img src="src\img\logo_frt_1.png" alt="Logo" style="height: 40px;">
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
            placeholder="14:00:00">
          </div>
        </div>

        <!-- TIEMPO ACUMULADO -->
        <div class="col-md-3">
          <div class="input-group">
            <span class="input-group-text">Tiempo Acumulado</span>
            <input type="text" id="tiempoAcumulado" class="form-control text-center" 
            placeholder="00:03:20" readonly data-bs-toggle="tooltip" title="tiempo acumulado del TK">
          </div>
        </div>

        <!-- BOTÓN -->
        <div class="col-md-2 d-grid">
          <button type="button" class="btn btn-secondary" onclick="calcularTiempos()"
          data-bs-toggle="tooltip" title="Muestra la tabla de escalación." >Calcular</button>
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
        <textarea id="wasapp" class="form-control border-start-0 small " rows="6" placeholder="Mensaje WhatsApp..."></textarea>
          <button class="btn btn-outline-secondary" type="button" onclick="copiarTextoWhatsApp('wasapp')"
          data-bs-toggle="tooltip" title="copialo tu mensaje!">
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
    FROM tb_escalaciones_registro WHERE estado = 1" ;

    $resultado = mysqli_query($general, $query);

echo "
<div class='kanban-container'>
  <div class='kanban-column' id='verde'>
    <h2>🟢 > 15 min</h2>
  </div>
  <div class='kanban-column' id='amarillo'>
    <h2>🟡 10 - 15 min</h2>
  </div>
  <div class='kanban-column' id='rojo'>
    <h2>🔴 < 10 min</h2>
  </div>
</div> ";

// Crear tarjetas
while ($fila = mysqli_fetch_assoc($resultado)) {
    $dif_segundos = calcular_diferencia_segundos($fila['hora_sumada']);

    // Clasificar por tiempo restante
    if ($dif_segundos > 900) {
        $columna = "verde";
    } elseif ($dif_segundos > 600) {
        $columna = "amarillo";
    } else {
        $columna = "rojo";
    }

    $card = "<div class='card $columna'>
      <p><strong>ID:</strong> {$fila['falla_id']}  ||  <strong>Hora:</strong> {$fila['hora_sumada']} Hrs </p>
      <p><strong>Título:</strong> {$fila['titulo']}</p>
      <p><strong>Escalacion:</strong> {$fila['nombre']} ({$fila['telefono']})</p>
    </div>";

    echo "<script>document.getElementById('$columna').innerHTML += `" . $card . "`;</script>";
}
}

// Función para convertir hora_sumada a timestamp
function calcular_diferencia_segundos($hora_sumada) {
    $ahora = strtotime(date("H:i:s"));
    $target = strtotime($hora_sumada);
    return $target - $ahora;
}

/*  apara colocar horas aleatorias 
UPDATE tb_escalaciones_registro
SET hora_sumada  = SEC_TO_TIME(FLOOR(3600 * (11 + RAND() * 10))); */

?>
