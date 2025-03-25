<?php

require_once __DIR__ . '/../config/conection.php';

class component{
    private $id_material;
    private $chipset;
    private $form_factor;
    private $type; // tambien es para socket_type
    private $RAM_slots;
    private $max_RAM;
    private $expansion_slots;
    private $capacity;
    private $voltage;
    private $quantity;
    private $audio_channels;
    private $component_type;
    private $id_supplier;
    private $id_type;

    // Constructor
    public function __construct($id_material = null, $chipset = null, $form_factor = null, $type = null, $RAM_slots = null, $max_RAM = null, $expansion_slots = null){
        $this->id_material = $id_material;
        $this->chipset = $chipset;
        $this->form_factor = $form_factor;
        $this->type = $type;
        $this->RAM_slots = $RAM_slots;
        $this->max_RAM = $max_RAM;
        $this->expansion_slots = $expansion_slots;
    }

    // Getters y setters
    public function getIdMaterial() { return $this->id_material; }
    public function setIdMaterial($id_material) { $this->id_material = $id_material; }

    public function getChipset() { return $this->chipset;}
    public function setChipset($chipset) { $this->chipset = $chipset; }

    public function getFormFactor() { return $this->form_factor; }
    public function setFormFactor($form_factor) { $this->form_factor = $form_factor; }

    public function getType() { return $this->type; }
    public function setType($type) { $this->type = $type; }

    public function getRAMSlots() { return $this->RAM_slots; }
    public function setRAMSlots($RAM_slots) { $this->RAM_slots = $RAM_slots; }

    public function getMaxRAM() { return $this->max_RAM; }
    public function setMaxRAM($max_RAM) { $this->max_RAM = $max_RAM; }

    public function getExpansionSlots() { return $this->expansion_slots; }
    public function setExpansionSlots($expansion_slots) { $this->expansion_slots = $expansion_slots; }

    public function getCapacity() { return $this->capacity; }
    public function setCapacity($capacity) { $this->capacity = $capacity; }

    public function getVoltage() { return $this->voltage; }
    public function setVoltage($voltage) { $this->voltage = $voltage; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getAudioChannels() { return $this->audio_channels; }
    public function setAudioChannels($audio_channels) { $this->audio_channels = $audio_channels; }

    public function getComponentType() { return $this->component_type; }
    public function setComponentType($component_type) { $this->component_type = $component_type;}


    public static function getAllComponents() {
        $components = array();
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexion" . $connection->connect_error;
        }

        $command = $connection->prepare("SELECT 
        mc.id_material, 
        mc.model, 
        mc.brand,
        mc.chipset, 
        mc.form_factor, 
        mc.type, 
        mc.RAM_slots, 
        mc.max_RAM, 
        mc.expansion_slots, 
        mc.capacity, 
        mc.voltage, 
        mc.quantity, 
        mc.audio_channels, 
        mt.name AS material_type_name, 
        s.name AS supplier_name
    FROM MATERIAL_COMPONENT mc
    JOIN MATERIAL_TYPE mt ON mc.id_type = mt.id_type
    JOIN SUPPLIER s ON mc.id_supplier = s.id_supplier
    ORDER BY mc.id_material");

$command->execute();

$command->bind_result(
    $id_material,
    $model,
    $brand,
    $chipset,
    $form_factor,
    $type,
    $RAM_slots,
    $max_RAM,
    $expansion_slots,
    $capacity,
    $voltage,
    $quantity,
    $audio_channels,
    $material_type_name,
    $supplier_name
);

$components = []; 

while ($command->fetch()) {
    $components[] = [
        "id_material" => $id_material,
        "model" => $model,
        "brand" => $brand,
        "chipset" => $chipset,
        "form_factor" => $form_factor,
        "type" => $type,
        "RAM_slots" => $RAM_slots,
        "max_RAM" => $max_RAM,
        "expansion_slots" => $expansion_slots,
        "capacity" => $capacity,
        "voltage" => $voltage,
        "quantity" => $quantity,
        "audio_channels" => $audio_channels,
        "material_type_name" => $material_type_name,
        "supplier_name" => $supplier_name
    ];
}

    
        return $components;
    }

    public static function insert($chipset, $form_factor, $socket_type, $RAM_slots, $max_RAM, $expansion_slots, $capacity, $voltage, $quantity, $audio_channels, $component_type, $id_type) {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $command = $connection->prepare("INSERT INTO MATERIAL_COMPONENT (chipset, form_factor, socket_type, RAM_slots, max_RAM, expansion_slots, capacity, voltage, quantity, audio_channels, component_type, id_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $command->bind_param('sssiiddddsii', $chipset, $form_factor, $socket_type, $RAM_slots, $max_RAM, $expansion_slots, $capacity, $voltage, $quantity, $audio_channels, $component_type, $id_type);
    
        if ($command->execute()) {
            return "Material componente agregado correctamente";
        } else {
            return "Error al agregar material componente: " . $connection->error;
        }
    }
    
    
    
}
