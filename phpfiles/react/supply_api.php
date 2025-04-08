<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/supply.php';

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
    case 'GET': // Obtener suministros
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;
        $id_order = isset($_GET['id_order']) ? intval($_GET['id_order']) : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;

        if ($status === 'Pendiente') {
            // Obtener suministros pendientes
            $supplies = Supply::getPendingSupplies();
        } else if ($id_sub_warehouse) {
            // Obtener suministros por subalmacén
            $supplies = Supply::getSuppliesBySubWarehouse($id_sub_warehouse);
        } else if ($id_order) {
            // Obtener suministros por pedido
            $supplies = Supply::getSuppliesByOrder($id_order);
        } else {
            // Obtener todos los suministros
            $supplies = Supply::getSupplies();
        }

        if (is_array($supplies)) {
            echo json_encode($supplies, true);
        } else {
            echo json_encode(["error" => "No se encontraron suministros"]);
        }
        break;

    case 'POST': // Crear un nuevo suministro
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id_order']) || !isset($data['quantity']) || !isset($data['id_supplier']) || !isset($data['id_status'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Datos incompletos. Se requiere id_order, quantity, id_supplier e id_status."]);
            exit();
        }

        $id_order = intval($data['id_order']);
        $quantity = intval($data['quantity']);
        $id_supplier = intval($data['id_supplier']);
        $id_status = intval($data['id_status']);

        try {
            $response = Supply::insert($id_order, $quantity, $id_supplier, $id_status);

            if ($response === true) {
                http_response_code(201); // Created
                echo json_encode(["message" => "Suministro creado exitosamente."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al crear el suministro."]);
            }
        } catch (Exception $e) {
            error_log("Error interno del servidor: " . $e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método de solicitud no válido"]);
}
?>