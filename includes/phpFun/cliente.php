<?php
$tk_buscado = $_GET['tk'] ?? '';
$resultado = null;

if ($tk_buscado !== '') {
    // URL absoluta para consumir API desde cualquier ruta
    $url = './src\api_data\api.php';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    foreach ($data as $item) {
        if ($item['tk'] === $tk_buscado) {
            $resultado = [
                'tk' => $item['tk'],
                'total_menos_cliente_horas' => $item['total_menos_cliente_horas']
            ];
            break;
        }
    }
}
?>