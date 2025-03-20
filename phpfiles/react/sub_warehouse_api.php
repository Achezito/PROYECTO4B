<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes métodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para llamar diferentes datos dependiendo del método

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