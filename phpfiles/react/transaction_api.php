<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/transaction.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes acciones

switch ($method) {
    case 'GET': // Obtener transacciones
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

        if ($id_sub_warehouse) {
            // Llama al método para obtener las transacciones filtradas por subalmacén
            $transactions = Transaction::getTransactionsBySubWarehouseId($id_sub_warehouse);
        } else {
            // Obtiene todas las transacciones si no se proporciona un ID de subalmacén
            $transactions = Transaction::getAllTransactions();
        }

        if (is_array($transactions)) {
            echo json_encode($transactions, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["error" => "No se encontraron transacciones"]);
            http_response_code(404); // Not Found
        }
        break;

    case 'POST': // Insertar una nueva transacción
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            isset($data['id_material']) &&
            isset($data['id_sub_warehouse']) &&
            isset($data['type']) &&
            isset($data['quantity'])
        ) {
            $id_material = intval($data['id_material']);
            $id_sub_warehouse = intval($data['id_sub_warehouse']);
            $type = $data['type'];
            $quantity = intval($data['quantity']);

            $result = Transaction::insertTransaction($id_material, $id_sub_warehouse, $type, $quantity);

            if ($result === true) {
                echo json_encode(["message" => "Transacción registrada exitosamente."]);
                http_response_code(201); // Created
            } else {
                echo json_encode(["error" => $result]);
                http_response_code(400); // Bad Request
            }
        } else {
            echo json_encode(["error" => "Datos incompletos para registrar la transacción."]);
            http_response_code(400); // Bad Request
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        http_response_code(405); // Method Not Allowed
}
?>