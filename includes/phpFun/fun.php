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

// prueba de mi funcion para que este sumando las horas y las imprima 23hrs formato 
function calcu_hrs() {
    $hora_actual = "21:00:00"; // hora base
    $horas_a_sumar = [2, 3, 5, 8];

    echo "Hora actual: $hora_actual<br><br>";

    foreach ($horas_a_sumar as $hrs) {
        $dt = new DateTime($hora_actual);
        $dt->modify("+{$hrs} hours");
        echo "Hora +$hrs hrs: " . $dt->format("H:i:s") . "<br>";
    }

}


?>