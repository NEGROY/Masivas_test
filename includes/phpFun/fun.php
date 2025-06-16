<?php


// MUESTRA TODOS LOS paises para ser elegidos para las tablas  
function listarPaises() {
    include_once './includes/BD_con/db_con.php';
    $consulta = "SELECT id_pais, nombre_pais, ext FROM pawsoyos_escalaciones_no_eliminar.tb_pais";
    $resultado = mysqli_query($general, $consulta);

    if (!$resultado) {
        echo "<option>Error en la consulta</option>";
        return;
    }
    echo '<option value="">Selecciona un país</option>';
    while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        echo '<option value="' . $fila['id_pais'] .'">' . $fila['nombre_pais'] . ' ('.$fila['ext'] . ')</option>';
    }
}



?>