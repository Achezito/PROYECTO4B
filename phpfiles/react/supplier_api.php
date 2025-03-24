<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/supplier.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // Se lee el método para manejar diferentes operaciones

switch ($method) {
    case 'GET': // Obtener proveedores
        if (isset($_GET['id_sub_warehouse'])) {
            // Obtener proveedores relacionados con un subalmacén específico
            $id_sub_warehouse = intval($_GET['id_sub_warehouse']);
            $suppliers = Supplier::getSuppliersBySubWarehouse($id_sub_warehouse);
        } else {
            // Obtener todos los proveedores
            $suppliers = Supplier::getSuppliers();
        }

        if (is_array($suppliers)) {
            echo json_encode($suppliers, true);
        } else {
            echo json_encode(["error" => "No se encontraron proveedores"]);
        }
        break;

    case 'POST': // Crear un nuevo proveedor
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name'], $data['contact_info'], $data['address'])) {
            $name = $data['name'];
            $contact_info = $data['contact_info'];
            $address = $data['address'];

          $result = Supplier::createSupplier($name, $contact_info, $address);

            if ($result === true) {
                echo json_encode(["success" => "Proveedor creado exitosamente"]);
            } else {
                echo json_encode(["error" => $result]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'PUT': // Actualizar un proveedor existente
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id_supplier'], $data['name'], $data['contact_info'], $data['address'])) {
            $id_supplier = intval($data['id_supplier']);
            $name = $data['name'];
            $contact_info = $data['contact_info'];
            $address = $data['address'];

            $result = Supplier::updateSupplier($id_supplier, $name, $contact_info, $address);

            if ($result === true) {
                echo json_encode(["success" => "Proveedor actualizado exitosamente"]);
            } else {
                echo json_encode(["error" => $result]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'DELETE': // Eliminar un proveedor
        if (isset($_GET['id_supplier'])) {
            $id_supplier = intval($_GET['id_supplier']);
           // $result = Supplier::deleteSupplier($id_supplier);

            if ($result === true) {
                echo json_encode(["success" => "Proveedor eliminado exitosamente"]);
            } else {
                echo json_encode(["error" => $result]);
            }
        } else {
            echo json_encode(["error" => "ID de proveedor no proporcionado"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método de solicitud no válido"]);
}
?>