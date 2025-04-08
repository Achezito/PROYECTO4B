<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse_material.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET': // Obtener materiales por subalmacén o todos los materiales
            // Obtener el parámetro id_sub_warehouse de la solicitud GET
            $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

            if ($id_sub_warehouse) {
                // Obtener materiales específicos de un subalmacén
                $materials = SubWarehouseMaterial::getMaterialsBySubWarehouseId($id_sub_warehouse);

                if (is_array($materials) && !empty($materials)) {
                    echo json_encode(["success" => true, "data" => $materials]);
                } else {
                    echo json_encode(["success" => false, "message" => "No se encontraron materiales para el subalmacén especificado."]);
                    http_response_code(404);
                }
            } else {
                // Obtener todos los materiales de todos los subalmacenes
                $allMaterials = SubWarehouseMaterial::getAll();

                if (is_array($allMaterials) && !empty($allMaterials)) {
                    echo json_encode(["success" => true, "data" => $allMaterials]);
                } else {
                    echo json_encode(["success" => false, "message" => "No se encontraron materiales en ningún subalmacén."]);
                    http_response_code(404);
                }
            }
            break;

        case 'POST': // Agregar nuevo SubWarehouseMaterial
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id_sub_warehouse'], $data['id_material'], $data['quantity'])) {
                $id_sub_warehouse = intval($data['id_sub_warehouse']);
                $id_material = intval($data['id_material']);
                $quantity = intval($data['quantity']);

                $subWarehouseMaterial = new SubWarehouseMaterial($id_sub_warehouse, $id_material, $quantity);
                $result = $subWarehouseMaterial->insert();

                if ($result === true) {
                    echo json_encode(["success" => true, "message" => "Material agregado al subalmacén con éxito"]);
                } else {
                    throw new Exception($result);
                }
            } else {
                throw new Exception("Faltan datos requeridos: id_sub_warehouse, id_material, quantity");
            }
            break;

        case 'PUT': // Actualizar SubWarehouseMaterial
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id_sub_warehouse'], $data['id_material'], $data['quantity'])) {
                $id_sub_warehouse = intval($data['id_sub_warehouse']);
                $id_material = intval($data['id_material']);
                $quantity = intval($data['quantity']);

                $subWarehouseMaterial = new SubWarehouseMaterial($id_sub_warehouse, $id_material, $quantity);
                $result = $subWarehouseMaterial->update();

                if ($result === true) {
                    echo json_encode(["success" => true, "message" => "Cantidad actualizada con éxito"]);
                } else {
                    throw new Exception($result);
                }
            } else {
                throw new Exception("Faltan datos requeridos: id_sub_warehouse, id_material, quantity");
            }
            break;

        case 'DELETE': // Eliminar SubWarehouseMaterial
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['id_sub_warehouse'], $data['id_material'])) {
                $subWarehouseMaterial = new SubWarehouseMaterial(
                    intval($data['id_sub_warehouse']),
                    intval($data['id_material'])
                );

                $result = $subWarehouseMaterial->delete();
                if ($result === true) {
                    echo json_encode(["success" => true, "message" => "SubWarehouseMaterial eliminado con éxito"]);
                } else {
                    throw new Exception($result);
                }
            } else {
                throw new Exception("Faltan datos requeridos: id_sub_warehouse, id_material");
            }
            break;

        default:
            throw new Exception("Método de solicitud no válido");
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
    http_response_code(500);
}
?>