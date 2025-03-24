<?php

require_once __DIR__ . '/../config/conection.php';

class SubWarehouseMaterial
{
    private $id_sub_warehouse;
    private $id_material;
    private $quantity;

    // Constructor
    public function __construct($id_sub_warehouse = null, $id_material = null, $quantity = null)
    {
        $this->id_sub_warehouse = $id_sub_warehouse;
        $this->id_material = $id_material;
        $this->quantity = $quantity;
    }

    // Getters y Setters
    public function getIdSubWarehouse()
    {
        return $this->id_sub_warehouse;
    }
    public function setIdSubWarehouse($id_sub_warehouse)
    {
        $this->id_sub_warehouse = $id_sub_warehouse;
    }

    public function getIdMaterial()
    {
        return $this->id_material;
    }
    public function setIdMaterial($id_material)
    {
        $this->id_material = $id_material;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    // Obtener todos los registros
    public static function getAll()
    {
        $data = array();
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_sub_warehouse, id_material, quantity FROM SUB_WAREHOUSE_MATERIAL";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_sub_warehouse, $id_material, $quantity);

        while ($command->fetch()) {
            $data[] = new SubWarehouseMaterial($id_sub_warehouse, $id_material, $quantity);
        }

        return $data;
    }

    // Obtener materiales por ID de subalmacén
    public static function getMaterialsBySubWarehouseId($id_sub_warehouse)
    {
        $data = array();
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        // Consulta SQL con JOIN para obtener los datos necesarios
        $query = "
            SELECT 
    swm.id_material,
    swm.quantity AS 'Cantidad Disponible',
    rm.serial_number AS Material,
    c.name AS Categoría,
    rm.description AS Descripción
FROM 
    SUB_WAREHOUSE_MATERIAL swm
JOIN 
    RECEIVED_MATERIAL rm ON swm.id_material = rm.id_material
JOIN 
    CATEGORY c ON rm.id_category = c.id_category
WHERE 
    swm.id_sub_warehouse = ?;
        ";

        $command = $connection->prepare($query);
        $command->bind_param('i', $id_sub_warehouse);
        $command->execute();
        $command->bind_result($id_material, $quantity, $material, $categoria, $descripcion);

        while ($command->fetch()) {
            $data[] = [
                "id_material" => $id_material,
                "Cantidad Disponible" => $quantity,
                "Material" => $material,
                "Categoría" => $categoria,
                "Descripción" => $descripcion
            ];
        }

        $command->close();
        $connection->close();

        return $data;
    }

    // Agregar nuevo registro
    public function insert()
    {
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "INSERT INTO SUB_WAREHOUSE_MATERIAL (id_sub_warehouse, id_material, quantity) VALUES (?, ?, ?)";
        $command = $connection->prepare($query);
        $command->bind_param('iii', $this->id_sub_warehouse, $this->id_material, $this->quantity);

        return $command->execute() ? true : $command->error;
    }

    // Actualizar cantidad
    public function update()
    {
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "UPDATE SUB_WAREHOUSE_MATERIAL SET quantity = ? WHERE id_sub_warehouse = ? AND id_material = ?";
        $command = $connection->prepare($query);
        $command->bind_param('iii', $this->quantity, $this->id_sub_warehouse, $this->id_material);

        return $command->execute() ? true : $command->error;
    }

    // Eliminar registro
    public function delete()
    {
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "DELETE FROM SUB_WAREHOUSE_MATERIAL WHERE id_sub_warehouse = ? AND id_material = ?";
        $command = $connection->prepare($query);
        $command->bind_param('ii', $this->id_sub_warehouse, $this->id_material);

        return $command->execute() ? true : $command->error;
    }
}
