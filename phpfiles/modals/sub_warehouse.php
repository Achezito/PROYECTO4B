<?php

require_once __DIR__ . '/../config/conection.php';

class SubWarehouse {
    private $id_sub_warehouse;
    private $location;
    private $capacity;
    private $id_warehouse;
    private $id_category;
    private $created_at;
    private $updated_at;

    public function __construct($id_sub_warehouse, $location, $capacity, $id_warehouse, $id_category, $created_at, $updated_at){
        $this->id_sub_warehouse = $id_sub_warehouse;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->id_warehouse = $id_warehouse;
        $this->id_category = $id_category;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getIdSubWarehouse(){ return $this->id_sub_warehouse; }
    public function setIdSubWarehouse($id_sub_warehouse){ $this->id_sub_warehouse = $id_sub_warehouse; }

    public function getLocation(){ return $this->location; }
    public function setLocation($location){ $this->location = $location; }

    public function getCapacity(){ return $this->capacity; }
    public function setCapacity($capacity){ $this->capacity = $capacity; }

    public function getIdWarehouse(){ return $this->id_warehouse; }
    public function setIdWarehouse($id_warehouse){ $this->id_warehouse = $id_warehouse; }

    public function getIdCategory(){ return $this->id_category; }
    public function setIdCategory($id_category){ $this->id_category = $id_category; }

    public function getCreatedAt(){ return $this->created_at; }
    public function setCreatedAt($created_at){ $this->created_at = $created_at; }

    public function getUpdatedAt(){ return $this->updated_at; }
    public function setUpdatedAt($updated_at){ $this->updated_at = $updated_at; }


    public static function getSubWarehouses(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_sub_warehouse, location, capacity, id_warehouse, id_category, created_at, updated_at FROM SUB_WAREHOUSE";
        $command = $connection->prepare($query);
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

        $subWarehouses = [];
        while ($command->fetch()) {
            $subWarehouses[] = [
                "id_sub_warehouse" => $id_sub_warehouse,
                "location" => $location,
                "capacity" => $capacity,
                "id_warehouse" => $id_warehouse,
                "id_category" => $id_category,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $subWarehouses;
    }

    public static function getSubWarehouseById($id_sub_warehouse){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query="SELECT id_sub_warehouse, location, capacity, id_warehouse, id_category, created_at, updated_at 
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

    public static function getSubWarehousesByWarehouseId($id_warehouse){
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
        $command -> close();
        $connection -> close();
        return $subWarehouses;

    }



    public static function getMaterialsBySubWarehouseId($id_sub_warehouse) {
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
}
