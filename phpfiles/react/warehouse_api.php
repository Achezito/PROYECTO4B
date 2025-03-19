<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/Warehouse.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

/* ------ codigo de react para llamar los datos --------

    const getWarehouses = async () => {
    try {
        const response = await fetch(API_URL, {
            method: 'GET'
        });
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error obteniendo almacenes:', error);
    }
};
    ------ codigo de react para llamar los datos -------- */

switch ($method) {
    case 'GET': // Obtener warehouse
        $warehouses = Warehouse::getWarehouse();
        if (is_array($warehouses)) {
            echo json_encode($warehouses);
        } else {
            echo json_encode(["error" => $warehouses]); // Si hay un error, lo devuelve en JSON
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);

}
?>
