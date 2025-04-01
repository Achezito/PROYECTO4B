<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/conection.php';

$data = json_decode(file_get_contents('php://input'), true);

// Verificar si ya existe el material en el subalmacén
$checkQuery = "SELECT * FROM Inventario_sub_warehouse_material 
               WHERE IL_sub_warehouse = ? AND IL_material = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $data['IL_sub_warehouse'], $data['IL_material']);
$checkStmt->execute();
$exists = $checkStmt->get_result()->num_rows > 0;

if ($exists) {
    // Actualizar cantidad
    $query = "UPDATE Inventario_sub_warehouse_material 
              SET quantity = quantity + ? 
              WHERE IL_sub_warehouse = ? AND IL_material = ?";
} else {
    // Insertar nuevo registro
    $query = "INSERT INTO Inventario_sub_warehouse_material 
              (IL_sub_warehouse, IL_material, quantity) 
              VALUES (?, ?, ?)";
}

$stmt = $conn->prepare($query);
if ($exists) {
    $stmt->bind_param("iii", 
        $data['quantity'],
        $data['IL_sub_warehouse'],
        $data['IL_material']
    );
} else {
    $stmt->bind_param("iii", 
        $data['IL_sub_warehouse'],
        $data['IL_material'],
        $data['quantity']
    );
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>