<?php
    include '../includes/BD_con/db_con.php';

    // validar cadenas 
function safe_escape($general, $value) {
    return mysqli_real_escape_string($general, $value ?? '');
}

    $fallaID = $_POST["fallaID"];
    //echo $fallaID;
    // URL de la API
    //$url = 'http://127.0.0.1:8000/masivas/F5875158?token=masivas2025'; // ← cambia esto a la URL real
    $url =('../src/api_data/relacionadas.json');

// Consumir la API con file_get_contents
$response = file_get_contents($url);

// Verificar si hubo respuesta 
// se debera de validar con / la concexion el marlon  
if ($response === false) {
    echo "Error al consumir la API.";
    exit;
}

// Decodificar el JSON
$data = json_decode($response, true);

/* Verificar si la respuesta es válida
if (!isset($data['code']) || $data['code'] !== 200) {
    echo "Error en la respuesta de la API.";
    exit;
}*/

$tickets = $data['data'] ?? [];

// Recorrer los tickets
foreach ($tickets as $ticket) {
    $tk         = $ticket['NUMBER'] ?? '';
    $enlace     = $ticket['TG_ENLACE'] ?? null;
    $company    = $ticket['COMPANY'] ?? null;
    $close_time = $ticket['CLOSE_TIME'] ?? null;
    $desc       = $ticket['BRIEF_DESCRIPTION'] ?? null;
    $pais       = $ticket['PAIS'] ?? null;
    $tk_masiva  = $ticket['FALLA_MASIVA'] ?? null; 

    /* Validar si ya existe
    $e->bind_param("s", $tk);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->store_result();*/

    // Generar uniqid como combinación de FALLA_MASIVA y TG_ENLACE
    $uniqid = $tk_masiva . '' . $tk;

    // Escapar todos los valores
    //$tk         = safe_escape($general, $tk);
    $enlace     = safe_escape($general, $enlace);
    $company    = safe_escape($general, $company);
    $desc       = safe_escape($general, $desc);
    //$close_time = safe_escape($general, $close_time);
    //$pais       = safe_escape($general, $pais);
    //$tk_masiva  = safe_escape($general, $tk_masiva);
    //$uniqid     = safe_escape($general, $uniqid);

    // Ejecutar el insert con ON DUPLICATE KEY UPDATE
    $sql = "
        INSERT INTO tb_fallas_asociadas 
        (uniqid, tk_id, tk_masiva, ENLACE, COMPANY, CLOSE_TIME, DESCRIPTION, PAIS, fecha_ingreso)
        VALUES ('$uniqid', '$tk', '$tk_masiva', '$enlace', '$company', '$close_time', '$desc', '$pais', current_timestamp())
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

$general->close();


function mostrar_html($general, $fallaID) {
    $fallaID = mysqli_real_escape_string($general, $fallaID);

    $query = "SELECT id, uniqID, tk_masiva, TK_id, ENLACE, COMPANY, CLOSE_TIME, PE, WAN, VRF,  DESCRIPTION, PAIS, fecha_ingreso
              FROM pawsoyos_escalaciones_no_eliminar.tb_fallas_asociadas
              WHERE tk_masiva = '$fallaID'
              ORDER by PAIS";

    $result = mysqli_query($general, $query);

    if (mysqli_num_rows($result) === 0) {
      echo "<p class='text-muted'>No hay fallas asociadas registradas.</p>";
      return;
    }

    echo '<div class="card shadow-sm p-4">';
    echo '<h5 class="mb-4">Fallas asociadas</h5>';
    echo '<div class="row g-3">';

    $contador = 1;
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
        $tiempo = date('d/m/Y H:i', strtotime($row['CLOSE_TIME'] ?? 'now'));
        $uniq = htmlspecialchars($row['uniqID']); // identificador único

        //datos PARA EL ARK 
        $PE = $row['PE'] ?: ' ';
        $WAN = $row['WAN'] ?: ' ';
        $VRF = $row['VRF'] ?: ' ';
?>
    <div class="col-12 mb-3">
      <div class="border-start border-4 <?= $borderClass; ?> ps-3 py-3 px-3 bg-white rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <p class="mb-1 fw-semibold text-dark">Falla #<?= htmlspecialchars($row['TK_id']) ?>: <?= htmlspecialchars($descripcion); ?></p>
            <small class="text-muted">
              TK: <?= $row['TK_id']; ?> |
              Enlace: <?= htmlspecialchars($row['ENLACE'] ?: 'Sin enlace'); ?> |
              Área: <?= $area; ?> |
              País: <?= $row['PAIS']; ?> |
              Cierre: <?= $tiempo; ?>
            </small>
          </div>



        </div>

  <form class="mt-3 row gx-2 gy-1 align-items-end" id="<?= $uniq ?>" onsubmit="guardarInputs(event)">
  
    <input hidden id="pe_<?= $uniq ?>" value="<?=$uniq?>" name="pe" >

    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">PE</span>
        <input type="text" class="form-control" id="pe_<?= $uniq ?>" value="<?=$PE?>" name="pe" >
      </div>
    </div>

    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">VRF</span>
        <input type="text" class="form-control" id="vrf_<?= $uniq ?>" value="<?=$VRF?>" name="vrf" >
      </div>
    </div>

    <div class="col-12 col-md-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text">WAN</span>
        <input type="text" class="form-control" id="wan_<?= $uniq ?>" value="<?=$WAN?>" name="wan" >
      </div>
    </div>

  <div class="col-12 col-md-3 d-grid">
    <button type="submit" class="btn btn-outline-dark btn-sm">Guardar</button>
  </div>
</form>

      </div>
    </div>
<?php
        $contador++;
    }

    echo '</div></div>'; // Cierra .row y .card
}


/*/ Extraer los tickets/*
$tickets = $data['data'];
$total = $data['total'];
*/

/* CREATE TABLE TB_fallas_asociadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tk_masiva VARCHAR(20) NOT NULL,
    tk_id     VARCHAR(20) NOT NULL,
    ENLACE VARCHAR(50),
    COMPANY VARCHAR(100),
    CLOSE_TIME DATETIME,
    DESCRIPTION TEXT,
    PAIS VARCHAR(5),
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); */
?>