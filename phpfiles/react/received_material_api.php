<?php
// filepath: c:\xampp\htdocs\PROYECTO4B-1\phpfiles\react\received_material_api.php

require_once __DIR__ . '/../config/conection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST': // Registrar material recibido
            $data = json_decode(file_get_contents("php://input"), true);

            // Verificar si es un registro de material recibido
            if (!isset($data['action']) || $data['action'] !== 'register') {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Acción no válida o no especificada."]);
                exit();
            }

            // Validar datos obligatorios
            if (!isset($data['id_supply']) || !isset($data['description']) || !isset($data['serial_number']) || !isset($data['id_category']) || !isset($data['volume'])) {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Datos incompletos para el registro."]);
                exit();
            }

            // Insertar material recibido
            $connection = Conexion::get_connection();
            if ($connection->connect_error) {
                throw new Exception("Error en la conexión a la base de datos: " . $connection->connect_error);
            }

            $query = "INSERT INTO RECEIVED_MATERIAL (id_supply, description, serial_number, id_category, volume) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $connection->error);
            }

            $stmt->bind_param(
                "issid",
                $data['id_supply'],
                $data['description'],
                $data['serial_number'],
                $data['id_category'],
                $data['volume']
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            http_response_code(201); // Created
            echo json_encode(["message" => "Material recibido registrado exitosamente."]);
            break;

        case 'GET': // Listar materiales recibidos
            $query = "SELECT * FROM RECEIVED_MATERIAL";
            $connection = Conexion::get_connection();
            $result = $connection->query($query);

            if ($result->num_rows > 0) {
                $materials = [];
                while ($row = $result->fetch_assoc()) {
                    $materials[] = $row;
                }
                http_response_code(200); // OK
                echo json_encode($materials);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["error" => "No se encontraron materiales recibidos."]);
            }
            break;

        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(["error" => "Método no permitido."]);
    }
} catch (Exception $e) {
    error_log("Error interno del servidor: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error interno del servidor: " . $e->getMessage()]);
}
?>