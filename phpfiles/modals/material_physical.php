<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para MaterialPhysical
class MaterialPhysical {
    private $id_material;
    private $resolution;
    private $size;
    private $design;
    private $material_type;
    private $sensitivity;
    private $connectivity;

    // Constructor
    public function __construct($id_material, $resolution, $size, $design, $material_type, $sensitivity, $connectivity) {
        $this->id_material = $id_material;
        $this->resolution = $resolution;
        $this->size = $size;
        $this->design = $design;
        $this->material_type = $material_type;
        $this->sensitivity = $sensitivity;
        $this->connectivity = $connectivity;
    }

    // Getters y Setters
    public function getIdMaterial(){ return $this->id_material; }
    public function setIdMaterial($id_material){ $this->id_material = $id_material; }

    public function getResolution(){ return $this->resolution; }
    public function setResolution($resolution){ $this->resolution = $resolution; }

    public function getSize(){ return $this->size; }
    public function setSize($size){ $this->size = $size; }

    public function getDesign(){ return $this->design; }
    public function setDesign($design){ $this->design = $design; }

    public function getMaterialType(){ return $this->material_type; }
    public function setMaterialType($material_type){ $this->material_type = $material_type; }

    public function getSensitivity(){ return $this->sensitivity; }
    public function setSensitivity($sensitivity){ $this->sensitivity = $sensitivity; }

    public function getConnectivity(){ return $this->connectivity; }
    public function setConnectivity($connectivity){ $this->connectivity = $connectivity; }

    // Obtener todos los materiales físicos
    public static function getAllMaterialPhysical() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, resolution, size, design, material_type, sensitivity, connectivity FROM MATERIAL_PHYSICAL";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_material, $resolution, $size, $design, $material_type, $sensitivity, $connectivity);

        $materials = [];
        while ($command->fetch()) {
            $materials[] = [
                "id_material" => $id_material,
                "resolution" => $resolution,
                "size" => $size,
                "design" => $design,
                "material_type" => $material_type,
                "sensitivity" => $sensitivity,
                "connectivity" => $connectivity
            ];
        }

        return $materials;
    }

    // Obtener material físico por ID
    public static function getMaterialPhysicalById($id_material) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, resolution, size, design, material_type, sensitivity, connectivity FROM MATERIAL_PHYSICAL WHERE id_material = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_material);
        $command->execute();
        $command->bind_result($id_material, $resolution, $size, $design, $material_type, $sensitivity, $connectivity);

        if ($command->fetch()) {
            return [
                "id_material" => $id_material,
                "resolution" => $resolution,
                "size" => $size,
                "design" => $design,
                "material_type" => $material_type,
                "sensitivity" => $sensitivity,
                "connectivity" => $connectivity
            ];
        } else {
            return "Material físico no encontrado.";
        }
    }
}
