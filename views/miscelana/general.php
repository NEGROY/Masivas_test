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
        $query = "SELECT e.nivel, c.nombre, c.telefono, e.tiempo 
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id_contacto
        WHERE e.id_tipo_escalacion = 2";
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
                // Alternar clases de color
                $claseFila = ($contador % 2 == 0) ? 'table-success' : 'table-danger';
                $hora = ($fila['tiempo']) + $hora;
                echo "<tr class='{$claseFila}'>";
                echo "<td>{$contador}/4</td>";
                echo "<td>{$fila['nombre']}</td>";
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
            <span class="input-group-text">Hora actual</span>
            <input type="text" id="horaActual" class="form-control text-center"
                   pattern="^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$"
                   placeholder="Ej. 14:00:00">
          </div>
        </div>

        <!-- TIEMPO ACUMULADO -->
        <div class="col-md-3">
          <div class="input-group">
            <span class="input-group-text">Acumulado</span>
            <input type="text" id="tiempoAcumulado" class="form-control text-center"
                   placeholder="Ej. 3h 15m">
          </div>
        </div>

        <!-- BOTÓN -->
        <div class="col-md-2 d-grid">
          <button type="button" class="btn btn-secondary" onclick="calcularTiempos()">Calcular</button>
        </div>

      </div>

    </div>
  </div>
</div>
<?php
}




?>
