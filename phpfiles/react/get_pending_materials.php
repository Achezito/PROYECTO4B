<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../config/conection.php';

try {
    $conn = Conexion::get_connection();
    
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos');
    }

    $query = "SELECT 
                rm.id_material,
                s.id_supply,
                COALESCE(mh.model, mc.model, mp.model) AS model,
                COALESCE(mh.brand, mc.brand, mp.brand) AS brand,
                c.name AS category,
                mt.name AS material_type,
                s.id_status
              FROM RECEIVED_MATERIAL rm
              JOIN SUPPLY s ON rm.id_supply = s.id_supply
              LEFT JOIN MATERIAL_HARDWARE mh ON rm.id_material_hardware = mh.id_material
              LEFT JOIN MATERIAL_COMPONENT mc ON rm.id_material_component = mc.id_material
              LEFT JOIN MATERIAL_PHYSICAL mp ON rm.id_material_physical = mp.id_material
              JOIN CATEGORY c ON rm.id_category = c.id_category
              JOIN MATERIAL_TYPE mt ON (
                  mh.id_type = mt.id_type OR 
                  mc.id_type = mt.id_type OR 
                  mp.id_type = mt.id_type
              )
              WHERE s.id_status = 1";

    $stmt = $conn->prepare($query);
    
    if (!$stmt || !$stmt->execute()) {
        throw new Exception('Error en la consulta: ' . ($conn->error ?? ''));
    }

    $result = $stmt->get_result();
    $materials = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $materials ?: []
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'data' => []
    ]);
}
?>