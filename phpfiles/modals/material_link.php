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

    // Obtener todos los registros
    public static function getAll() {
        $data = array();
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, id_material_hardware, id_material_component, id_material_physical FROM MATERIAL_LINK";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_material, $id_material_hardware, $id_material_component, $id_material_physical);

        while ($command->fetch()) {
            $data[] = new MaterialLink($id_material, $id_material_hardware, $id_material_component, $id_material_physical);
        }

        return $data;
    }

    public static function insert($id_material, $id_material_hardware, $id_material_component, $id_material_physical) {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $command = $connection->prepare("INSERT INTO MATERIAL_LINK (id_material, id_material_hardware, id_material_component, id_material_physical) VALUES (?, ?, ?, ?)");
        $command->bind_param('iiii', $id_material, $id_material_hardware, $id_material_component, $id_material_physical);
    
        if ($command->execute()) {
            return "Enlace de material agregado correctamente";
        } else {
            return "Error al agregar enlace de material: " . $connection->error;
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