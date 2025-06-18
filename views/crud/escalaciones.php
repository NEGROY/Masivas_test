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
            echo "<option value='{$row['nombre_area']}'>{$row['nombre_area']}</option>";
            }
    } else {
        echo "<p>No se encontraron áreas para este país.</p>";
    }
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