<?php
    include_once '../../includes/BD_con/db_con.php';
    $condi = $_POST["condi"];
    date_default_timezone_set('America/Guatemala');

switch ($condi) {

// FUNCION PARA IMPRIMIR LAS AREAS DENTRO DE LA tablas 
case 'tb_slct_areas': #areas por pais 
    $pais_id = $_POST["id"];
    #consulta general para traer las areas 
        $consulta = "SELECT id_area, nombre_area, id_pais
        FROM pawsoyos_escalaciones_no_eliminar.tb_area_escalacion 
        WHERE id_pais = $pais_id";
        #se realiza la consulta 
        $resultado = mysqli_query($general, $consulta);

        if (mysqli_num_rows($resultado) > 0) {
        echo "<table border='1' cellpadding='1' class='table table-striped'>
            <thead class='table-dark'>
                <tr>
                    <th>Nombre Área</th>
                    <th>Opcion</th>
                </tr>
            </thead>
            <tbody id='myTable'>";
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                    <td>{$row['nombre_area']}</td>
                    <td> <button type='button' class='btn btn-primary btn-sm px-2 py-1' 
                    value='{$row['nombre_area']}'> Ver </button> </td>
                </tr>";
        }
        echo "  </tbody> </table>";
    } else {
        echo "<p>No se encontraron áreas para este país.</p>";
    }
break;

// FUNCION PARA IMPRIMIR LAS AREAS DE ESCALACION DENTRO DEL SELECT 
case 'tb_areas': #areas por pais 
    $pais_id = $_POST["id"];
    #consulta general para traer las areas 
        $consulta = "SELECT id_area, nombre_area, id_pais
        FROM pawsoyos_escalaciones_no_eliminar.tb_area_escalacion 
        WHERE id_pais = $pais_id";
        #se realiza la consulta 
        $resultado = mysqli_query($general, $consulta);
    if (mysqli_num_rows($resultado) > 0) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo "<option value='{$row['id_area']}'> {$row['nombre_area']}</option>";
            }
    } else {
        echo "<p>No se encontraron áreas para este país.</p>";
    }
break;


// # TABLA CON LOS TIEMPO BIEN CALCULADOS
case 'TB_calculadora':
    // datos desde el AJAX
    $hrActual = $_POST["hrActual"];  $tmpAcumu = $_POST["tmpAcumu"];   $areaSlct = $_POST["areaSlct"];
    $fallaID  = $_POST["fallaID"];   $titulo  = $_POST["titulo"]; 
    // falta titulo, ticket, #AFECTADOS, 
    
    // Consulta Para los contactos  
        $query = "SELECT 
        e.nivel,c.nombre,c.telefono,e.tiempo,e.comentario,tte.tipo  
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id_contacto
        INNER JOIN tb_tipo_escalacion tte ON  e.id_tipo_escalacion = tte.id_tipo_escalacion 
        WHERE e.id_area  = 2 ORDER by e.nivel ";
        #realiza la consulta 
        $resultado = mysqli_query($general, $query);    
    # imprime el encabezado de la tabla
    echo '<table class="table table-striped table-hover table-bordered">
        <thead class="table-dark"> <tr>
        <th>#</th><th>Nombre</th> <th>Medio</th> <th>Tiempo</th>
        <th>Caculadora</th>  <th>Mensaje</th> 
        </tr> </thead> <tbody>';
        
    $contador = 1; // Asegúrate de inicializar el contador
    $hora_acumulada = new DateTime($hrActual); // Objeto DateTime para hora acumulada

    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Alternar clases de color || MODIFICAR PARA VER EL HORARIO 
            $claseFila = ($contador % 2 == 0) ? 'bg-light' : 'bg-white';
        // Badge si hay comentario
            $comentarioBadge = !empty($fila['comentario']) 
            ? "<span class='badge bg-light text-dark'>{$fila['comentario']}</span>" 
            : "";
        // Ícono según tipo
            $iconoTipo = obtenerIconoTipo($fila['tipo']);
        // **SUMATORIA DE TIEMPO**
            $tiempo_sumar = (int)$fila['tiempo']; // convertir a entero
            $hora_acumulada = new DateTime($hrActual);
            $hora_acumulada->modify("+{$tiempo_sumar} hours");
            $hr_suma    = $hora_acumulada->format("H:i:s");

        // Crear objeto de datos
            $datos = [
            'hrActual'   => $hrActual,
            'tmpAcumu'   => $tmpAcumu,
            'areaSlct'   => $areaSlct,
            'nivel'      => $fila['nivel'],
            'nombre'     => $fila['nombre'],
            'telefono'   => $fila['telefono'],
            'tiempo'     => $fila['tiempo'],
            'titulo'     => $titulo,
            'comentario' => $fila['comentario'],
            'fallaID'    => $fallaID,
            'hr_suma'    => $hr_suma
            ];
            $jsonDatos = htmlspecialchars(json_encode($datos), ENT_QUOTES);

    // - YA IMPRIMO CONTENIDO DEL TB -
        echo "<tr class='{$claseFila}'>";
        echo "<td>{$fila['nivel']}</td>";
        echo "<td >{$fila['nombre']} {$comentarioBadge}</td>";
        echo "<td>{$fila['telefono']} {$iconoTipo}</td>";
        echo "<td>{$fila['tiempo']} Horas</td>";
        echo "<td><label class='form-label'>" . $hr_suma . " Hrs</label></td>";
        echo "<td>
                <button type='button' class='btn btn-outline-secondary btn-sm rounded-pill shadow-sm px-3'
                onclick='mnsjEscala({$jsonDatos})' data-bs-toggle='tooltip' title='Genera Mesajes'>
                <i class='fa-regular fa-message'></i> </button> 
                
                <button type='button' class='btn btn-outline-success btn-sm rounded-pill shadow-sm px-3' 
                onclick='tablerosave({$jsonDatos})' data-bs-toggle='tooltip' title='Escalacion'>
                <i class='fa-solid fa-right-long'></i> </button> 
                
                </td>";
        echo "</tr>";
        $contador++;
    }
    echo '</tbody> </table> </div>';

