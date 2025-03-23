<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/material_component.php';

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
        $components = component::getAllComponents();

        if (is_array($components)) {
            echo json_encode($components, true);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (
            isset($data['chipset']) &&
            isset($data['form_factor']) &&
            isset($data['socket_type']) &&
            isset($data['RAM_slots']) &&
            isset($data['max_RAM']) &&
            isset($data['expansion_slots']) &&
            isset($data['capacity']) &&
            isset($data['voltage']) &&
            isset($data['quantity']) &&
            isset($data['audio_channels']) &&
            isset($data['component_type']) &&
            isset($data['id_type'])
        ) {
            $response = component::insert(
            $data['chipset'],
            $data['form_factor'],
            $data['socket_type'],
            intval($data['RAM_slots']),
            floatval($data['max_RAM']),
            intval($data['expansion_slots']),
            floatval($data['capacity']),
            floatval($data['voltage']),
            intval($data['quantity']),
            intval($data['audio_channels']),
            $data['component_type'],
            intval($data['id_type'])
            );
            echo json_encode(["message" => $response]);
        } else {
            echo json_encode(["error" => "Datos incompletos para crear material componente"]);
        }
        break;


    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>