<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes métodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para llamar diferentes datos dependiendo del método

switch ($method) {
    case 'GET': // Obtener subalmacenes
        // Verifica si se pasa un parámetro `id`
        $id_warehouse = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($id_warehouse) {
            // Llama al método para obtener subalmacenes filtrados por `id_warehouse`
            $SubWarehouse = SubWarehouse::getSubWarehousesByWarehouseId($id_warehouse);
        } else {
            // Si no se pasa `id`, devuelve todos los subalmacenes
            $SubWarehouse = SubWarehouse::getSubWarehouses();
        }

        if (is_array($SubWarehouse)) {
            echo json_encode($SubWarehouse, true);
        } else {
            echo json_encode(["error" => "No se encontraron subalmacenes"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>