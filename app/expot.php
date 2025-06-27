<?php
include '../includes/BD_con/db_con.php';

$tkid = $_POST["tkid"] ?? '';
if (!$tkid) {
    http_response_code(400);
    exit("No se recibió el TK ID.");
}

// Encabezados para forzar descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="fallas_asociadas_' . $tkid . '.csv"');

// Salida directa
$output = fopen('php://output', 'w');
fputcsv($output, ['TK_id', 'ENLACE', 'PE', 'WAN', 'VRF']);

// Consulta con mysqli
$sql = "
    SELECT TK_id, ENLACE, PE, WAN, VRF
    FROM pawsoyos_escalaciones_no_eliminar.tb_fallas_asociadas
    WHERE tk_masiva = ? AND PE IS NOT NULL AND WAN IS NOT NULL
";

// Preparar
$stmt = $general->prepare($sql);
$stmt->bind_param("s", $tkid);
$stmt->execute();
$result = $stmt->get_result(); // obtener resultados como mysqli_result

// Escribir cada fila como CSV
while ($fila = $result->fetch_assoc()) {
    fputcsv($output, $fila);
}

fclose($output);
exit;
