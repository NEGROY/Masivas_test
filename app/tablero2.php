<?php
    require_once '../includes/phpFun/fun.php';
    require_once '../includes/2incl.php';
    require_once '../views/miscelana/general.php';

    date_default_timezone_set('America/Guatemala');
    $fecha = date('m-d-Y');
    $hora  = date('H:i');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tablero Simplificado</title>
    <link rel="stylesheet" href="../includes/CSS/TABLERO2.css">
</head>
<body>

<div>
  <?php listarHeader('.'); ?>
</div>

<?php
include '../includes/BD_con/db_con.php';
if ($general->connect_error) {
    die("Error de conexión: " . $general->connect_error);
}

$sql = "
SELECT
    area_escalacion AS `area`,
    MAX(CASE WHEN nivel = 'N1' THEN tiempo_escalacion END) AS N1,
    MAX(CASE WHEN nivel = 'N2' THEN tiempo_escalacion END) AS N2,
    MAX(CASE WHEN nivel = 'N3' THEN tiempo_escalacion END) AS N3,
    MAX(CASE WHEN nivel = 'N4' THEN tiempo_escalacion END) AS N4,
    MAX(CASE WHEN nivel = 'NS' THEN tiempo_escalacion END) AS NS
FROM tb_tiempos_escalacion
GROUP BY area_escalacion
ORDER BY area_escalacion;
";

$result = $general->query($sql);
?>

<div class="py-4">
  <div class="row g-4">

    <!-- Panel izquierdo con tabla -->
    <div class="col-md-3">
      <div class="card card-escalacion p-3">
        <div class="table-responsive">
          <table class="  table-sm table-bordered tabla-escalacion mb-0">
            <thead>
              <tr>
                <th class="th-area">Área</th>
                <th class="th-n1">N1</th>
                <th class="th-n2">N2</th>
                <th class="th-n3">N3</th>
                <th class="th-n4">N4</th>
                <th class="th-ns">NS</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td class="td-area"><?= htmlspecialchars($row['area']) ?></td>
                    <td><?= htmlspecialchars($row['N1']) ?></td>
                    <td><?= htmlspecialchars($row['N2']) ?></td>
                    <td><?= htmlspecialchars($row['N3']) ?></td>
                    <td><?= htmlspecialchars($row['N4']) ?></td>
                    <td><?= htmlspecialchars($row['NS']) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" class="text-muted text-center">Sin datos disponibles.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Panel derecho con título -->
    <div class="col-md-9 d-flex align-items-center">
      <div class="p-4 rounded shadow-sm bg-white border-start border-4 border-dark w-100">
        <div class="dashboard-title">Tiempos de Escalación por Área</div>

      </div>
    </div>

  </div>
</div>

    <div class="tabla1">
        <table id="tablaIncidencias" class="table table-bordered table-striped table-hover dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                  <th colspan="5" class="text-center bg-secondary text-white border border-dark">Detalle</th>
                  <th colspan="5" class="text-center bg-primary text-white border border-dark">NOC</th>
                  <th colspan="5" class="text-center bg-info text-white border border-dark">Gerencia Técnica</th>
                  <th class="text-center bg-dark text-white border border-dark">CNOC</th>
                </tr>


                <tr class="table-secondary text-center align-middle">
                  <th class="border border-dark">País</th>
                  <th class="border border-dark">Ticket</th>
                  <th class="border border-dark">Área</th>
                  <th class="border border-dark">Hora Total</th>
                  <th class="border border-dark">Afectación</th>

                  <th class="border border-dark bg-success text-white">n1</th>
                  <th class="border border-dark bg-success bg-opacity-75 text-white">n2</th>
                  <th class="border border-dark bg-warning text-dark">n3</th>
                  <th class="border border-dark bg-orange-300 text-dark" style="background-color: #fd7e14 !important;">n4</th>
                  <th class="border border-dark bg-danger text-white">n5</th>

                  <th class="border border-dark bg-success text-white">n1</th>
                  <th class="border border-dark bg-success bg-opacity-75 text-white">n2</th>
                  <th class="border border-dark bg-warning text-dark">n3</th>
                  <th class="border border-dark bg-orange-300 text-dark" style="background-color: #fd7e14 !important;">n4</th>
                  <th class="border border-dark bg-danger text-white">n5</th>

                  <th class="border border-dark bg-dark text-white">NX</th>
                </tr>

            
            <tbody>
                <?php
                // Conexión y consulta (ajusta tu conexión y asegúrate de que estás usando mysqli o PDO)
                $query = "SELECT 
                    er.falla_id,
                    er.tiempo_acumulado,
                    er.titulo,
                    ae.nombre_area,
                    p.nombre_pais
                    FROM tb_escalaciones_registro AS er
                    INNER JOIN tb_area_escalacion AS ae ON er.area_id = ae.id_area
                    INNER JOIN tb_pais AS p ON ae.id_pais = p.id_pais
                    WHERE er.estado = 1";
                $result = mysqli_query($general, $query); // Asegúrate de tener una conexión $conn
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['nombre_pais']}</td>";
                    echo "<td>{$row['falla_id']}</td>";
                    echo "<td>{$row['nombre_area']}</td>";
                    echo "<td>{$row['tiempo_acumulado']}</td>";
                    echo "<td>{$row['titulo']}</td>";
                
                // NOC
                echo "<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
            
                // Gerencia Técnica
                echo "<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
            
                // CNOC
                echo "<td>0</td>";
            
                echo "</tr>";
                }
                ?>
            </tbody>
            
        </table>
    </div>


</body>
</html>


<script>
    $(document).ready(function () {
        $('#tablaIncidencias2').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ordering: true
        });
    });
</script>   

<?php
    /*     SELECT 
        er.id_registro,
        er.fecha_registro,
        er.falla_id,
        er.titulo,
        er.nivel,
        er.nombre,
        er.telefono,
        er.tiempo,
        er.hora_sumada,
        er.hora_apertura,
        er.tiempo_acumulado,
        er.estado,
        er.comentario,
        ae.nombre_area,
        p.nombre_pais,
        p.ext
    FROM tb_escalaciones_registro AS er
    INNER JOIN tb_area_escalacion AS ae ON er.area_id = ae.id_area
    INNER JOIN tb_pais AS p ON ae.id_pais = p.id_pais
    WHERE er.estado = 1; */
?>
