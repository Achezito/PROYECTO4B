<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/material_link.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para llamar diferentes datos dependiendo del método

switch ($method) {
    case 'GET': // Obtener todos los registros de material_link
        $links = MaterialLink::getAll();

        if (is_array($links)) {
            echo json_encode($links, true);
        } else {
            echo json_encode(["error" => "No se encontraron registros de material_link"]);
        }
        break;

    case 'POST': // Agregar nuevo registro a material_link
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset($data['id_material']) &&
            isset($data['id_material_hardware']) &&
            isset($data['id_material_component']) &&
            isset($data['id_material_physical'])
        ) {
            $response = MaterialLink::insert(
                intval($data['id_material']),
                intval($data['id_material_hardware']),
                intval($data['id_material_component']),
                intval($data['id_material_physical'])
            );

            echo json_encode(["message" => $response]);
        } else {
            echo json_encode(["error" => "Datos incompletos para crear material_link"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}
?>
