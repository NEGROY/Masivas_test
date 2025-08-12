<?php
    include '../../includes/BD_con/db_con.php';

    $datos = $_POST["datos"];
    $tkid = $_POST["tkid"]; 

    // VALIDAR ESTRUCTURA DE LOS DATOS
    $regexIP = '/^(?:\d{1,3}\.){3}\d{1,3}$/';

    // Limpiar espacios
    $pe = trim($datos["pe"]);
    $wan = trim($datos["wan"]);
    $vrf = trim($datos["vrf"]);
    // VALIDA
    //if (!preg_match($regexIP, $pe) || !preg_match($regexIP, $wan)) 
    if ( (!empty($pe) && !preg_match($regexIP, $pe)) ||
         (!empty($wan) && !preg_match($regexIP, $wan)) ){
        echo "La IP del PE o la IP WAN no es válida.";
        return;
    }

    $sql = "UPDATE tb_fallas_asociadas
        SET PE = ?, WAN = ?, VRF = ?
        WHERE uniqID = ?";
    
    //REALIZA UPDATE 
    $stmt = $general->prepare($sql);
    $stmt->bind_param("ssss", $pe, $wan, $vrf, $tkid);

    if ($stmt->execute()) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

$stmt->close();

?>