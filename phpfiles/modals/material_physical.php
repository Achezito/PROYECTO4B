<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para MaterialPhysical
class MaterialPhysical {
    private $id_material;
    private $model;
    private $brand;
    private $resolution;
    private $size;
    private $design;
    private $material_type;
    private $sensitivity;
    private $connectivity;
    private $id_type;
    private $id_supplier;
    

    // Constructor
    public function __construct($id_material, $model, $brand, $resolution, $size, $design, $material_type, $sensitivity, $connectivity, $id_type, $id_supplier) {
        $this->id_material = $id_material;
        $this->model = $model;
        $this->brand = $brand;
        $this->resolution = $resolution;
        $this->size = $size;
        $this->design = $design;
        $this->material_type = $material_type;
        $this->sensitivity = $sensitivity;
        $this->connectivity = $connectivity;
        $this->id_type = $id_type;
        $this->id_supplier = $id_supplier;
    }

    // Getters y Setters
    public function getIdMaterial(){ return $this->id_material; }
    public function setIdMaterial($id_material){ $this->id_material = $id_material; }

    public function getModel(){ return $this->model; }
    public function setModel($model){ $this->model = $model; }
    
    public function getIdType(){ return $this->id_type; }
    public function setIdType($id_type){ $this->id_type = $id_type; }

    public function getIdSupplier(){ return $this->id_supplier; }
    public function setIdSupplier($id_supplier){ $this->id_supplier = $id_supplier; }

    public function getBrand(){ return $this->brand; }
    public function setBrand($brand){ $this->brand = $brand; }

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


    public static function getAllMaterialPhysical() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_material, model, brand, resolution, size, design, material_type, sensitivity, connectivity, id_type, id_supplier FROM MATERIAL_PHYSICAL";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_material, $model, $brand, $resolution, $size, $design, $material_type, $sensitivity, $connectivity, $id_type, $id_supplier);

        $materials = [];
        while ($command->fetch()) {
            $materials[] = [
                "id_material" => $id_material,
                "model" => $model,
                "brand" => $brand,
                "resolution" => $resolution,
                "size" => $size,
                "design" => $design,
                "material_type" => $material_type,
                "sensitivity" => $sensitivity,
                "connectivity" => $connectivity,
                "id_type" => $id_type,
                "id_supplier" => $id_supplier
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

    public static function insert($resolution, $size, $design, $material_type, $sensitivity, $connectivity, $id_type) {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $command = $connection->prepare("INSERT INTO MATERIAL_PHYSICAL (resolution, size, design, material_type, sensitivity, connectivity, id_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $command->bind_param('ssssssi', $resolution, $size, $design, $material_type, $sensitivity, $connectivity, $id_type);
    
        if ($command->execute()) {
            return "Material físico agregado correctamente";
        } else {
            return "Error al agregar material físico: " . $connection->error;
        }
    }
    
}
