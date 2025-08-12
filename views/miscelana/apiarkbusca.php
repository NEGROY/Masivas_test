<?php
// Asegúrate de que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recibir el valor enviado por POST
    $wanInput = $_POST["wanInput"] ?? '';

    // Validar que el valor no esté vacío
    if (!empty($wanInput)) {

        // Construir la URL dinámica con el valor recibido
        $arkurl = "http://10.20.10.81/get_network_data/?wan=" . urlencode($wanInput);

        // Configurar contexto HTTP si necesitas autenticación básica
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Basic " . base64_encode("frt_ark_interno:Uf!8$6mMG0qg")
            ]
        ];
        $context = stream_context_create($opts);

        // Obtener la respuesta de la API
        $ark_response = file_get_contents($arkurl, false, $context);

        if ($ark_response !== false) {
            $ark_data = json_decode($ark_response, true);

            // Extraer los datos necesarios
            $response = [
                "pe"  => $ark_data['pe']  ?? null,
                "vrf" => $ark_data['vrf'] ?? null,
                "wan" => $ark_data['wan'] ?? null
            ];

            // Devolver los datos en formato JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            // Error al obtener datos de la API
            http_response_code(500);
            echo json_encode(["error" => "No se pudo conectar con la API de ARK."]);
        }
    } else {
        // El campo WAN está vacío
        http_response_code(400);
        echo json_encode(["error" => "Parámetro 'wanInput' vacío."]);
    }
} else {
    // Método incorrecto
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido."]);
}
?>
