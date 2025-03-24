<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

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
    case 'GET': // Obtener datos
        $id_warehouse = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($id_warehouse) {
            // Llama al método para obtener los subalmacenes por almacén
            $SubWarehouse = SubWarehouse::getSubWarehousesByWarehouseId($id_warehouse);
            echo json_encode($SubWarehouse);
        } else {
            // Si no se proporciona ningún parámetro, devuelve todos los subalmacenes
            $SubWarehouse = SubWarehouse::getSubWarehouses();
            echo json_encode($SubWarehouse);
        }
        break;

    case 'POST': // Añadir un nuevo subalmacén
        $data = json_decode(file_get_contents("php://input"), true); // Decodifica el JSON enviado desde el cliente

        if (!isset($data['warehouse_id'], $data['location'], $data['capacity'], $data['id_category'])) {
            echo json_encode(["error" => "Faltan campos requeridos"]);
            http_response_code(400); // Bad Request
            exit();
        }

        $warehouse_id = intval($data['warehouse_id']);
        $location = $data['location'];
        $capacity = intval($data['capacity']);
        $id_category = intval($data['id_category']);

        $result = SubWarehouse::createSubWarehouse($location, $capacity, $warehouse_id, $id_category);

        if ($result === true) {
            echo json_encode(["success" => "Subalmacén añadido correctamente"]);
            http_response_code(201); // Created
        } else {
            echo json_encode(["error" => $result]); // Devuelve el error si ocurre
            http_response_code(500); // Internal Server Error
        }
        break;

    case 'PUT': // Actualizar un subalmacén existente
        $data = json_decode(file_get_contents("php://input"), true); // Decodifica el JSON enviado desde el cliente

        if (!isset($data['id_sub_warehouse'], $data['location'], $data['capacity'], $data['id_category'])) {
            echo json_encode(["error" => "Faltan campos requeridos"]);
            http_response_code(400); // Bad Request
            exit();
        }

        $id_sub_warehouse = intval($data['id_sub_warehouse']);
        $location = $data['location'];
        $capacity = intval($data['capacity']);
        $id_category = intval($data['id_category']);

        $result = SubWarehouse::updateSubWarehouse($id_sub_warehouse, $location, $capacity, $id_category);

        if ($result === true) {
            echo json_encode(["success" => "Subalmacén actualizado correctamente"]);
            http_response_code(200); // OK
        } else {
            echo json_encode(["error" => $result]); // Devuelve el error si ocurre
            http_response_code(500); // Internal Server Error
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        http_response_code(405); // Method Not Allowed
}
?>