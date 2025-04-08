<?php
include("../../menu.php");
$id = $_GET['id'];

require_once '../../vendor/autoload.php'; // Asegúrate de que Guzzle está instalado

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiId = "67e4a2198ef8a6bad72e7e06";  
$apiKey = "19dd28ac-0bc1-4dc3-bd69-e80ae90eb358";  

$client = new Client();

try {
    // Realizar la solicitud a la API con el ID del producto
    $response = $client->request('GET', "https://api.techspecs.io/v5/products/$id", [
        'headers' => [
            'X-API-ID' => $apiId,
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]);

    // Obtener la respuesta del cuerpo
    $responseBody = $response->getBody();
    $productData = json_decode($responseBody, true);
} catch (RequestException $e) {
    echo "Error en la solicitud: " . $e->getMessage();
    exit;
}

// Extraemos todos los datos de la respuesta
$product = $productData['data']['Product'] ?? [];
$design = $productData['data']['Design'] ?? [];
$display = $productData['data']['Display'] ?? [];
$cpu = $productData['data']['Inside']['CPU'] ?? [];
$ram = $productData['data']['Inside']['RAM'] ?? [];
$gpu = $productData['data']['Inside']['GPU'] ?? [];
$storage = $productData['data']['Inside']['Storage'] ?? [];
$ssd = $productData['data']['Inside']['SSD'] ?? [];
$wireless = $productData['data']['Inside']['Wireless'] ?? [];
$audio = $productData['data']['Inside']['Audio'] ?? [];
$software = $productData['data']['Inside']['Software'] ?? [];
$battery = $productData['data']['Inside']['Battery'] ?? [];
$ports = $productData['data']['Inside']['Ports'] ?? [];
$camera = $productData['data']['Camera'] ?? [];
$keyAspects = $productData['data']['Key Aspects'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Componentes</title>
    <link href="../../css/details.css" rel="stylesheet">
</head>
<body>
    <div class="containdor">
    <br><br><br><br>

        <h1 class="text-center mb-4"><?= $product['Model'] ?? 'Modelo desconocido'; ?></h1>

        <!-- Información del producto -->

        <div class="row row-cols-md-2">
            
            <div class="col">
            <div class="section-title">Información del Producto</div>

                <table class="table">
                    <tr><th>Modelo</th><td><?= $product['Model'] ?? 'Modelo desconocido'; ?></td></tr>
                    <tr><th>Marca</th><td><?= $product['Brand'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Tipo</th><td><?= $design['Body']['Type'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Categoría</th><td><?= $product['Category'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">Diseño</div>

                <table class="table">
                    <tr><th>Color</th><td><?= $design['Body']['Colors'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Peso</th><td><?= $design['Body']['Weight'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Estilo</th><td><?= $design['Body']['Style'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Dimensiones</th><td><?= $design['Body']['Width (Longer Side)'] ?? 'N/A'; ?> x <?= $design['Body']['Height (Shorter Side)'] ?? 'N/A'; ?> mm</td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
            <div class="section-title">Pantalla</div>

                <table class="table">
                    <tr><th>Diagonal</th><td><?= $display['Diagonal'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Resolución</th><td><?= $display['Resolution (H x W)'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Definición</th><td><?= $display['Definition'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Relación de Aspecto</th><td><?= $display['Aspect Ratio'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">CPU</div>

                <table class="table">
                    <tr><th>Generación</th><td><?= $cpu['Generation'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Modelo</th><td><?= $cpu['Model'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Cores</th><td><?= $cpu['Number of Cores'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Velocidad</th><td><?= $cpu['Clock Speed'] ?? 'N/A'; ?> GHz</td></tr>
                    <tr><th>Cache</th><td><?= $cpu['Cache'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
            <div class="section-title">RAM</div>

                <table class="table">
                    <tr><th>Capacidad</th><td><?= $ram['Capacity'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Tipo</th><td><?= $ram['Type'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Capacidad Máxima</th><td><?= $ram['Maximum Capacity'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">GPU</div>

                <table class="table">
                    <tr><th>Modelo</th><td><?= $gpu['Dedicated Card Model'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Memoria</th><td><?= $gpu['Dedicated Card Memory'] ?? 'N/A'; ?> GB</td></tr>
                    <tr><th>Integrada</th><td><?= $gpu['Integrated Card Model'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
            <div class="section-title">Almacenamiento</div>

                <table class="table">
                    <tr><th>Capacidad Total</th><td><?= $storage['Total Capacity'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Tipo</th><td><?= $ssd['Storage Type'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">Conectividad</div>

                <table class="table">
                    <tr><th>Wi-Fi</th><td><?= $wireless['WiFi Standards'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Bluetooth</th><td><?= $wireless['Bluetooth Version'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
            <div class="section-title">Software</div>

                <table class="table">
                    <tr><th>Sistema Operativo</th><td><?= $software['Operating System Version'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">Cámara</div>

                <table class="table">
                    <tr><th>Cámara Frontal</th><td><?= $camera['Front Camera']['Definition'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
            <div class="section-title">Aspectos Clave</div>

                <table class="table">
                    <tr><th>Fecha de lanzamiento</th><td><?= $keyAspects['Release Date'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Colores</th><td><?= $keyAspects['Colors'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Procesador</th><td><?= $keyAspects['Processor'] ?? 'N/A'; ?></td></tr>
                    <tr><th>RAM</th><td><?= $keyAspects['RAM'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
            <div class="col">
            <div class="section-title">Puertos</div>

                <table class="table">
                    <tr><th>Expansión</th><td><?= $ports['Expansion'] ?? 'N/A'; ?></td></tr>
                    <tr><th>USB 3.0 Type-C</th><td><?= $ports['Number of USB 3,0 Type C Ports'] ?? 'N/A'; ?></td></tr>
                    <tr><th>HDMI</th><td><?= $ports['Number of HDMI Ports'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Ethernet</th><td><?= $ports['Number of Ethernet LAN (RJ-45) Ports'] ?? 'N/A'; ?></td></tr>
                </table>
            </div>
        </div>

        <div class="row row-cols-md-2">
            <div class="col">
                <div class="section-title">Batería</div>
                    <table class="table">
                        <tr><th>Capacidad</th><td><?= $battery['Capacity (Watt-hours)'] ?? 'N/A'; ?> Wh</td></tr>
                        <tr><th>Tipo</th><td><?= $battery['Type'] ?? 'N/A'; ?></td></tr>
                        <tr><th>Voltaje</th><td><?= $battery['Capacity (mAh)'] ?? 'N/A'; ?> mAh</td></tr>
                    </table>
            </div>
        </div>
    </div>

    <pre>
        <?php print_r($productData); ?>
    </pre>

</body>
</html>
