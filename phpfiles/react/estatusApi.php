<?php
require_once '../config/conection.php';

header("Content-Type: application/json");

$connection = Conexion::get_connection();

if ($connection === null) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error al conectar a la base de datos."]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $query = "SELECT id_status, description FROM STATUS"; // Asegúrate de que las columnas sean correctas
        $result = $connection->query($query);

        if (!$result) {
            throw new Exception("Error en la consulta SQL: " . $connection->error);
        }

        $statuses = [];
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row;
        }

        echo json_encode($statuses, JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error al obtener los estados: " . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido"]);
}
?>