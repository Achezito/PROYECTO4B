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

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener SubWarehouseMaterial
        $subWarehouseMaterials = SubWarehouseMaterial::getAll();

        if (is_array($subWarehouseMaterials)) {
            echo json_encode($subWarehouseMaterials, true);
        } else {
            echo json_encode(["error" => $subWarehouseMaterials]);
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
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>
