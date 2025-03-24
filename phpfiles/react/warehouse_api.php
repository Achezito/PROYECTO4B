<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/Warehouse.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes acciones

switch ($method) {
    case 'GET': // Obtener warehouse
        $warehouses = Warehouse::getWarehouse();
        if (is_array($warehouses)) {
            echo json_encode($warehouses);
        } else {
            echo json_encode(["error" => $warehouses]); // Si hay un error, lo devuelve en JSON
        }
        break;

    case 'POST': // Insertar un nuevo warehouse
        $data = json_decode(file_get_contents("php://input"), true); // Decodifica el JSON enviado desde el cliente

        if (!isset($data['name'], $data['location'], $data['capacity'])) {
            echo json_encode(["error" => "Missing required fields"]);
            http_response_code(400); // Bad Request
            exit();
        }

        $name = $data['name'];
        $location = $data['location'];
        $capacity = intval($data['capacity']);

        $result = Warehouse::createWarehouse($name, $location, $capacity);

        if ($result === true) {
            echo json_encode(["success" => "Warehouse created successfully"]);
            http_response_code(201); // Created
        } else {
            echo json_encode(["error" => $result]); // Devuelve el error si ocurre
            http_response_code(500); // Internal Server Error
        }
        break;

    case 'PUT': // Actualizar un warehouse existente
        $data = json_decode(file_get_contents("php://input"), true); // Decodifica el JSON enviado desde el cliente

        if (!isset($data['id'], $data['name'], $data['location'], $data['capacity'])) {
            echo json_encode(["error" => "Missing required fields"]);
            http_response_code(400); // Bad Request
            exit();
        }

        $id = intval($data['id']);
        $name = $data['name'];
        $location = $data['location'];
        $capacity = intval($data['capacity']);

        $result = Warehouse::updateWarehouse($id, $name, $location, $capacity);

        if ($result === true) {
            echo json_encode(["success" => "Warehouse updated successfully"]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["error" => $result]); // Devuelve el error si ocurre
            http_response_code(500); // Internal Server Error
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        http_response_code(405); // Method Not Allowed
}
?>