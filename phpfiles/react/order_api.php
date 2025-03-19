<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/orders.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes metodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener warehouse
        $orders = Order::getOrders();

        if (is_array($orders)) {
            echo json_encode($orders, true);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>

