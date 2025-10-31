<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include '../includes/BD_con/db_con.php';

$query = "SELECT COUNT(*) AS total FROM tb_escalaciones_registro WHERE estado = 1 AND Gestor = 'MASIVAS' ";
$result = mysqli_query($general, $query);
$data = mysqli_fetch_assoc($result);
$maxId = $data['max_id'];

echo "data: {$maxId}\n\n";
flush();
?>
