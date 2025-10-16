<?php
    include '../includes/BD_con/db_con.php';

    // validar cadenas 
function safe_escape($general, $value) {
    return mysqli_real_escape_string($general, $value ?? '');
}
    $fallaID = $_POST["fallaID"];
    //echo $fallaID;
    // URL de la API
    $url = "http://172.20.97.102:8000/masivas/list/{$fallaID}?token=masivas2025"; // ← cambia esto a la URL real
    // Consumir la API con file_get_contents
    $response = @file_get_contents($url);
    // Verificar si no hay respuesta o si el servidor devolvió un error
    if ($response === FALSE) {
      echo "<p class='text-muted'>Validar FALLA ID.</p>";
      exit;
    }
    // Decodificar el JSON
    $data = json_decode($response, true);
    /* Verificar si la respuesta es válida*/
    if (is_null($data)) {
      echo "<p class='text-muted'>Error al decodificar JSON.</p>";
      exit;
    }
    // Verificar si la respuesta es válida y contiene el código 200
    if (!isset($data['code']) || $data['code'] !== 200) {
      echo "<p class='text-muted'> Sin respuesta de Base de datos. CONTACTAR CON ADMINISTRADOR. </p>";
      exit;
    }

$tickets = $data['data'] ?? [];

// Recorrer los tickets
foreach ($tickets as $ticket) {
    $tk         = $ticket['NUMBER'] ?? '';
    $enlace     = $ticket['TG_ENLACE'] ?? null;
    $company    = $ticket['COMPANY'] ?? null;
    $close_time = $ticket['CLOSE_TIME'] ?? null;
    $OPEN_TIME = $ticket['OPEN_TIME'] ?? null;
    $desc       = $ticket['BRIEF_DESCRIPTION'] ?? null;
    $pais       = $ticket['PAIS'] ?? null;
    $tk_masiva  = $ticket['FALLA_MASIVA'] ?? null; 
    
    // Generar uniqid como combinación de FALLA_MASIVA y TG_ENLACE
    $uniqid = $tk_masiva . '' . $tk;
    //echo $OPEN_TIME . "<br>". $tk;
    
    // Ejecutar el insert con ON DUPLICATE KEY UPDATE
    $sql = "
        INSERT INTO tb_fallas_asociadas 
        (uniqid, tk_id, tk_masiva, ENLACE, COMPANY, CLOSE_TIME, DESCRIPTION, PAIS, fecha_ingreso, OPEN_TIME)
        VALUES ('$uniqid', '$tk', '$tk_masiva', '$enlace', '$company', '$close_time', '$desc', '$pais', current_timestamp(), '$OPEN_TIME')
        ON DUPLICATE KEY UPDATE
            COMPANY = '$company',
            CLOSE_TIME = '$close_time',
            DESCRIPTION = '$desc' ";
    // ?? enserio cambiaria la compa;ia ? 
    mysqli_query($general, $sql);
    
    //echo $sql;
}

// le pidio favor que imprimas 
mostrar_html($general, $fallaID);
// vamos a AGREGAR UN TITULO CON LA FECHA DE APERTURA 
// tituloFalla($general, $fallaID);


$general->close();

