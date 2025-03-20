<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/orders.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes métodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para llamar diferentes datos dependiendo del método

switch ($method) {
    case 'GET': // Obtener órdenes
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

        if ($id_sub_warehouse) {
            // Llama al método para obtener las órdenes filtradas por subalmacén
            $orders = Order::getOrdersBySubWarehouseId($id_sub_warehouse);
        } else {
            // Obtiene todas las órdenes si no se proporciona un ID de subalmacén
            $orders = Order::getOrders();
        }

        if (is_array($orders)) {
            echo json_encode($orders, true);
        } else {
            echo json_encode(["error" => "No se encontraron órdenes"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}
?>