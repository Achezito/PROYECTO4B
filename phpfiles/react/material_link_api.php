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

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes operaciones

switch ($method) {
    case 'GET': // Obtener registros
        if (isset($_GET['material_type'])) {
            // Obtener materiales según el tipo
            $materialType = $_GET['material_type'];

            switch ($materialType) {
                case 'hardware':
                    $materials = MaterialLink::getHardware();
                    break;
                case 'component':
                    $materials = MaterialLink::getComponents();
                    break;
                case 'physical':
                    $materials = MaterialLink::getPhysical();
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(["error" => "Tipo de material no válido"]);
                    exit();
            }

            echo json_encode($materials);
        } else {
            // Obtener todos los registros de material_link
            $links = MaterialLink::getAll();

            if (is_array($links)) {
                http_response_code(200); // OK
                echo json_encode($links, true);
            } else {
                http_response_code(404); // No encontrado
                echo json_encode(["error" => "No se encontraron registros de material_link"]);
            }
        }
        break;

    case 'POST': // Agregar nuevo registro a material_link
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset($data['id_supply']) &&
            (
                isset($data['id_material_hardware']) ||
                isset($data['id_material_component']) ||
                isset($data['id_material_physical'])
            )
        ) {
            $response = MaterialLink::insert(
                intval($data['id_supply']),
                isset($data['id_material_hardware']) ? intval($data['id_material_hardware']) : null,
                isset($data['id_material_component']) ? intval($data['id_material_component']) : null,
                isset($data['id_material_physical']) ? intval($data['id_material_physical']) : null
            );

            if ($response) {
                http_response_code(201); // Creado
                echo json_encode(["message" => "Registro creado con éxito"]);
            } else {
                http_response_code(500); // Error interno
                echo json_encode(["error" => "Error al crear el registro"]);
            }
        } else {
            http_response_code(400); // Solicitud incorrecta
            echo json_encode(["error" => "Datos incompletos para crear material_link"]);
        }
        break;

    default:
        http_response_code(405); // Método no permitido
        echo json_encode(["error" => "Método no permitido"]);
}
?>