break;

// PARA INSERTAR EN LA TABLA DEL TABLERO
case 'insertb':
        $data_falla = $_POST["datos"];
        $campos = [
        'areaSlct', 'nivel', 'nombre', 'telefono', 'tiempo',
        'hrActual', 'hr_suma', 'tmpAcumu', 'titulo', 'comentario', 'fallaID' ];
    // Extraer y limpiar los valores
        $valores = [];
        foreach ($campos as $key) {
        $valores[$key] = isset($data_falla[$key]) ? addslashes($data_falla[$key]) : ''; }

    // Validación: si hay otro ticket con el mismo fallaID, cambiar su estado a 0
        $fallaID = $valores['fallaID'];
        if (!empty($fallaID)) {
            $queryUpdateEstado = "UPDATE tb_escalaciones_registro 
                                SET estado = 0 
                                WHERE falla_id = '$fallaID' AND estado = 1";
            mysqli_query($general, $queryUpdateEstado);
        }
    // Armar la consulta SQL de inserción (modo string)
        $query_preview = "INSERT INTO tb_escalaciones_registro (
            area_id, nivel, nombre, telefono, tiempo, 
            hora_apertura, hora_sumada, tiempo_acumulado,
            titulo, comentario, falla_id, estado
            ) VALUES (
            {$valores['areaSlct']},
            '{$valores['nivel']}',
            '{$valores['nombre']}',
            '{$valores['telefono']}',
            {$valores['tiempo']},
            '{$valores['hrActual']}',
            '{$valores['hr_suma']}',
            '{$valores['tmpAcumu']}',
            '{$valores['titulo']}',
            '{$valores['comentario']}',
            '{$valores['fallaID']}',
            1 );";

    // HOLAS 
        $ok = mysqli_query($general, $query_preview);
        echo json_encode($ok
            ? ['status'=>'success','message'=>'Escalación registrada']
            : ['status'=>'error','message'=>'Error en INSERT','error'=>mysqli_error($general)]
        );
        
    /*  echo json_encode([
        "status" => "preview",
        "sql" => $query_preview
        ]);
        // $resultado = mysqli_query($general, $query); */
break;

