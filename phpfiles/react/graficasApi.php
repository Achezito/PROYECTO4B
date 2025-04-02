<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluye el archivo de conexión
require_once __DIR__ . '/../config/conection.php';

// Obtén la conexión a la base de datos
$conn = Conexion::get_connection();

// Verifica que la conexión esté establecida
if (!$conn) {
    echo json_encode(["error" => "No se pudo establecer la conexión a la base de datos"]);
    exit;
}

// Verifica qué tipo de consulta se solicita
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    switch ($type) {
        case 'sub_almacen':
            // Consulta para Material por Sub Almacén
            $query = "
                SELECT 
                    sw.location AS sub_almacen,
                    SUM(swm.quantity) AS total_materiales
                FROM 
                    SUB_WAREHOUSE sw
                LEFT JOIN SUB_WAREHOUSE_MATERIAL swm ON sw.id_sub_warehouse = swm.id_sub_warehouse
                GROUP BY sw.location;
            ";
            break;

        case 'suministros':
            // Consulta para Comparación de Suministros
            $query = "
                SELECT 
                    s.name AS proveedor,
                    SUM(sp.quantity) AS total_suministros
                FROM 
                    SUPPLIER s
                LEFT JOIN SUPPLY sp ON s.id_supplier = sp.id_supplier
                GROUP BY s.name;
            ";
            break;

        case 'progreso_inventario':
            // Consulta para Progreso de Inventario
            $query = "
                SELECT 
                    w.name AS almacen,
                    SUM(swm.quantity) / w.capacity AS porcentaje_ocupacion
                FROM 
                    WAREHOUSE w
                LEFT JOIN SUB_WAREHOUSE sw ON w.id_warehouse = sw.id_warehouse
                LEFT JOIN SUB_WAREHOUSE_MATERIAL swm ON sw.id_sub_warehouse = swm.id_sub_warehouse
                GROUP BY w.name, w.capacity;
            ";
            break;

        case 'materiales_por_tipo':
            // Consulta para Materiales por Tipo
            $query = "
                SELECT 
                    mt.name AS tipo_material,
                    COUNT(COALESCE(mh.id_material, mc.id_material, mp.id_material)) AS total_materiales
                FROM 
                    MATERIAL_TYPE mt
                LEFT JOIN MATERIAL_HARDWARE mh ON mt.id_type = mh.id_type
                LEFT JOIN MATERIAL_COMPONENT mc ON mt.id_type = mc.id_type
                LEFT JOIN MATERIAL_PHYSICAL mp ON mt.id_type = mp.id_type
                GROUP BY mt.name
                ORDER BY total_materiales DESC;
            ";
            break;

        default:
            echo json_encode(["error" => "Tipo de consulta no válido"]);
            exit;
    }

    // Ejecuta la consulta y devuelve los resultados
    $result = mysqli_query($conn, $query);

    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Error en la consulta: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["error" => "No se especificó el tipo de consulta"]);
}

// Cierra la conexión
mysqli_close($conn);