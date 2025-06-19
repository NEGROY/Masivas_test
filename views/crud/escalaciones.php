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
        $query = "SELECT e.nivel, c.nombre, c.telefono, e.tiempo 
        FROM tb_escalacion e
        INNER JOIN tb_contactos c ON e.id_contacto = c.id_contacto
        WHERE e.id_tipo_escalacion = 2";
        #realiza la consulta 
        $resultado = mysqli_query($general, $query);    
    # imprime el encabezado de la tabla
    echo '<table id="TBescala" class="table table-bordered table-striped">
        <thead class="table-dark"> <tr>
        <th>#</th><th>Nombre</th> <th>Teléfono</th> <th>Tiempo</th>
        <th>Caculadora</th> </tr> </thead> <tbody>';
        
    $contador = 1; // Asegúrate de inicializar el contador
    $hora_acumulada = new DateTime($hrActual); // Objeto DateTime para hora acumulada

    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Alternar clases de color
        $claseFila = ($contador % 2 == 0) ? 'table-success' : 'table-danger';

        // tiempo sumarlo a la hora actual acumulada
        $tiempo_sumar = (int)$fila['tiempo']; // convertir a entero
        $hora_acumulada->modify("+{$tiempo_sumar} hours");

        //$hora = ($fila['tiempo']) + $hora;
        echo "<tr class='{$claseFila}'>";
        echo "<td>{$contador}/4</td>";
        echo "<td>{$fila['nombre']}</td>";
        echo "<td>{$fila['telefono']}</td>";
        echo "<td>{$fila['tiempo']} Horas</td>";
        echo "<td><label class='form-label'>" . $hora_acumulada->format("H:i:s") . " Hrs</label></td>";
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
?>