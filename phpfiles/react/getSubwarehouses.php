<?php
// filepath: c:\xampp\htdocs\PROYECTO4B-1\phpfiles\react\getSubwarehouses.php

require_once __DIR__ . '/../config/conection.php';
require_once '../modals/sub_warehouse.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

try {
    // Conexión a la base de datos
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        throw new Exception("Error en la conexión a la base de datos: " . $connection->connect_error);
    }

    // Consulta para obtener todos los subalmacenes
    $query = "SELECT id_sub_warehouse, location, capacity, id_warehouse, id_category, created_at, updated_at FROM SUB_WAREHOUSE";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $subWarehouses = [];
        while ($row = $result->fetch_assoc()) {
            $subWarehouses[] = $row;
        }
        http_response_code(200); // OK
        echo json_encode($subWarehouses);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "No se encontraron subalmacenes."]);
    }
} catch (Exception $e) {
    error_log("Error interno del servidor: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
}
?>