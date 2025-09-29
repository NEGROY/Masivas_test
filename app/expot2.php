<?php
$tkid = $_POST["tkid"] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fallas_" . date("Ymd").$tkid. ".xls");

echo "<table border='1'>";
echo "<tr><th>TK_id</th><th>ENLACE</th><th>PE</th><th>WAN</th><th>VRF</th></tr>";

include '../includes/BD_con/db_con.php';


if (!$tkid) {
    http_response_code(400);
    exit("No se recibió el TK ID.");
}

$sql = "
    SELECT TK_id, ENLACE, PE, WAN, VRF
    FROM esacalaciones_cnoc.tb_fallas_asociadas
    WHERE tk_masiva = ? AND PE IS NOT NULL AND WAN IS NOT NULL
";
$stmt = $general->prepare($sql);
$stmt->bind_param("s", $tkid);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['TK_id']}</td>";
    echo "<td>{$row['ENLACE']}</td>";
    echo "<td>{$row['PE']}</td>";
    echo "<td>{$row['WAN']}</td>";
    echo "<td>{$row['VRF']}</td>";
    echo "</tr>";
}
echo "</table>";
exit;
