<?php
// filepath: c:\xampp\htdocs\PROYECTO4B-1\phpfiles\react\sub_warehouse_api.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Determina el método HTTP utilizado

try {
    switch ($method) {
        case 'GET': // Obtener subalmacenes
            handleGetRequest();
            break;

        case 'POST': // Crear un nuevo subalmacén
            handlePostRequest();
            break;

        case 'PUT': // Actualizar un subalmacén existente
            handlePutRequest();
            break;

        default: // Método no permitido
            http_response_code(405); // Method Not Allowed
            echo json_encode(["error" => "Método no permitido"]);
    }
} catch (Exception $e) {
    error_log("Error interno del servidor: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
}

// Maneja solicitudes GET
function handleGetRequest() {
    if (isset($_GET['distribution']) && $_GET['distribution'] === 'true') {
        // Obtener distribución de materiales
        $distribution = SubWarehouse::getMaterialDistribution();
        if (!$distribution) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "No se pudo obtener la distribución de materiales"]);
            return;
        }
        echo json_encode($distribution);
    } else {
        // Obtener subalmacenes por ID de almacén o todos los subalmacenes
        $id_warehouse = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($id_warehouse) {
            $subWarehouses = SubWarehouse::getSubWarehousesByWarehouseId($id_warehouse);
            if (!$subWarehouses) {
                http_response_code(404); // Not Found
                echo json_encode(["rror" => "No se pudieron obtener los subalmacenes para el ID de almacén: $id_warehouse"]);
                return;
            }
            echo json_encode($subWarehouses);
        } else {
            $subWarehouses = SubWarehouse::getSubWarehouses();
            if (empty($subWarehouses)) {
                http_response_code(404); // Not Found
                echo json_encode(["error" => "No se encontraron subalmacenes."]);
                return;
            }
            echo json_encode($subWarehouses);
        }
    }
}

// Maneja solicitudes POST
function handlePostRequest() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['warehouse_id'], $data['location'], $data['capacity'], $data['id_category'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Faltan campos requeridos"]);
        return;
    }

    $warehouse_id = intval($data['warehouse_id']);
    $location = $data['location'];
    $capacity = intval($data['capacity']);
    $id_category = intval($data['id_category']);

    $result = SubWarehouse::createSubWarehouse($location, $capacity, $warehouse_id, $id_category);

    if ($result === true) {
        http_response_code(201); // Created
        echo json_encode(["success" => "Subalmacén añadido correctamente"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => $result]);
    }
}

// Maneja solicitudes PUT
function handlePutRequest() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id_sub_warehouse'], $data['location'], $data['capacity'], $data['id_category'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Faltan campos requeridos"]);
        return;
    }

    $id_sub_warehouse = intval($data['id_sub_warehouse']);
    $location = $data['location'];
    $capacity = intval($data['capacity']);
    $id_category = intval($data['id_category']);

    $result = SubWarehouse::updateSubWarehouse($id_sub_warehouse, $location, $capacity, $id_category);

    if ($result === true) {
        http_response_code(200); // OK
        echo json_encode(["success" => "Subalmacén actualizado correctamente"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => $result]);
    }
}
?>