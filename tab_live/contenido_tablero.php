<?php
include '../includes/BD_con/db_con.php';

$query = "SELECT * FROM tb_escalaciones_registro WHERE estado = 1";
$result = mysqli_query($general, $query);

while($row = mysqli_fetch_assoc($result)) {
    echo "
    <div class='card mb-3'>
      <div class='card-body'>
        <h5>{$row['titulo']}</h5>
        <p>{$row['nombre']} - {$row['telefono']}</p>
        <p>Tiempo restante: {$row['tiempo']}</p>
      </div>
    </div>";
}
?>
