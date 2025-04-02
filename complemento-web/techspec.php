<?php 
require_once 'vendor/autoload.php'; // Asegúrate de que Guzzle está instalado

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiId = "67e4a2198ef8a6bad72e7e06";  
$apiKey = "19dd28ac-0bc1-4dc3-bd69-e80ae90eb358";  

$client = new Client();

try {
    // Realizar la solicitud a la API de categorías
    $response = $client->request('GET', 'https://api.techspecs.io/v5/categories', [
        'headers' => [
            'X-API-ID' => $apiId,
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]);

    // Obtener la respuesta del cuerpo
    $responseBody = $response->getBody();

    // Mostrar la respuesta en pantalla
    echo $responseBody;

    // Guardar la respuesta en un archivo JSON
    $filePath = 'categories.json';
    file_put_contents($filePath, $responseBody);
    echo "\nLa lista de categorías se ha guardado en '$filePath'.\n";
    
} catch (RequestException $e) {
    // Manejar errores
    echo "Error en la solicitud: " . $e->getMessage();
}
?>
