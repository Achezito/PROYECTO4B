<?php
class Supply {
    private $id_supply;
    private $nombre;
    private $descripcion;
    private $id_supplier;

    // Constructor
    public function __construct($id_supply, $nombre, $descripcion, $id_supplier){
        $this->id_supply = $id_supply;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_supplier = $id_supplier;
    }

    // Getters y Setters
    public function getIdSupply(){ return $this->id_supply; }
    public function setIdSupply($id_supply){ $this->id_supply = $id_supply; }

    public function getNombre(){ return $this->nombre; }
    public function setNombre($nombre){ $this->nombre = $nombre; }

    public function getDescripcion(){ return $this->descripcion; }
    public function setDescripcion($descripcion){ $this->descripcion = $descripcion; }

    public function getIdSupplier(){ return $this->id_supplier; }
    public function setIdSupplier($id_supplier){ $this->id_supplier = $id_supplier; }

    // Obtener todos los suministros
 
    public static function getSupplies() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "
        SELECT 
    sp.id_supply AS id_supply,
    sp.id_order AS order_id,
    st.description AS supply_status,
    sp.quantity AS quantity,
    s.name AS supplier_name,
    s.contact_info AS supplier_contact,
    s.address AS supplier_address,
    COALESCE(mh.model, mc.model, mp.model) AS material_model,
    mt.name AS material_type
FROM SUPPLY sp
JOIN SUPPLIER s ON sp.id_supplier = s.id_supplier
LEFT JOIN ORDERS o ON sp.id_order = o.id_order
LEFT JOIN STATUS st ON sp.id_status = st.id_status
LEFT JOIN MATERIAL_LINK ml ON sp.id_supply = ml.id_supply
LEFT JOIN MATERIAL_HARDWARE mh ON ml.id_material_hardware = mh.id_material
LEFT JOIN MATERIAL_COMPONENT mc ON ml.id_material_component = mc.id_material
LEFT JOIN MATERIAL_PHYSICAL mp ON ml.id_material_physical = mp.id_material
LEFT JOIN MATERIAL_TYPE mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type);
        ";
    
        $command = $connection->prepare($query);
        $command->execute();
        $result = $command->get_result();
    
        $supplies = [];
        while ($row = $result->fetch_assoc()) {
            $supplies[] = $row;
        }
    
        $command->close();
        $connection->close();
    
        return $supplies;
    }

    public static function getSuppliesByOrder($id_order) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "
        SELECT 
            sp.id_supply AS id_supply,
            sp.id_order AS order_id,
            st.description AS supply_status,
            sp.quantity AS quantity,
            s.name AS supplier_name,
            COALESCE(mh.model, mc.model, mp.model) AS material_model,
            mt.name AS material_type
        FROM SUPPLY sp
        JOIN SUPPLIER s ON sp.id_supplier = s.id_supplier
        LEFT JOIN ORDERS o ON sp.id_order = o.id_order
        LEFT JOIN STATUS st ON sp.id_status = st.id_status
        LEFT JOIN MATERIAL_LINK ml ON sp.id_supply = ml.id_supply
        LEFT JOIN MATERIAL_HARDWARE mh ON ml.id_material_hardware = mh.id_material
        LEFT JOIN MATERIAL_COMPONENT mc ON ml.id_material_component = mc.id_material
        LEFT JOIN MATERIAL_PHYSICAL mp ON ml.id_material_physical = mp.id_material
        LEFT JOIN MATERIAL_TYPE mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type)
        WHERE sp.id_order = ?;
        ";
    
        $command = $connection->prepare($query);
        $command->bind_param("i", $id_order);
        $command->execute();
        $result = $command->get_result();
    
        $supplies = [];
        while ($row = $result->fetch_assoc()) {
            $supplies[] = $row;
        }
    
        $command->close();
        $connection->close();
    
        return $supplies;
    }
    

    // Obtener un suministro por ID
    public static function getSupplyById($id_supply){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_supply, nombre, descripcion, id_supplier FROM SUPPLY WHERE id_supply = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_supply);
        $command->execute();
        $command->bind_result($id_supply, $nombre, $descripcion, $id_supplier);

        if ($command->fetch()) {
            return [
                "id_supply" => $id_supply,
                "nombre" => $nombre,
                "descripcion" => $descripcion,
                "id_supplier" => $id_supplier
            ];
        } else {
            return "Supply not found.";
        }
    }


    public static function getSuppliesBySubWarehouse($id_sub_warehouse) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "
        SELECT 
            sp.id_supply AS id_supply,
            sp.quantity AS quantity,
            sp.id_supplier AS id_supplier,
            s.name AS supplier_name,
            s.contact_info AS supplier_contact,
            s.address AS supplier_address,
            rm.description AS material_description,
            rm.serial_number AS material_serial,
            sw.location AS subwarehouse_location,
            sw.capacity AS subwarehouse_capacity
        FROM 
            SUPPLY sp
        JOIN 
            SUPPLIER s ON sp.id_supplier = s.id_supplier
        JOIN 
            RECEIVED_MATERIAL rm ON sp.id_supply = rm.id_supply
        JOIN 
            SUB_WAREHOUSE_MATERIAL swm ON rm.id_material = swm.id_material
        JOIN 
            SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
        WHERE 
            sw.id_sub_warehouse = ?;
        ";
    
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_sub_warehouse);
        $command->execute();
        $result = $command->get_result();
    
        $supplies = [];
        while ($row = $result->fetch_assoc()) {
            $supplies[] = $row;
        }
    
        $command->close();
        $connection->close();
    
        return $supplies;
    }
}