// eliminar un registro relacionado 
case 'deletetk' :
    //include '../includes/BD_con/db_con.php';
    $uniqID = $_POST["tk"];

    // Preparar y ejecutar la consulta segura
    $stmt = $general->prepare("DELETE FROM tb_fallas_asociadas WHERE uniqID = ?");
    $stmt->bind_param("s", $uniqID);

    if ($stmt->execute()) {
        echo "Registros eliminados correctamente.";
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }

    $stmt->close();
    $general->close();
break;

// FUNCION PARA RECARGAR 
case 'recargash':
    $uniqID = $_POST["fallaID"];

    //AGRAGRA LA CONSULTA HACIA LA API E IMPRIMIR // falta  la url 
    //fetch('http://127.0.0.1:8000/masivas/F6144046?token=masivas2025')
    $url =('../../src/api_data/busqueda.json');
    // Consumir la API con file_get_contents
    $response = file_get_contents($url);
    // se debera de validar con / la concexion el marlon  
    if ($response === false) {
        echo json_encode([
        'success' => false,
        'error' => 'Error al conectarse a la URL: ' 
    ]);
    exit; }
    // Decodificar el JSON
    $data = json_decode($response, true);
    $tickets = $data['data'] ?? [];
    // RECORRE LAS 
    foreach ($tickets as $ticket) {
    $tk         = mysqli_real_escape_string($general, $ticket['TK'] ?? '');
    $titulo     = mysqli_real_escape_string($general, $ticket['TITULO'] ?? '');
    $horaSumada = mysqli_real_escape_string($general, $ticket['HH_MM_SS'] ?? '');

    if (!empty($tk)) {
        $query = "UPDATE tb_escalaciones_registro 
                  SET titulo = '$titulo', tiempo_acumulado = '$horaSumada' 
                  WHERE falla_id = '$tk' AND estado = 1";
        mysqli_query($general, $query);
    }
    }
    
    //--- CONSULTA SQL 
    $sql = 'SELECT 
    r.id_registro,  r.falla_id,  r.area_id, r.titulo, r.nivel,
    r.nombre, r.telefono, r.tiempo, r.hora_apertura, r.hora_sumada,
    r.tiempo_acumulado, r.comentario, r.estado,  r.fecha_registro, p.id_pais, p.nombre_pais
    FROM tb_escalaciones_registro r
    INNER JOIN tb_area_escalacion a ON r.area_id = a.id_area
    INNER JOIN tb_pais p ON a.id_pais = p.id_pais
    WHERE r.falla_id = ? AND  r.estado = 1 ;';

    $stmt = $general->prepare($sql);
    $stmt->bind_param("s", $uniqID);

    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $stmt->error
        ]);
    }

break;

// FUNCION PARA CERRAR LAS FALLAS 
case 'cerrarmasiva':
    $uniqID = $_POST["fallaID"];

    if (!empty($uniqID)) {
        $sql = "UPDATE tb_escalaciones_registro SET estado = 0 WHERE falla_id = ? ";
        $stmt = $general->prepare($sql);
        $result = $stmt->execute([$uniqID]);

        if ($result) {
            echo "<div class='alert alert-success'>Falla cerrada exitosamente (ID: $uniqID)</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al cerrar la falla (ID: $uniqID)</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>ID de falla no recibido.</div>";
    }

break;

} // SWITCH DEL CONDI 

    function obtenerIconoTipo($tipo) {
        switch (strtolower($tipo)) {
        case 'llamada':
            return "<i class='fas fa-phone fa-xs text-primary'></i>";
        case 'whatsapp':
            return "<i class='fab fa-whatsapp fa-s text-success'></i>";
        case 'correo':
            return "<i class='fas fa-envelope fa-s text-danger'></i>";
        default:
            return ""; // Sin ícono si no hay coincidencia
    }
}




    /* CONSULTA PARA QUE SE TRAIGAN LA TABLA DE ESCALACION SELECCIONADA 
    SELECT 
    e.nivel,
    c.nombre,
    c.telefono,
    e.tiempo
FROM tb_escalacion e
INNER JOIN tb_contactos c ON e.id_contacto = c.id_contacto
WHERE e.id_tipo_escalacion = 2;
         */


?>