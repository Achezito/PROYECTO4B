<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/received_materials.php';
require_once '../modals/sub_warehouse.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

function sendResponse($success, $data = null, $error = null) {
    echo json_encode([
        "success" => $success,
        "data" => $data,
        "error" => $error
    ]);
}

switch ($method) {
    case 'GET': // Obtener materiales
        $materials = ReceivedMaterial::getAllMaterials();
        sendResponse(true, $materials);
        break;

      
        case 'POST': // Asignar material a sub-almacén
            $data = json_decode(file_get_contents("php://input"), true);
        
            // Depuración: registra los datos recibidos
            error_log("Datos recibidos en la API: " . print_r($data, true));
        
            if (isset($data['id_material']) && isset($data['sub_almacen'])) {
                $id_material = intval($data['id_material']);
                $sub_almacen = intval($data['sub_almacen']);
        
                if (!ReceivedMaterial::exists($id_material)) {
                    http_response_code(404);
                    sendResponse(false, null, "El material no existe");
                    exit();
                }
        
                if (!SubWarehouse::exists($sub_almacen)) {
                    http_response_code(404);
                    sendResponse(false, null, "El subalmacén no existe");
                    exit();
                }
        
                $success = ReceivedMaterial::assignMaterialToSubWarehouse($id_material, $sub_almacen);
        
                if ($success) {
                    sendResponse(true, ["message" => "Material asignado con éxito"]);
                } else {
                    http_response_code(500);
                    sendResponse(false, null, "Error al asignar material");
                }
            } else {
                http_response_code(400);
                sendResponse(false, null, "Datos incompletos");
            }
            break;

    default:
        http_response_code(405);
        sendResponse(false, null, "Método no permitido");
}
?>