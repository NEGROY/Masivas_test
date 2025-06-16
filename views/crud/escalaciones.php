<?php
    include '../include/BD_con/bd_con.php';
    $condi = $_POST["condi"];

    # switch ($condi) {


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