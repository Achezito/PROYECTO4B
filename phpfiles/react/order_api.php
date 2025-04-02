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

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes acciones

try {
    switch ($method) {
        case 'GET': // Obtener órdenes
            $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;
            $confirmation = isset($_GET['confirmation']) ? intval($_GET['confirmation']) : null;
            $status = isset($_GET['status']) ? intval($_GET['status']) : null;
            $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
            $id_order = isset($_GET['id_order']) ? $_GET['id_order'] : null;

            try {
                if ($id_sub_warehouse) {
                    $orders = Order::getOrdersBySubWarehouseId($id_sub_warehouse);
                } else if ($confirmation) {
                    $orders = Order::getOrdersByConfirmation($confirmation);
                } else if ($status) {
                    $orders = Order::getOrdersByStatus($status);
                } else if ($fecha) {
                    $orders = Order::getOrdersByDateOrder($fecha);
                } else if ($id_order) {
                    $orders = Order::getOrderById($id_order);
                } else {
                    $orders = Order::getOrders();
                }

                if (is_array($orders) && !empty($orders)) {
                    echo json_encode($orders, JSON_PRETTY_PRINT);
                } else if (is_array($orders) && empty($orders)) {
                    echo json_encode(["message" => "No se encontraron órdenes"]);
                } else {
                    echo json_encode(["error" => "Error al obtener las órdenes."]);
                }
                
            } catch (\Throwable $th) {
                echo "No se pudieron Encontrar Ordenes";
            }

            break;

        case 'POST': // Agregar nueva orden
            $id_order_confirmation = isset($_POST['Id_order_confirmation']) ? $_POST['Id_order_confirmation'] : null;
            $confirmation = isset($_POST['confirmation']) ? $_POST['confirmation'] : null;
            $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

            if ($quantity) {
                $response = Order::insert(0);
            } if ($id_order_confirmation && $confirmation) {
                $response = Order::updateOrderConfirmation($id_order_confirmation, $confirmation);
            }

                if ($response === true) {
                    echo json_encode(["message" => "Orden creada exitosamente."]);
                    http_response_code(201); // Created
                } else {
                    echo json_encode(["error" => $response]);
                    http_response_code(500); // Internal Server Error
                }

        default:
            echo json_encode(["error" => "Método no permitido."]);
            http_response_code(405); // Method Not Allowed
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>