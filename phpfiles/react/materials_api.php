<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/supply.php';

header("Content-Type: application/json");

try {
    // Verificar si se proporcionó el parámetro id_supply
    if (!isset($_GET['id_supply'])) {
        throw new Exception("El parámetro 'id_supply' es requerido.");
    }

    $id_supply = intval($_GET['id_supply']); // Convertir el parámetro a un entero

    // Obtener los materiales asociados al suministro
    $materials = Supply::getMaterialsBySupply($id_supply);

    // Validar si se encontraron materiales
    if (empty($materials)) {
        echo json_encode(["error" => "No se encontraron materiales para el suministro especificado."]);
        exit();
    }

    // Devolver los materiales en formato JSON
    echo json_encode($materials);
} catch (Exception $e) {
    // Manejar errores y devolver un mensaje de error en formato JSON
    echo json_encode(["error" => $e->getMessage()]);
}