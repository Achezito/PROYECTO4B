<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para Supplier
class Supplier {
    private $id_supplier;
    private $name;
    private $contact_info;
    private $address;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_supplier, $name, $contact_info, $address, $created_at, $updated_at){
        $this->id_supplier = $id_supplier;
        $this->name = $name;
        $this->contact_info = $contact_info;
        $this->address = $address;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdSupplier(){ return $this->id_supplier; }
    public function setIdSupplier($id_supplier){ $this->id_supplier = $id_supplier; }

    public function getName(){ return $this->name; }
    public function setName($name){ $this->name = $name; }

    public function getContactInfo(){ return $this->contact_info; }
    public function setContactInfo($contact_info){ $this->contact_info = $contact_info; }

    public function getAddress(){ return $this->address; }
    public function setAddress($address){ $this->address = $address; }

    public function getCreatedAt(){ return $this->created_at; }
    public function setCreatedAt($created_at){ $this->created_at = $created_at; }

    public function getUpdatedAt(){ return $this->updated_at; }
    public function setUpdatedAt($updated_at){ $this->updated_at = $updated_at; }

    // Obtener todos los proveedores
    public static function getSuppliers(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "SELECT id_supplier, name, contact_info, address, created_at, updated_at FROM SUPPLIER";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_supplier, $name, $contact_info, $address, $created_at, $updated_at);

        $suppliers = [];
        while ($command->fetch()) {
            $suppliers[] = [
                "id_supplier" => $id_supplier,
                "name" => $name,
                "contact_info" => $contact_info,
                "address" => $address,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $suppliers;
    }
}
