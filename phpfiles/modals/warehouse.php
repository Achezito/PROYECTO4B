<?php

require_once __DIR__ . '/../config/conection.php';

class Warehouse {
    private $id_warehouse;
    private $name;
    private $location;
    private $capacity;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_warehouse, $name, $location, $capacity, $created_at, $updated_at){
        $this->id_warehouse = $id_warehouse;
        $this->name = $name;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getIdWarehouse(){ return $this->id_warehouse; }
    public function setIdWarehouse($id_warehouse){ $this->id_warehouse = $id_warehouse; }

    public function getName(){ return $this->name; }
    public function setName($name){ $this->name = $name; }

    public function getLocation(){ return $this->location; }
    public function setLocation($location){ $this->location = $location; }

    public function getCapacity(){ return $this->capacity; }
    public function setCapacity($capacity){ $this->capacity = $capacity; }

    public function getCreatedAt(){ return $this->created_at; }
    public function setCreatedAt($created_at){ $this->created_at = $created_at; }

    public function getUpdatedAt(){ return $this->updated_at; }
    public function setUpdatedAt($updated_at){ $this->updated_at = $updated_at; }


    public static function getWarehouse(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "SELECT id_warehouse, name, location, capacity, created_at, updated_at FROM WAREHOUSE";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result(
            $id_warehouse,
            $name,
            $location,
            $capacity,
            $created_at,
            $updated_at
        );

        // Para poder llamar los datos con PHP desde la base de datos primero necesitas hacer que el array que guarda los datos se
        // mande de esta forma. Se tiene que usar formato key => value porque se va a convertir en un json a la hora de enviarlo a react
        $warehouses = [];
        while ($command->fetch()) {
            $warehouses[] = [
                "id_warehouse" => $id_warehouse,
                "name" => $name,
                "location" => $location,
                "capacity" => $capacity,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $warehouses;
    }





// Todavia no voy a usar estos metodos, primero los metodos para obtener los datos y luego para insertar o actualizar

    public function createWarehouse(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "INSERT INTO WAREHOUSE (name, location, capacity) VALUES (?, ?, ?)";
        $command = $connection->prepare($query);
        $command->bind_param('ssi', $this->name, $this->location, $this->capacity);

        if ($command->execute()) {
            return "Warehouse created successfully.";
        } else {
            return "Error creating warehouse: " . $command->error;
        }
    }

    public function updateWarehouse(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "UPDATE WAREHOUSE SET name = ?, location = ?, capacity = ? WHERE id_warehouse = ?";
        $command = $connection->prepare($query);
        $command->bind_param('ssii', $this->name, $this->location, $this->capacity, $this->id_warehouse);

        if ($command->execute()) {
            return "Warehouse updated successfully.";
        } else {
            return "Error updating warehouse: " . $command->error;
        }
    }

    public static function deleteWarehouse($id_warehouse){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "DELETE FROM WAREHOUSE WHERE id_warehouse = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_warehouse);

        if ($command->execute()) {
            return "Warehouse deleted successfully.";
        } else {
            return "Error deleting warehouse: " . $command->error;
        }
    }
}
