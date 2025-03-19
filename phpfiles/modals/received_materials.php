<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para ReceivedMaterial
class ReceivedMaterial {
    private $id_material;
    private $name;
    private $description;
    private $quantity;
    private $batch_number;
    private $serial_number;
    private $date_received;
    private $id_supply;
    private $id_type;
    private $id_category;
    private $rotation;
    private $volume;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_material, $name, $description, $quantity, $batch_number, $serial_number, 
                                $date_received, $id_supply, $id_type, $id_category, $rotation, $volume, $created_at, $updated_at) {
        $this->id_material = $id_material;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->batch_number = $batch_number;
        $this->serial_number = $serial_number;
        $this->date_received = $date_received;
        $this->id_supply = $id_supply;
        $this->id_type = $id_type;
        $this->id_category = $id_category;
        $this->rotation = $rotation;
        $this->volume = $volume;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdMaterial(){ return $this->id_material; }
    public function setIdMaterial($id_material){ $this->id_material = $id_material; }

    public function getName(){ return $this->name; }
    public function setName($name){ $this->name = $name; }

    public function getDescription(){ return $this->description; }
    public function setDescription($description){ $this->description = $description; }

    public function getQuantity(){ return $this->quantity; }
    public function setQuantity($quantity){ $this->quantity = $quantity; }

    public function getBatchNumber(){ return $this->batch_number; }
    public function setBatchNumber($batch_number){ $this->batch_number = $batch_number; }

    public function getSerialNumber(){ return $this->serial_number; }
    public function setSerialNumber($serial_number){ $this->serial_number = $serial_number; }

    public function getDateReceived(){ return $this->date_received; }
    public function setDateReceived($date_received){ $this->date_received = $date_received; }

    public function getIdSupply(){ return $this->id_supply; }
    public function setIdSupply($id_supply){ $this->id_supply = $id_supply; }

    public function getIdType(){ return $this->id_type; }
    public function setIdType($id_type){ $this->id_type = $id_type; }

    public function getIdCategory(){ return $this->id_category; }
    public function setIdCategory($id_category){ $this->id_category = $id_category; }

    public function getRotation(){ return $this->rotation; }
    public function setRotation($rotation){ $this->rotation = $rotation; }

    public function getVolume(){ return $this->volume; }
    public function setVolume($volume){ $this->volume = $volume; }

    public function getCreatedAt(){ return $this->created_at; }
    public function setCreatedAt($created_at){ $this->created_at = $created_at; }

    public function getUpdatedAt(){ return $this->updated_at; }
    public function setUpdatedAt($updated_at){ $this->updated_at = $updated_at; }


    // Obtener todos los materiales recibidos
    public static function getAllMaterials() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, name, description, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category, rotation, volume, created_at, updated_at FROM RECEIVED_MATERIAL";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_material, $name, $description, $quantity, $batch_number, $serial_number, $date_received, $id_supply, $id_type, $id_category, $rotation, $volume, $created_at, $updated_at);

        $materials = [];
        while ($command->fetch()) {
            $materials[] = [
                "id_material" => $id_material,
                "name" => $name,
                "description" => $description,
                "quantity" => $quantity,
                "batch_number" => $batch_number,
                "serial_number" => $serial_number,
                "date_received" => $date_received,
                "id_supply" => $id_supply,
                "id_type" => $id_type,
                "id_category" => $id_category,
                "rotation" => $rotation,
                "volume" => $volume,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $materials;
    }

    // Obtener un material recibido por ID
    public static function getMaterialById($id_material) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, name, description, quantity, batch_number, serial_number, date_received, id_supply, id_type, id_category, rotation, volume, created_at, updated_at FROM RECEIVED_MATERIAL WHERE id_material = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_material);
        $command->execute();
        $command->bind_result($id_material, $name, $description, $quantity, $batch_number, $serial_number, $date_received, $id_supply, $id_type, $id_category, $rotation, $volume, $created_at, $updated_at);

        if ($command->fetch()) {
            return [
                "id_material" => $id_material,
                "name" => $name,
                "description" => $description,
                "quantity" => $quantity,
                "batch_number" => $batch_number,
                "serial_number" => $serial_number,
                "date_received" => $date_received,
                "id_supply" => $id_supply,
                "id_type" => $id_type,
                "id_category" => $id_category,
                "rotation" => $rotation,
                "volume" => $volume,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Material not found.";
        }
    }
}
