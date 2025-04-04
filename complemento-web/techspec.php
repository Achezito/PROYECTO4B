<?php
include("menu.php");

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiId = "67e4a2198ef8a6bad72e7e06";
$apiKey = "19dd28ac-0bc1-4dc3-bd69-e80ae90eb358";
$client = new Client();

$productos = [];

try {
    $url = "https://api.techspecs.io/v5/products/search?category=Laptops&keepCasing=true&page=0&size=20";
    
    $response = $client->request('GET', $url, [
        'headers' => [
            'X-API-ID' => $apiId,
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]);
    
    $data = json_decode($response->getBody(), true);
    $productos = $data['data'] ?? [];
} catch (RequestException $e) {
    echo "Error al obtener productos: " . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($productos, JSON_PRETTY_PRINT);
?>
