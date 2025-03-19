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
}
