<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/orders.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes acciones

try {
    switch ($method) {
        case 'GET': // Obtener órdenes
            $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

            if ($id_sub_warehouse) {
                $orders = Order::getOrdersBySubWarehouseId($id_sub_warehouse);
            } else {
                $orders = Order::getOrders();
            }

            if (is_array($orders) && !empty($orders)) {
                echo json_encode($orders, JSON_PRETTY_PRINT);
            } elseif (is_array($orders) && empty($orders)) {
                echo json_encode(["message" => "No se encontraron órdenes para el subalmacén especificado."]);
                http_response_code(404); // Not Found
            } else {
                echo json_encode(["error" => "Error al obtener las órdenes."]);
                http_response_code(500); // Internal Server Error
            }
            break;

        case 'POST': // Agregar nueva orden
            $data = json_decode(file_get_contents("php://input"), true);

            if (
                isset($data['order_date']) &&
                isset($data['id_status']) &&
                isset($data['id_supply']) &&
                isset($data['quantity'])
            ) {
                $response = Order::insert(
                    $data['order_date'],
                    intval($data['id_status']),
                    intval($data['id_supply']),
                    intval($data['quantity'])
                );

                if ($response === true) {
                    echo json_encode(["message" => "Orden creada exitosamente."]);
                    http_response_code(201); // Created
                } else {
                    echo json_encode(["error" => $response]);
                    http_response_code(500); // Internal Server Error
                }
            } else {
                echo json_encode(["error" => "Datos incompletos para crear la orden."]);
                http_response_code(400); // Bad Request
            }
            break;

        default:
            echo json_encode(["error" => "Método no permitido."]);
            http_response_code(405); // Method Not Allowed
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>