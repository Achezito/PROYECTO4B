<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/material_hardware.php';

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
        $hardware = hardware::getAllHardware();

        if (is_array($hardware)) {
            echo json_encode($hardware, true);
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
        
            if (
                isset($data['model']) &&
                isset($data['brand']) &&
                isset($data['power_consumption']) &&
                isset($data['frecuency']) &&
                isset($data['vram']) &&
                isset($data['speed']) &&
                isset($data['cores']) &&
                isset($data['threads']) &&
                isset($data['cache_memory']) &&
                isset($data['tipo']) &&
                isset($data['capacity']) &&
                isset($data['read_speed']) &&
                isset($data['write_speed']) &&
                isset($data['id_type'])
            ) {
                $response = hardware::insert(
                    $data['model'],
                    $data['brand'],
                    floatval($data['power_consumption']),
                    floatval($data['frecuency']),
                    floatval($data['vram']),
                    floatval($data['speed']),
                    intval($data['cores']),
                    intval($data['threads']),
                    $data['cache_memory'],
                    $data['tipo'],
                    floatval($data['capacity']),
                    floatval($data['read_speed']),
                    floatval($data['write_speed']),
                    intval($data['id_type'])
                );
                echo json_encode(["message" => $response]);
            } else {
                echo json_encode(["error" => "Datos incompletos para crear material hardware"]);
            }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>