<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

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
    case 'GET': // Obtener datos
        $id_warehouse = isset($_GET['id']) ? intval($_GET['id']) : null;
        $id_sub_warehouse = isset($_GET['id_sub_warehouse']) ? intval($_GET['id_sub_warehouse']) : null;

        if ($id_sub_warehouse && $id_warehouse) {
            // Si ambos parámetros están presentes, devuelve un error
            echo json_encode(["error" => "No se pueden usar 'id' y 'id_sub_warehouse' al mismo tiempo"]);
        } elseif ($id_sub_warehouse) {
            // Llama al método para obtener los materiales del subalmacén
            $materials = SubWarehouse::getMaterialsBySubWarehouseId($id_sub_warehouse);
            echo json_encode($materials);
        } elseif ($id_warehouse) {
            // Llama al método para obtener los subalmacenes por almacén
            $SubWarehouse = SubWarehouse::getSubWarehousesByWarehouseId($id_warehouse);
            echo json_encode($SubWarehouse);
        } else {
            // Si no se proporciona ningún parámetro, devuelve todos los subalmacenes
            $SubWarehouse = SubWarehouse::getSubWarehouses();
            echo json_encode($SubWarehouse);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}
?>