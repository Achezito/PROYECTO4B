<?php

require_once __DIR__ . '/../config/conection.php';

class MaterialLink {
    private $id_material;
    private $id_material_hardware;
    private $id_material_component;
    private $id_material_physical;

    // Constructor
    public function __construct($id_material = null, $id_material_hardware = null, $id_material_component = null, $id_material_physical = null) {
        $this->id_material = $id_material;
        $this->id_material_hardware = $id_material_hardware;
        $this->id_material_component = $id_material_component;
        $this->id_material_physical = $id_material_physical;
    }

    // Getters y Setters
    public function getIdMaterial() { return $this->id_material; }
    public function setIdMaterial($id_material) { $this->id_material = $id_material; }

    public function getIdMaterialHardware() { return $this->id_material_hardware; }
    public function setIdMaterialHardware($id_material_hardware) { $this->id_material_hardware = $id_material_hardware; }

    public function getIdMaterialComponent() { return $this->id_material_component; }
    public function setIdMaterialComponent($id_material_component) { $this->id_material_component = $id_material_component; }

    public function getIdMaterialPhysical() { return $this->id_material_physical; }
    public function setIdMaterialPhysical($id_material_physical) { $this->id_material_physical = $id_material_physical; }




    public static function getAll() {
        $connection = Conexion::get_connection();
        $query = "SELECT * FROM MATERIAL_LINK";
        $result = $connection->query($query);

        $links = [];
        while ($row = $result->fetch_assoc()) {
            $links[] = $row;
        }

        return $links;
    }

    public static function getHardware() {
        $connection = Conexion::get_connection();
        $query = "SELECT id_material, model, brand FROM MATERIAL_HARDWARE";
        $result = $connection->query($query);

        $materials = [];
        while ($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }

        return $materials;
    }

    public static function getComponents() {
        $connection = Conexion::get_connection();
        $query = "SELECT id_material, model, brand FROM MATERIAL_COMPONENT";
        $result = $connection->query($query);

        $materials = [];
        while ($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }

        return $materials;
    }

    public static function getPhysical() {
        $connection = Conexion::get_connection();
        $query = "SELECT id_material, model, brand FROM MATERIAL_PHYSICAL";
        $result = $connection->query($query);

        $materials = [];
        while ($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }

        return $materials;
    }

    public static function insert($id_supply, $id_material_hardware, $id_material_component, $id_material_physical) {
        $connection = Conexion::get_connection();
        $query = "INSERT INTO MATERIAL_LINK (id_supply, id_material_hardware, id_material_component, id_material_physical)
                  VALUES (?, ?, ?, ?)";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("iiii", $id_supply, $id_material_hardware, $id_material_component, $id_material_physical);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


  




    // Eliminar registro
    public function delete() {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "DELETE FROM MATERIAL_LINK WHERE id_material = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $this->id_material);

        return $command->execute() ? true : $command->error;
    }
}
?>