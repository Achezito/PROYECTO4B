<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/orders.php';

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
        case 'POST': // Agregar nueva orden
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['supply_quantity']) || !isset($data['id_status'])) {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Datos incompletos. Se requiere supply_quantity e id_status."]);
                exit();
            }

            $supply_quantity = intval($data['supply_quantity']);
            $id_status = intval($data['id_status']);
            $confirmation = 3; // Valor predeterminado establecido en el backend

            try {
                $response = Order::insert($id_status, $supply_quantity, $confirmation);
            
                if ($response === true) {
                    http_response_code(201); // Created
                    echo json_encode(["message" => "Orden creada exitosamente."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["error" => "Error al crear la orden."]);
                }
            } catch (Exception $e) {
                error_log("Error interno del servidor: " . $e->getMessage());
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
            }
            break;

        case 'GET': // Obtener todas las órdenes
            try {
                $orders = Order::getAll(); // Llama al método para obtener todas las órdenes
                http_response_code(200); // OK
                echo json_encode($orders, JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                error_log("Error al obtener las órdenes: " . $e->getMessage());
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "Error al obtener las órdenes: " . $e->getMessage()]);
            }
            break;

        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(["error" => "Método no permitido."]);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
}
?>