// FUNCION PARA MOSTRAR EL TITULO DE LA FALLA
function tituloFalla($general, $fallaID) {
    $fallaID = mysqli_real_escape_string($general, $fallaID);

    $url = "http://172.20.97.102:8000/masivas/{$fallaID}?token=masivas2025";
    $response = @file_get_contents($url);

    if ($response === false) {
        echo "<script>document.getElementById('tituloFalla').innerText = 'Error al obtener datos de la API';</script>";
        return;
    }

    $data = json_decode($response, true);
    $ticket = $data['data'][0] ?? null;

    if ($ticket) {
        $desc = $ticket['TITULO'] ?? 'Descripción no disponible';
        $fecha_apertura = $ticket['OPEN_TIME'] ?? 'Fecha no disponible';
        echo "<script>
                document.getElementById('tituloFalla').innerText = " . json_encode("Falla: {$desc} | Apertura: {$fecha_apertura}") . ";
              </script>";
    } else {
        echo "<script>
                document.getElementById('tituloFalla').innerText = 'Falla no encontrada';
              </script>";
    }
}
function mostrar_html($general, $fallaID) {
    $fallaID = mysqli_real_escape_string($general, $fallaID);

    /* ==========================================================
       1. OBTENER OPEN_TIME DE LA FALLA MASIVA DESDE API
    ========================================================== */
    $url = "http://172.20.97.102:8000/masivas/{$fallaID}?token=masivas2025";
    $response = @file_get_contents($url);

    $open_time_masiva = null;
    if ($response !== false) {
        $dataMasiva = json_decode($response, true);
        $ticketMasiva = $dataMasiva['data'][0] ?? null;
        $open_time_masiva = $ticketMasiva['OPEN_TIME'] ?? null;
    }

    /* ==========================================================
       2. CONSULTAR FALLAS ASOCIADAS DESDE BD
    ========================================================== */
    $query = "SELECT id, uniqID, tk_masiva, TK_id, ENLACE, COMPANY, CLOSE_TIME, OPEN_TIME,
                     PE, WAN, VRF, DESCRIPTION, PAIS, fecha_ingreso
              FROM esacalaciones_cnoc.tb_fallas_asociadas
              WHERE tk_masiva = '$fallaID'
              ORDER BY PAIS";

    $result = mysqli_query($general, $query);

    if (mysqli_num_rows($result) === 0) {
        echo "<p class='text-muted'>No hay fallas asociadas registradas.</p>";
        return;
    }

    echo '<div class="card shadow-sm p-4">';
    echo '<h5 class="mb-4">Fallas asociadas</h5>';
    echo '<h1 id="tituloFalla"></h1>';
    echo '<div class="row g-3">';

    /* ==========================================================
       3. RECORRER CADA FALLA ASOCIADA
    ========================================================== */
    while ($row = mysqli_fetch_assoc($result)) {
        $borderClass = match ($row['PAIS']) {
            'GT' => 'border-primary',
            'HN' => 'border-success',
            'NI' => 'border-warning',
            'CR' => 'border-danger',
            default => 'border-secondary',
        };

        $descripcion = $row['DESCRIPTION'] ?: 'Sin descripción';
        $area = $row['COMPANY'] ?: 'Sin empresa';
        $tiempo_cierre = $row['CLOSE_TIME'] ? date('d/m/Y H:i', strtotime($row['CLOSE_TIME'])) : 'Sin cierre';
        $uniq = htmlspecialchars($row['uniqID']);
        $PE = $row['PE'] ?: '';
        $WAN = $row['WAN'] ?: '';
        $VRF = $row['VRF'] ?: '';

        // --- VALIDAR SI ESTÁ DESFASADA ---
        $mensajeDesfase = '';
        if ($open_time_masiva && $row['OPEN_TIME']) {
            $masiva_date = strtotime($open_time_masiva);
            $ticket_date = strtotime($row['OPEN_TIME']);
            $horasresta = $ticket_date - $masiva_date; 
            // Convertir a horas
            $horasresta = round($horasresta / 3600);


            if ($ticket_date < $masiva_date) {
                $mensajeDesfase = "<span class='badge bg-warning text-dark ms-2'>⚠ Falla desfasada</span> $horasresta hrs diferencia";
            }
        }
?>

    <div class="col-12 mb-3">
      <div class="border-start border-4 <?= $borderClass; ?> ps-3 py-3 px-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <p class="mb-1 fw-semibold text-dark">
              Falla #<?= htmlspecialchars($row['TK_id']) ?>: <?= htmlspecialchars($descripcion); ?>
              <?= $mensajeDesfase ?>
            </p>
            <small class="text-muted">
              TK: <?= $row['TK_id']; ?> |
              Enlace: <?= htmlspecialchars($row['ENLACE'] ?: 'Sin enlace'); ?> |
              Área: <?= $area; ?> |
              País: <?= $row['PAIS']; ?> |
              Apertura: <?= date('d/m/Y H:i', strtotime($row['OPEN_TIME'] ?? 'now')); ?> |
              Cierre: <?= $tiempo_cierre; ?>
            </small>
          </div>
          <div class="order-2 p-2">
            <button class="btn btn-outline-info btn-sm" onclick="buscarWan('<?= $uniq ?>')">Buscar WAN</button>
            <button class="btn btn-outline-danger btn-sm" onclick="deletetk('<?= $uniq ?>')">Eliminar</button>
          </div>
        </div>

  <form class="mt-3 row gx-2 gy-1 align-items-end" id="<?= $uniq ?>" onsubmit="guardarInputs(event)">
    <input hidden id="pe_<?= $uniq ?>" value="<?=$uniq?>" name="pe">
    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">PE</span>
        <input type="text" class="form-control" id="pee_<?= $uniq ?>" value="<?=$PE?>" name="pe">
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">VRF</span>
        <input type="text" class="form-control" id="vrf_<?= $uniq ?>" value="<?=$VRF?>" name="vrf">
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">WAN</span>
        <input type="text" class="form-control" id="wan_<?= $uniq ?>" value="<?=$WAN?>" name="wan">
      </div>
    </div>
    <div class="col-12 col-md-3 d-grid">
      <button type="submit" class="btn btn-outline-dark btn-sm">Guardar</button>
    </div>
    <div class="col-12 col-md-3 d-grid" id="btns_<?= $uniq ?>"></div>
  </form>
      </div>
    </div>
<?php
    }

    echo '</div></div>'; // cierre de row y card
}

 
?>