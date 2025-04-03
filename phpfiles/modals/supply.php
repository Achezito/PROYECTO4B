<?php
class Supply
{
    private $id_supply;
    private $nombre;
    private $descripcion;
    private $id_supplier;

    // Constructor
    public function __construct($id_supply, $nombre, $descripcion, $id_supplier)
    {
        $this->id_supply = $id_supply;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_supplier = $id_supplier;
    }

    // Getters y Setters
    public function getIdSupply()
    {
        return $this->id_supply;
    }
    public function setIdSupply($id_supply)
    {
        $this->id_supply = $id_supply;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getIdSupplier()
    {
        return $this->id_supplier;
    }
    public function setIdSupplier($id_supplier)
    {
        $this->id_supplier = $id_supplier;
    }

    // Obtener todos los suministros


public static function getSupplies()
{
    try {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            throw new Exception("Error en la conexión: " . $connection->connect_error);
        }

        $query = "
            SELECT 
                s.id_supply,
                s.quantity,
                s.created_at,
                s.updated_at,
                sup.name AS supplier_name,
                COALESCE(mh.model, mc.model, mp.model) AS material_model,
                COALESCE(mh.brand, mc.brand, mp.brand) AS material_brand,
                mt.name AS material_type
            FROM SUPPLY s
            LEFT JOIN SUPPLIER sup ON s.id_supplier = sup.id_supplier
            LEFT JOIN MATERIAL_LINK ml ON s.id_supply = ml.id_supply
            LEFT JOIN MATERIAL_HARDWARE mh ON ml.id_material_hardware = mh.id_material
            LEFT JOIN MATERIAL_COMPONENT mc ON ml.id_material_component = mc.id_material
            LEFT JOIN MATERIAL_PHYSICAL mp ON ml.id_material_physical = mp.id_material
            LEFT JOIN MATERIAL_TYPE mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type)
        ";

        $command = $connection->prepare($query);
        if (!$command) {
            throw new Exception("Error en la preparación de la consulta: " . $connection->error);
        }

        $command->execute();
        $result = $command->get_result();

        $supplies = [];
        while ($row = $result->fetch_assoc()) {
            $supplies[] = $row;
        }

        $command->close();
        $connection->close();

        return $supplies;
    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}

    public static function getSuppliesByOrder($id_order)
    {
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
        LEFT JOIN MATERIAL_HARDWARE mh ON sp.id_material_hardware = mh.id_material
        LEFT JOIN MATERIAL_COMPONENT mc ON sp.id_material_component = mc.id_material
        LEFT JOIN MATERIAL_PHYSICAL mp ON sp.id_material_physical = mp.id_material
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
    public static function getSupplyById($id_supply)
    {
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

    public static function insertSupply($quantity, $id_supplier, $id_order, $id_material_hardware, $id_material_component, $id_material_physical) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "INSERT INTO SUPPLY (quantity, id_supplier, id_order, id_material_hardware, id_material_component, id_material_physical) VALUES (?, ?, ?, ?, ?, ?)";
        
        $command = $connection->prepare($query);
        if (!$command) {
            return "Error en la preparación de la consulta: " . $connection->error;
        }
        
        $command->bind_param("iiiiii", $quantity, $id_supplier, $id_order, $id_material_hardware, $id_material_component, $id_material_physical);
        
        $result = $command->execute();
        if (!$result) {
            return "Error en la ejecución de la consulta: " . $command->error;
        }
        
        $inserted_id = $command->insert_id;
        
        $command->close();
        $connection->close();
        
        return [
            "success" => true,
            "message" => "Supply insertado correctamente.",
            "id_supply" => $inserted_id
        ];
    }
    


    public static function getSuppliesBySubWarehouse($id_sub_warehouse)
    {
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




    public static function getPendingSupplies()
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

       
        $query = "
        SELECT 
            sp.id_supply,
            sp.quantity AS supply_quantity,
            sp.id_order,
            s.name AS supplier_name,
            s.address AS supplier_address,
            COALESCE(mh.model, mc.model, mp.model) AS material_model,
            COALESCE(mh.brand, mc.brand, mp.brand) AS material_brand,
            mt.name AS material_type,
            st.description AS supply_status -- Agregar el estado del suministro
        FROM supply sp
        JOIN supplier s ON sp.id_supplier = s.id_supplier
        LEFT JOIN orders o ON sp.id_order = o.id_order
        LEFT JOIN material_link ml ON sp.id_supply = ml.id_supply
        LEFT JOIN material_hardware mh ON ml.id_material_hardware = mh.id_material
        LEFT JOIN material_component mc ON ml.id_material_component = mc.id_material
        LEFT JOIN material_physical mp ON ml.id_material_physical = mp.id_material
        LEFT JOIN material_type mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type)
        LEFT JOIN status st ON sp.id_status = st.id_status -- Unir con la tabla de estado
        WHERE st.description = 'Pendiente'; -- Filtrar por estado pendiente
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



    public static function getMaterialsBySupply($id_supply)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }



        $query = "
SELECT 
    COALESCE(mh.id_material, mc.id_material, mp.id_material) AS id_material,
    COALESCE(mh.model, mc.model, mp.model) AS material_model,
    COALESCE(mh.brand, mc.brand, mp.brand) AS material_brand,
    mt.name AS material_type,
    c.id_category,
    c.description AS category_description
FROM material_link ml
LEFT JOIN material_hardware mh ON ml.id_material_hardware = mh.id_material
LEFT JOIN material_component mc ON ml.id_material_component = mc.id_material
LEFT JOIN material_physical mp ON ml.id_material_physical = mp.id_material
LEFT JOIN material_type mt ON mt.id_type = COALESCE(mh.id_type, mc.id_type, mp.id_type)
LEFT JOIN category c ON c.id_category = mt.id_type
WHERE ml.id_supply = ?;
";

        $command = $connection->prepare($query);
        $command->bind_param('i', $id_supply); // Vincular el parámetro id_supply
        $command->execute();
        $result = $command->get_result();

        $materials = [];
        while ($row = $result->fetch_assoc()) {
            $materials[] = $row;
        }

        $command->close();
        $connection->close();

        return $materials;
    }
}
