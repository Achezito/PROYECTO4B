<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/received_materials.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener warehouse
        $materials = ReceivedMaterial::getAllMaterials();

        if (is_array($materials)) {
            echo json_encode($materials, true);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>

