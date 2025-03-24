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


public static function createWarehouse($name, $location, $capacity) {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Database connection error: " . $connection->connect_error;
    }

    $query = "INSERT INTO warehouse (name, location, capacity) VALUES (?, ?, ?)";
    $statement = $connection->prepare($query);

    if (!$statement) {
        return "Error preparing statement: " . $connection->error;
    }

    $statement->bind_param("ssi", $name, $location, $capacity); // "ssi" indica string, string, integer
    $result = $statement->execute();

    if ($result) {
        $statement->close();
        $connection->close();
        return true; // Inserción exitosa
    } else {
        $error = $statement->error;
        $statement->close();
        $connection->close();
        return $error; // Devuelve el error si ocurre
    }
}


public static function updateWarehouse($id, $name, $location, $capacity) {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Database connection error: " . $connection->connect_error;
    }

    $query = "UPDATE warehouse SET name = ?, location = ?, capacity = ? WHERE id_warehouse = ?";
    $statement = $connection->prepare($query);

    if (!$statement) {
        return "Error preparing statement: " . $connection->error;
    }

    $statement->bind_param("ssii", $name, $location, $capacity, $id); // "ssii" indica string, string, integer, integer
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

    public static function getWarehouseById($id_warehouse){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_warehouse, name, location, capacity, created_at, updated_at FROM WAREHOUSE WHERE id_warehouse = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_warehouse);
        $command->execute();
        $command->bind_result(
            $id_warehouse,
            $name,
            $location,
            $capacity,
            $created_at,
            $updated_at
        );

        if ($command->fetch()) {
            return [
                "id_warehouse" => $id_warehouse,
                "name" => $name,
                "location" => $location,
                "capacity" => $capacity,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Warehouse not found.";
        }
    }

}
