<?php
// DESPLIEGE DE PRUEBAS
// define("urlsite", "http://localhost/Masivas_test/");
// DESPLIEGE DE PRODUCCION
 // define("urlsite", "http://172.20.97.102/Masivas_test/"); 

 
// ===========================
// Cargar variables de entorno
// ===========================
$envPath = __DIR__ . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios o líneas vacías
        if (strpos(trim($line), '#') === 0 || trim($line) === '') continue;

        // Separar clave=valor
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

 define("urlsite", "http://172.20.97.102/Masivas_test/");
// ===========================
// Definir constantes globales
// ===========================
// define("urlsite", getenv('APP_URL')); // APP_URL
//define("urlsite", __DIR__); // Ruta absoluta del proyecto BASE_PATH
define("APP_ENV", getenv('APP_ENV'));
