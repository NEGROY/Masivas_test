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
    $hrActual = $_POST["hrActual"];     $tmpAcumu = $_POST["tmpAcumu"];     $areaSlct = $_POST["areaSlct"];
    $pais_id = 2; // es temporal mientras se ingresa el restto a la BD
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

        //OBTENER EL TITULO
        $titulo = "CORTE DE ULTIMA MILLA || ROBO DE CABLE MULTIPAR ZONA 9 || MASIVO_GT";
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
        'comentario' => $fila['comentario']
        ];
        $jsonDatos = htmlspecialchars(json_encode($datos), ENT_QUOTES);

        // **SUMATORIA DE TIEMPO CAMBIARLO**
        $tiempo_sumar = (int)$fila['tiempo']; // convertir a entero
        $hora_acumulada->modify("+{$tiempo_sumar} hours");

        //$hora = ($fila['tiempo']) + $hora;
        echo "<tr class='{$claseFila}'>";
        echo "<td>{$fila['nivel']}</td>";
        echo "<td >{$fila['nombre']} {$comentarioBadge}</td>";
        echo "<td>{$fila['telefono']} {$iconoTipo}</td>";
        echo "<td>{$fila['tiempo']} Horas</td>";
        echo "<td><label class='form-label'>" . $hora_acumulada->format("H:i:s") . " Hrs</label></td>";
        echo "<td>
                <button type='button' class='btn btn-outline-secondary btn-sm rounded-pill shadow-sm px-3'
                    onclick='mnsjEscala({$jsonDatos})'>
                <i class='fa-regular fa-message'></i> </button> </td>";
        echo "</tr>";
        $contador++;
    }
    echo '</tbody> </table> </div>';

break;

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
?>