<?php

require_once __DIR__ . '/../config/conection.php';

class SubWarehouse
{
    private $id_sub_warehouse;
    private $location;
    private $capacity;
    private $id_warehouse;
    private $id_category;
    private $created_at;
    private $updated_at;

    public function __construct($id_sub_warehouse, $location, $capacity, $id_warehouse, $id_category, $created_at, $updated_at)
    {
        $this->id_sub_warehouse = $id_sub_warehouse;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->id_warehouse = $id_warehouse;
        $this->id_category = $id_category;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getIdSubWarehouse()
    {
        return $this->id_sub_warehouse;
    }
    public function setIdSubWarehouse($id_sub_warehouse)
    {
        $this->id_sub_warehouse = $id_sub_warehouse;
    }

    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    public function getIdWarehouse()
    {
        return $this->id_warehouse;
    }
    public function setIdWarehouse($id_warehouse)
    {
        $this->id_warehouse = $id_warehouse;
    }

    public function getIdCategory()
    {
        return $this->id_category;
    }
    public function setIdCategory($id_category)
    {
        $this->id_category = $id_category;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

   




public static function getSubWarehouses() {
    $connection = Conexion::get_connection();
    $query = "SELECT id_sub_warehouse, location, capacity, id_warehouse, id_category FROM SUB_WAREHOUSE";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $subWarehouses = [];
        while ($row = $result->fetch_assoc()) {
            $subWarehouses[] = $row;
        }
        return $subWarehouses;
    } else {
        return [];
    }
}

    public static function getSubWarehouseById($id_sub_warehouse)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_sub_warehouse, location, capacity, id_warehouse, id_category, created_at, updated_at 
                FROM SUB_WAREHOUSE WHERE id_sub_warehouse = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_sub_warehouse);
        $command->execute();
        $command->bind_result(
            $id_sub_warehouse,
            $location,
            $capacity,
            $id_warehouse,
            $id_category,
            $created_at,
            $updated_at
        );

        if ($command->fetch()) {
            return [
                "id_sub_warehouse" => $id_sub_warehouse,
                "location" => $location,
                "capacity" => $capacity,
                "id_warehouse" => $id_warehouse,
                "id_category" => $id_category,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Sub-warehouse not found.";
        }
    }

    public static function getSubWarehousesByWarehouseId($id_warehouse)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
        $query = "
        SELECT 
        sb.id_sub_warehouse as 'ID',
        sb.location as 'Subalmacén',
        w.name as 'Almacén'
        FROM sub_warehouse as sb
        INNER JOIN warehouse  as w on sb.id_warehouse = w.id_warehouse
        WHERE w.id_warehouse = ?;";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_warehouse);
        $command->execute();
        $command->bind_result(
            $id_sub_warehouse,
            $location,
            $warehouse

        );

        $subWarehouses = [];
        while ($command->fetch()) {
            $subWarehouses[] = [
                "ID" => $id_sub_warehouse,
                "Location" => $location,
                "WareHouse" => $warehouse
            ];
        }
        $command->close();
        $connection->close();
        return $subWarehouses;
    }


    public static function updateSubWarehouse($id_sub_warehouse, $location, $capacity, $id_category)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión a la base de datos: " . $connection->connect_error;
        }

        $query = "UPDATE sub_warehouse SET location = ?, capacity = ?, id_category = ? WHERE id_sub_warehouse = ?";
        $statement = $connection->prepare($query);

        if (!$statement) {
            return "Error preparando la consulta: " . $connection->error;
        }

        $statement->bind_param("siii", $location, $capacity, $id_category, $id_sub_warehouse);
        $result = $statement->execute();

        if ($result) {
            $statement->close();
            $connection->close();
            return true; // Actualización exitosa
        } else {
            $error = $statement->error;
            $statement->close();
            $connection->close();
            return $error; // Devuelve el error si ocurre
        }
    }




    public static function createSubWarehouse($location, $capacity, $warehouse_id, $id_category)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error de conexión a la base de datos: " . $connection->connect_error;
        }

        $query = "INSERT INTO sub_warehouse (location, capacity, id_warehouse, id_category) VALUES (?, ?, ?, ?)";
        $statement = $connection->prepare($query);

        if (!$statement) {
            return "Error preparando la consulta: " . $connection->error;
        }

        $statement->bind_param("siii", $location, $capacity, $warehouse_id, $id_category);
        $result = $statement->execute();

        if ($result) {
            $statement->close();
            $connection->close();
            return true;
        } else {
            $error = $statement->error;
            $statement->close();
            $connection->close();
            return $error;
        }
    }


    public static function getMaterialsBySubWarehouseId($id_sub_warehouse)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            error_log("Error en la conexión: " . $connection->connect_error);
            return [];
        }

        $query = "
        SELECT 
            sw.location AS 'Ubicación del Subalmacén',
            c.name AS 'Categoría',
            m.name AS 'Material',
            m.description AS 'Descripción',
            swm.quantity AS 'Cantidad Disponible'
        FROM SUB_WAREHOUSE_MATERIAL swm
        JOIN SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
        JOIN RECEIVED_MATERIAL m ON swm.id_material = m.id_material
        JOIN CATEGORY c ON sw.id_category = c.id_category
        WHERE sw.id_sub_warehouse = ?;
        ";

        $command = $connection->prepare($query);
        $command->bind_param('i', $id_sub_warehouse);
        $command->execute();
        $command->bind_result(
            $location,
            $category,
            $material,
            $description,
            $quantity
        );

        $materials = [];
        while ($command->fetch()) {
            $materials[] = [
                "Ubicación del Subalmacén" => $location,
                "Categoría" => $category,
                "Material" => $material,
                "Descripción" => $description,
                "Cantidad Disponible" => $quantity
            ];
        }

        error_log("Materiales encontrados: " . json_encode($materials));

        $command->close();
        $connection->close();

        return $materials;
    }


    public static function exists($id_sub_warehouse)
    {
        $connection = Conexion::get_connection();
        $query = "SELECT COUNT(*) FROM SUB_WAREHOUSE WHERE id_sub_warehouse = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('i', $id_sub_warehouse);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }


    public static function getMaterialDistribution()
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            error_log("Error en la conexión: " . $connection->connect_error);
            return false;
        }

        $query = "
        SELECT 
            sw.location AS sub_warehouse,
            SUM(swm.quantity) AS total_quantity
        FROM 
            SUB_WAREHOUSE_MATERIAL swm
        INNER JOIN 
            SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
        GROUP BY 
            sw.location;
        ";

        $command = $connection->prepare($query);
        if (!$command) {
            error_log("Error preparando la consulta: " . $connection->error);
            return false;
        }

        $command->execute();
        $command->bind_result($sub_warehouse, $total_quantity);

        $distribution = [];
        while ($command->fetch()) {
            $distribution[] = [
                "sub_warehouse" => $sub_warehouse,
                "total_quantity" => $total_quantity
            ];
        }

        $command->close();
        $connection->close();

        return $distribution;
    }
}
