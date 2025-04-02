<?php
require_once __DIR__ . '/../config/conection.php';
require_once __DIR__ . '/../modals/supply.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method !== 'POST') {
        echo json_encode(["success" => false, "error" => "Método no permitido"]);
        http_response_code(405); // Method Not Allowed
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);

    // Registrar los datos recibidos para depuración
    error_log("Datos recibidos en el backend: " . json_encode($data));

    if (!isset($data['id_supply']) || !isset($data['id_category'])) {
        echo json_encode([
            "success" => false,
            "error" => "Faltan datos requeridos: id_supply o id_category"
        ]);
        http_response_code(400); // Bad Request
        exit();
    }

    $id_supply = intval($data['id_supply']);
    $id_category = intval($data['id_category']);
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        throw new Exception("Error en la conexión a la base de datos: " . $connection->connect_error);
    }

    // Verificar la cantidad disponible en SUPPLY
    $query = "SELECT quantity FROM SUPPLY WHERE id_supply = ?";
    $command = $connection->prepare($query);
    $command->bind_param('i', $id_supply);
    $command->execute();
    $result = $command->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("El suministro especificado no existe.");
    }

    $supply = $result->fetch_assoc();
    $available_quantity = $supply['quantity'];

    if ($available_quantity <= 0) {
        throw new Exception("La cantidad disponible del suministro es insuficiente.");
    }

    $result->free(); // Libera los resultados
    $command->close(); // Cierra el comando

    // Obtener los materiales asociados al suministro
    $materials = Supply::getMaterialsBySupply($id_supply);

    if (empty($materials)) {
        throw new Exception("No se encontraron materiales asociados al suministro.");
    }

    foreach ($materials as $material) {
        $id_material = $material['id_material'];
        $id_category = $material['id_category'];
        $quantity = $available_quantity; // Usar la cantidad del suministro

        // Verificar si el material existe en received_material
        $query = "SELECT id_material FROM received_material WHERE id_material = ?";
        $checkCommand = $connection->prepare($query);
        $checkCommand->bind_param('i', $id_material);
        $checkCommand->execute();
        $result = $checkCommand->get_result();

        if ($result->num_rows === 0) {
            // Insertar el material en received_material si no existe
            $query = "
            INSERT INTO received_material (id_material, description, serial_number, id_supply, id_category, volume, created_at)
            VALUES (?, 'Descripción del material', 'SN-123456', ?, ?, 10, NOW());
            ";
            $insertCommand = $connection->prepare($query);
            $insertCommand->bind_param('iii', $id_material, $id_supply, $id_category);
            if (!$insertCommand->execute()) {
                throw new Exception("Error al insertar el material en received_material: " . $insertCommand->error);
            }
            $insertCommand->close(); // Cierra el comando
        }

        $result->free(); // Libera los resultados
        $checkCommand->close(); // Cierra el comando

        // Registrar el material en el subalmacén
        $query = "
        INSERT INTO sub_warehouse_material (id_sub_warehouse, id_material, quantity)
        SELECT 
            sw.id_sub_warehouse,
            ? AS id_material,
            ? AS quantity
        FROM sub_warehouse sw
        WHERE sw.id_category = ?;
        ";
        $command = $connection->prepare($query);
        $command->bind_param('iii', $id_material, $quantity, $id_category);

        if (!$command->execute()) {
            error_log("Error al mover el material al subalmacén: " . $command->error);
            throw new Exception("Error al mover el material al subalmacén: " . $command->error);
        } else {
            error_log("Material registrado en sub_warehouse_material: id_material=$id_material, quantity=$quantity, id_category=$id_category");
        }
        $command->close();
    }

    echo json_encode(["success" => true, "message" => "Materiales registrados con éxito"]);
    $connection->close(); // Cierra la conexión
} catch (Exception $e) {
    error_log("Error en received_material_api.php: " . $e->getMessage());

    echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
        "trace" => $e->getTraceAsString()
    ]);
    http_response_code(500); // Internal Server Error
}