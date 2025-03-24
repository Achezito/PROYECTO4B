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

public static function getSuppliers() {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "
    SELECT 
        id_supplier,
        name,
        contact_info,
        address,
        created_at,
        updated_at
    FROM 
        SUPPLIER;
    ";

    $command = $connection->prepare($query);
    $command->execute();
    $result = $command->get_result();

    $suppliers = [];
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }

    $command->close();
    $connection->close();

    return $suppliers;
}
 
public static function getSuppliersBySubWarehouse($id_sub_warehouse) {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "
    SELECT
        s.id_supplier,
        s.name,
        s.contact_info,
        s.address,
        s.created_at,
        s.updated_at
    FROM 
        SUPPLIER s
    JOIN 
        SUPPLY sp ON s.id_supplier = sp.id_supplier
    JOIN 
        RECEIVED_MATERIAL rm ON sp.id_supply = rm.id_supply
    JOIN 
        SUB_WAREHOUSE_MATERIAL swm ON rm.id_material = swm.id_material
    WHERE 
        swm.id_sub_warehouse = ?;
    ";

    $command = $connection->prepare($query);
    $command->bind_param('i', $id_sub_warehouse);
    $command->execute();
    $result = $command->get_result();

    $suppliers = [];
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }

    $command->close();
    $connection->close();

    return $suppliers;
}
    
public static function createSupplier($name, $contact_info, $address) {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "INSERT INTO SUPPLIER (name, contact_info, address) VALUES (?, ?, ?)";
    $command = $connection->prepare($query);
    $command->bind_param('sss', $name, $contact_info, $address);

    if ($command->execute()) {
        $command->close();
        $connection->close();
        return true; // Éxito
    } else {
        $error = $command->error;
        $command->close();
        $connection->close();
            return $error; // Error
        }
    }
    
        public static function updateSupplier($id_supplier, $name, $contact_info, $address) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "UPDATE SUPPLIER SET name = ?, contact_info = ?, address = ? WHERE id_supplier = ?";
        $command = $connection->prepare($query);
        $command->bind_param('sssi', $name, $contact_info, $address, $id_supplier);
    
        if ($command->execute()) {
            $command->close();
            $connection->close();
            return true; // Éxito
        } else {
            $error = $command->error;
            $command->close();
            $connection->close();
            return $error; // Error
        }
    }


 
  
}
 ?>