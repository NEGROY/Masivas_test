<?php
$url = "https://api.ejemplo.com/data";

// Inicializar cURL
$ch = curl_init($url);

// Configurar opciones
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // obtener respuesta como string

// Ejecutar petición
$response = curl_exec($ch);

// Cerrar cURL
curl_close($ch);

// Convertir JSON a array PHP
$data = json_decode($response, true);

// Mostrar resultados
print_r($data);
?>


<?php
/* PRUEBA DE API CAMBIAR POR LA DIRECCION DEL JSON 
$url = "https://jsonplaceholder.typicode.com/posts/1";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
print_r($data);

------------------------------------------------
<?php
$url = "https://tuservidor.com/api/datos"; // Cambia esta URL

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// TK objetivo a buscar
$tkBuscado = "F6131074";

foreach ($data as $item) {
    if ($item["tk"] === $tkBuscado) {
        // Obtenemos los valores deseados
        $totalHoras = $item["total_horas"];
        $menosCliente = $item["total_menos_cliente_horas"];

        // Mostramos en consola o para debug
        echo "TK: $tkBuscado\n";
        echo "Total horas: $totalHoras\n";
        echo "Horas sin cliente: $menosCliente\n";

        // OPCIÓN: Guardar en la base de datos
        // guardarDatosEnBD($tkBuscado, $totalHoras, $menosCliente);

        break; // ya encontramos el TK, no es necesario seguir
    }
}
?>
*/

const tk = valdiaFAlla(tkEntrada);
if (!tk) return; // Si la validación falla, se detiene la función

// Validar conexión antes de continuar
fetch('http://172.20.97.102:8000/connection')
  .then(res => res.json())
  .then(conexion => {
    if (conexion.code === 200) {
      // Conexión exitosa, ahora sí llamar a la API principal
      return fetch('./api.php');
    } else {
      throw new Error('Conexión fallida al backend');
    }
  })
  .then(res => res.json())
  .then(data => {
    const encontrado = data.find(item => item.tk === tk);
    if (encontrado) {
      // Lógica si se encuentra el TK
      console.log('TK encontrado:', encontrado);
    } else {
      console.warn('TK no encontrado');
    }
  })
  .catch(error => {
    console.error('Error en conexión o en la API:', error.message);
    alert('No se pudo conectar al servidor. Intente más tarde.');
  });

?>