<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse_material.php';

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
    case 'GET': // Obtener materiales por subalmacén
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

        if ($id_sub_warehouse) {
            // Llama al método para obtener los materiales por subalmacén
            $materials = SubWarehouseMaterial::getMaterialsBySubWarehouseId($id_sub_warehouse);

            if (is_array($materials) && !empty($materials)) {
                echo json_encode($materials, JSON_PRETTY_PRINT);
            } elseif (is_array($materials) && empty($materials)) {
                echo json_encode(["message" => "No se encontraron materiales para el subalmacén especificado."]);
                http_response_code(404); // Not Found
            } else {
                echo json_encode(["error" => $materials]);
                http_response_code(500); // Internal Server Error
            }
        } else {
            echo json_encode(["error" => "Falta el parámetro id_sub_warehouse"]);
            http_response_code(400); // Bad Request
        }
        break;

    case 'POST': // Agregar nuevo SubWarehouseMaterial
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_sub_warehouse'], $data['id_material'], $data['quantity'])) {
            $subWarehouseMaterial = new SubWarehouseMaterial(
                $data['id_sub_warehouse'],
                $data['id_material'],
                $data['quantity']
            );

            $result = $subWarehouseMaterial->insert();
            echo json_encode(["message" => $result === true ? "SubWarehouseMaterial added successfully" : $result]);
        } else {
            echo json_encode(["error" => "Missing data"]);
            http_response_code(400); // Bad Request
        }
        break;

    case 'PUT': // Actualizar SubWarehouseMaterial
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_sub_warehouse'], $data['id_material'], $data['quantity'])) {
            $subWarehouseMaterial = new SubWarehouseMaterial(
                $data['id_sub_warehouse'],
                $data['id_material'],
                $data['quantity']
            );

            $result = $subWarehouseMaterial->update();
            echo json_encode(["message" => $result === true ? "SubWarehouseMaterial updated successfully" : $result]);
        } else {
            echo json_encode(["error" => "Missing data"]);
            http_response_code(400); // Bad Request
        }
        break;

    case 'DELETE': // Eliminar SubWarehouseMaterial
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_sub_warehouse'], $data['id_material'])) {
            $subWarehouseMaterial = new SubWarehouseMaterial($data['id_sub_warehouse'], $data['id_material']);
            $result = $subWarehouseMaterial->delete();
            echo json_encode(["message" => $result === true ? "SubWarehouseMaterial deleted successfully" : $result]);
        } else {
            echo json_encode(["error" => "Missing id_sub_warehouse or id_material"]);
            http_response_code(400); // Bad Request
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        http_response_code(405); // Method Not Allowed
}
?>