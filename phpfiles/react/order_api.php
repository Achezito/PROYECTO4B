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

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener órdenes
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

        if ($id_sub_warehouse) {
            $orders = Order::getOrdersBySubWarehouseId($id_sub_warehouse);
        } else {
            $orders = Order::getOrders();
        }

        if (is_array($orders)) {
            echo json_encode($orders, true);
        } else {
            echo json_encode(["error" => "No se encontraron órdenes"]);
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
    
                echo json_encode(["message" => $response]);
            } else {
                echo json_encode(["error" => "Datos incompletos para crear la orden"]);
            }
        break;
    

    default:
        echo json_encode(["error" => "Método no permitido"]);
}
?>