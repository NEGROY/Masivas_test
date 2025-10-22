<?php
    include_once '../../includes/BD_con/db_con.php';
    $condi = $_POST["condi"];
    date_default_timezone_set('America/Guatemala');

switch ($condi) {

// FUNCION PARA IMPRIMIR LAS AREAS DENTRO DE LA tablas 
case 'tb_slct_areas': #areas por pais 
    $pais_id = $_POST["id"];
    #consulta general para traer las areas 
        $consulta = "SELECT id as id_area, nombre_area, id_pais
        FROM esacalaciones_cnoc.tb_area_escalacion 
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
        $consulta = "SELECT id as id_area, nombre_area, id_pais
        FROM esacalaciones_cnoc.tb_area_escalacion 
        WHERE id_pais = $pais_id";
        #se realiza la consulta 
        $resultado = mysqli_query($general, $consulta);  
    if (mysqli_num_rows($resultado) > 0) {
        echo "<option > - </option>";
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
    $fallaID  = $_POST["fallaID"];   $titulo  = $_POST["titulo"];      $dashboard  = $_POST["dashboard"];  
    $txtarea = $_POST["txtarea"];    $nivel   = $_POST["nivel"];
    // falta titulo, ticket, #AFECTADOS, 

    /// validamos el nivel 
    $nivel  = validarNivel($nivel);

    // Consulta Para los contactos  
        $query = "SELECT 
        e.nivel,c.nombre,c.telefono,e.tiempo,e.comentario,tte.tipo  
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id
        INNER JOIN tb_tipo_escalacion tte ON  e.id_tipo_escalacion = tte.id 
        WHERE e.id_area  = $areaSlct ORDER by e.nivel ";
        #realiza la consulta 
        $resultado = mysqli_query($general, $query);    
    # imprime el encabezado de la tabla
    echo '<table class="table table-striped table-hover table-bordered">
        <thead class="table-dark"> <tr>
        <th>#</th><th>Nombre</th> <th>Medio</th> <th>Tiempo</th>
        <th>Caculadora</th>';  
    echo '<th>Opciones</th> '; 
    echo '</tr> </thead> <tbody>';
        
    $contador = 1; // Asegúrate de inicializar el contador
    
    // PARA VALIDAR SI ES LA ULTIMA FILA 
    $totalFilas = mysqli_num_rows($resultado);
    $contador   = 0;
    // INICIO DEL CICLO QUE IMPRIME CADA LINEA 
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Alternar clases de color || MODIFICAR PARA VER EL HORARIO 
            $claseFila = ($contador % 2 == 0) ? 'bg-light' : 'bg-white';
        // Badge si hay comentario
            $comentarioBadge = !empty($fila['comentario']) 
            ? "<span class='badge bg-light text-dark'>{$fila['comentario']}</span>" 
            : "";
        // Ícono según tipo
            $iconoTipo = obtenerIconoTipo($fila['tipo']);

            // === VALIDACIÓN DE NIVEL ===
             if ( $fila['nivel'] >= $nivel) {
                // **SUMATORIA DE TIEMPO**
                 $tiempo_sumar = (float)$fila['tiempo']; // convertir a número decimal
                 $horas = floor($tiempo_sumar);  
                 $minutos = round(($tiempo_sumar - $horas) * 60);

                 $hora_acumulada = new DateTime($hrActual); // Objeto DateTime para hora acumulada
                 if ($horas > 0) $hora_acumulada->modify("+{$horas} hours");
                 if ($minutos > 0) $hora_acumulada->modify("+{$minutos} minutes");
                 
                 $hr_suma = $hora_acumulada->format("Y-m-d H:i:s");
                 
             } else {
                     // Si el nivel actual es menor o igual al nivel del registro,
                 // imprimir valores vacíos o en cero sin calcular tiempo
                 $hr_suma = "00:00:00";
                 $tiempo_sumar = 0;
             }

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
        echo "<td><label class='form-label'>" . $hr_suma . " Hrs</label></td> ";
        if ($dashboard == 1) {
            echo "<td> <button type='button' class='btn btn-outline-success btn-sm rounded-pill shadow-sm px-3' 
                onclick='tablerosave({$jsonDatos})' data-bs-toggle='tooltip' title='Escalacion'>
                <i class='fa-solid fa-right-long'></i> </button>  ";
                echo "<button type='button' class='btn btn-outline-secondary btn-sm rounded-pill shadow-sm px-3'
                onclick='mnsjEscala({$jsonDatos})' data-bs-toggle='tooltip' title='Genera Mesajes'>
                <i class='fa-regular fa-message'></i> </button>  ";

                echo " <button type='button' class='btn btn-outline-secondary btn-sm rounded-pill shadow-sm px-3'
                onclick='toggleTable({$jsonDatos})' data-bs-toggle='tooltip' title='+2 horas'>
                <i class='fa-solid fa-square-plus'></i></i> </button> "; 
            }
            else{
                 echo "<td> <button type='button' class='btn btn-outline-secondary btn-sm rounded-pill shadow-sm px-3'
                onclick='plusdos({$jsonDatos}, &quot;$txtarea&quot; )' data-bs-toggle='tooltip' title='Genera Mesajes'>
                <i class='fa-regular fa-message'></i> </button> ";
            }
            echo "</td>";

        $contador++;  

    }
     /* validar si se cuenta con 2 horas mas 
     $horaActual = new DateTime(); $cuenta = 0; // va a contar, cuantas veces se suman 
     // 
    while ($horaActual > $horaLlamada) {
        $horaLlamada->modify('+2 hours');
        $cuenta++; }*/

    echo '</tbody> </table> </div>
    <div id="tableContainer"></div>
    ';

break;

case 'msj_tb':
    // Datos recibidos por POST
    $hrActual  = $_POST["hrActual"];
    $tmpAcumu  = $_POST["tmpAcumu"];
    $areaSlct  = $_POST["areaSlct"];
    $fallaID   = $_POST["fallaID"];
    $titulo    = $_POST["titulo"];

    // Consulta de contactos
    $query = "SELECT 
        e.nivel, c.nombre, c.telefono, e.tiempo, e.comentario, tte.tipo  
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id
        INNER JOIN tb_tipo_escalacion tte ON e.id_tipo_escalacion = tte.id 
        WHERE e.id_area = $areaSlct 
        ORDER BY e.nivel";

    $resultado = mysqli_query($general, $query);

    // Encabezado de tabla en texto plano
    $tablaTxt  = str_repeat("=", 80) . "\n";
    $tablaTxt .= "  ESCALACIÓN: $titulo (Falla ID: $fallaID)\n";
    $tablaTxt .= str_repeat("=", 80) . "\n";
    $tablaTxt .= sprintf("| %-5s | %-15s | %-10s | %-6s | %-20s | %-8s | %-8s |\n",
                        "Nivel", "Nombre", "Teléfono", "Tiempo", "Comentario", "Tipo", "Hr Suma");
    $tablaTxt .= str_repeat("-", 80) . "\n";

    $contador = 1;

    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Calcular hora acumulada
        $tiempo_sumar = (float)$fila['tiempo'];
        $horas = floor($tiempo_sumar);
        $minutos = round(($tiempo_sumar - $horas) * 60);

        $hora_acumulada = new DateTime($hrActual);
        if ($horas > 0) {
            $hora_acumulada->modify("+{$horas} hours");
        }
        if ($minutos > 0) {
            $hora_acumulada->modify("+{$minutos} minutes");
        }

        $hr_suma = $hora_acumulada->format("H:i:s");

        // Agregar fila a la tabla en texto plano
        $tablaTxt .= sprintf("| %-5s | %-15s | %-10s | %-6s | %-20s | %-8s | %-8s |\n",
                            $fila['nivel'],
                            substr($fila['nombre'], 0, 15),
                            $fila['telefono'],
                            $fila['tiempo'],
                            substr($fila['comentario'] ?? '', 0, 20),
                            substr($fila['tipo'], 0, 8),
                            $hr_suma);

        $contador++;
    }

    $tablaTxt .= str_repeat("=", 80) . "\n";

    // Devolver texto plano al frontend
    header('Content-Type: text/plain; charset=utf-8');
    echo $tablaTxt;
    exit;
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
            
            // aqui mismo agregar la funcion para enviar los mensajes,
            // enviarsmj($general, $datos), si existe se cambia a cero e insertar uno nuevo en 1 

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
    $url = "http://172.20.97.102:8503/masivas/{$uniqID}?token=masivas2025";
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
    $OPEN_TIME  = mysqli_real_escape_string($general, $ticket['OPEN_TIME'] ?? '');
    $hora_cierre = !empty($ticket['CLOSE_TIME']) ? mysqli_real_escape_string($general, $ticket['CLOSE_TIME']) : '';


    if (!empty($tk)) {
        $query = "UPDATE tb_escalaciones_registro 
                  SET titulo = '$titulo', tiempo_acumulado = '$horaSumada', CLOSE_TIME = '$hora_cierre',  OPEN_TIME = '$OPEN_TIME'
                  WHERE falla_id = '$tk' AND estado = 1";
        mysqli_query($general, $query);
    }}

    //--- CONSULTA SQL 
    $sql = 'SELECT 
    r.id_registro,  r.falla_id,  r.area_id, r.titulo, r.nivel,
    r.nombre, r.telefono, r.tiempo, r.hora_apertura, r.hora_sumada,
    r.tiempo_acumulado, r.comentario, r.estado,  r.fecha_registro, p.id, p.nombre_pais, r.CLOSE_TIME, r.OPEN_TIME
    FROM tb_escalaciones_registro r
    INNER JOIN tb_area_escalacion a ON r.area_id = a.id
    INNER JOIN tb_pais p ON a.id_pais = p.id
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
        $sql = "UPDATE tb_escalaciones_registro SET estado = 0 WHERE falla_id = ?";
        $stmt = $general->prepare($sql);
        
        $stmt->bind_param("s", $uniqID);
        // Execute without arguments
        $result = $stmt->execute();
        if ($result) {
            echo "<div class='alert alert-success'>Falla cerrada exitosamente (ID: $uniqID )</div>";
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
        case 'teams':
            return "<i class='fa fa-address-card text-primary'></i>";
        default:
            return ""; // Sin ícono si no hay coincidencia
    }
}

// funcion para validar el nivel que sea entero y no negativo 

function validarNivel($nivel) {
    // Verifica si no es un número
    if (!is_numeric($nivel)) {
        return 1;
    }
    // Convierte a entero
    $nivel = (int)$nivel;
    // Verifica que esté en el rango permitido
    if ($nivel < 0 || $nivel >= 10) {
        return 1;
    }
    return $nivel; 
}
 

// funcion para realizar ENVIO DE MENSAJES AL WAHA CONSUMO DE API 


?>