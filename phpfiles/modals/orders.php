<?php

require_once __DIR__ . '/../config/conection.php';

class Order {
    private $id_order;
    private $id_status;
    private $id_supply;
    private $supply_quantity;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_order, $order_date, $id_status, $id_supply, $quantity, $created_at, $updated_at){
        $this->id_order = $id_order;
        $this->id_status = $id_status;
        $this->id_supply = $id_supply;
        $this->supply_quantity = $quantity;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdOrder(){ return $this->id_order; }
    public function setIdOrder($id_order){ $this->id_order = $id_order; }

    public function getIdStatus(){ return $this->id_status; }
    public function setIdStatus($id_status){ $this->id_status = $id_status; }

    public function getIdSupply(){ return $this->id_supply; }
    public function setIdSupply($id_supply){ $this->id_supply = $id_supply; }

    public function getQuantity(){ return $this->supply_quantity; }
    public function setQuantity($supply_quantity){ $this->supply_quantity = $supply_quantity; }

    public function getCreatedAt(){ return $this->created_at; }
    public function setCreatedAt($created_at){ $this->created_at = $created_at; }

    public function getUpdatedAt(){ return $this->updated_at; }
    public function setUpdatedAt($updated_at){ $this->updated_at = $updated_at; }

    // Obtener todas las órdenes
    public static function getOrders(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "SELECT 
        o.id_order,
        s.description AS status_name,
        o.supply_quantity,
        s2.description AS confirmation,
        o.created_at,
        o.updated_at
        FROM ORDERS o
        JOIN STATUS s ON o.id_status = s.id_status
        JOIN STATUS s2 ON o.confirmation = s2.id_status
        ORDER BY id_order ASC";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result(
            $id_order,
            $id_status,
            $supply_quantity,
            $confirmation,
            $created_at,
            $updated_at
        );

        $orders = [];
        while ($command->fetch()) {
            $orders[] = [
                "id_order" => $id_order,
                "id_status" => $id_status,
                "supply_quantity" => $supply_quantity,
                "confirmation" => $confirmation,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $orders;
    }


    public static function getOrdersByDateOrder($orderType){
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    // Validar el tipo de orden (solo permitir ASC o DESC para evitar SQL Injection)
    $orderType = strtoupper($orderType);
    if ($orderType !== 'ASC' && $orderType !== 'DESC') {
        $orderType = 'ASC'; // Valor por defecto si el usuario introduce un valor inválido
    }

    $query = "SELECT 
        o.id_order,
        s.description AS status_name,
        o.supply_quantity,
        s2.description AS confirmation,
        o.created_at,
        o.updated_at
    FROM ORDERS o
    JOIN STATUS s ON o.id_status = s.id_status
    JOIN STATUS s2 ON o.confirmation = s2.id_status
    ORDER BY o.id_order $orderType"; // Ordenar por fecha de creación en orden descendente

    $command = $connection->prepare($query);
    $command->execute();
    $command->bind_result($id_order, $status_name, $supply_quantity, $confirmation, $created_at, $updated_at);

    $orders = [];
    while ($command->fetch()) {
        $orders[] = [
            "id_order" => $id_order,
            "status_name" => $status_name,
            "supply_quantity" => $supply_quantity,
            "confirmation" => $confirmation,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        ];
    }

    return $orders;
}



    public static function getOrdersByStatus($status){
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "SELECT 
        o.id_order,
        s.description AS status_name,
        o.supply_quantity,
        s2.description AS confirmation,
        o.created_at,
        o.updated_at
    FROM ORDERS o
    JOIN STATUS s ON o.id_status = s.id_status
    JOIN STATUS s2 ON o.confirmation = s2.id_status
    WHERE o.id_status = ?";

    $command = $connection->prepare($query);
    $command->bind_param("i", $status);
    $command->execute();
    $command->bind_result($id_order, $status_name, $supply_quantity, $confirmation, $created_at, $updated_at);

    $orders = [];
    while ($command->fetch()) {
        $orders[] = [
            "id_order" => $id_order,
            "id_status" => $status_name,
            "supply_quantity" => $supply_quantity,
            "confirmation" => $confirmation,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        ];
    }
    return $orders;
}

public static function getOrdersByConfirmation($confirmation){
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "SELECT 
        o.id_order,
        s.description AS status_name,
        o.supply_quantity,
        s2.description AS confirmation,
        o.created_at,
        o.updated_at
    FROM ORDERS o
    JOIN STATUS s ON o.id_status = s.id_status
    JOIN STATUS s2 ON o.confirmation = s2.id_status
    WHERE o.confirmation = ?";

    $command = $connection->prepare($query);
    $command->bind_param("i", $confirmation);
    $command->execute();
    $command->bind_result($id_order, $status_name, $supply_quantity, $confirmation, $created_at, $updated_at);

    $orders = [];
    while ($command->fetch()) {
        $orders[] = [
            "id_order" => $id_order,
            "id_status" => $status_name,
            "supply_quantity" => $supply_quantity,
            "confirmation" => $confirmation,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        ];
    }
    return $orders;
}


    // Obtener una orden por ID
    public static function getOrderById($id_order){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query="SELECT 
        o.id_order,
        s.description AS status_name,
        o.supply_quantity,
        s2.description AS confirmation,
        o.created_at,
        o.updated_at
        FROM ORDERS o
        JOIN STATUS s ON o.id_status = s.id_status
        JOIN STATUS s2 ON o.confirmation = s2.id_status WHERE o.id_order = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_order);
        $command->execute();
        
        $command->bind_result(
            $id_order,
            $id_status,
            $supply_quantity,
            $confirmation,
            $created_at,
            $updated_at
        );

        $orders = [];
        while ($command->fetch()) {
            $orders[] = [
                "id_order" => $id_order,
                "id_status" => $id_status,
                "supply_quantity" => $supply_quantity,
                "confirmation" => $confirmation,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }

        return $orders;
    }

    public static function insert($quantity) {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $command = $connection->prepare("INSERT INTO ORDERS (supply_quantity) VALUES (?)");
        $command->bind_param('i', $quantity);
    
        if ($command->execute()) {
            return "Orden agregada correctamente";
        } else {
            return "Error al agregar orden: " . $connection->error;
        }
    }


    public static function updateOrderConfirmation($id_order, $confirmation) {
        $connection = Conexion::get_connection();
    
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "UPDATE ORDERS SET confirmation = ? WHERE id_order = ?";
        $command = $connection->prepare($query);
        $command->bind_param('ii', $confirmation, $id_order);
    
        if ($command->execute()) {
            return true;
        } else {
            return "Error al actualizar la confirmación: " . $connection->error;
        }
    }


    public static function delete($id_order) {
        $connection = Conexion::get_connection();
    
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        // Preparar la consulta SQL para eliminar una orden
        $query = "DELETE FROM ORDERS WHERE id_order = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_order);
    
        // Ejecutar y verificar la eliminación
        if ($command->execute()) {
            if ($command->affected_rows > 0) {
                return "Orden eliminada correctamente";
            } else {
                return "No se encontró la orden con el ID especificado";
            }
        } else {
            return "Error al eliminar orden: " . $connection->error;
        }
    }
    

    
    public static function getOrdersBySubWarehouseId($id_sub_warehouse) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $query = "
        SELECT 
    o.id_order AS 'ID Orden',
    o.created_at AS 'Fecha de Orden', -- Cambiado de order_date a created_at
    s.description AS 'Estado',
    sp.name AS 'Proveedor',
    o.supply_quantity AS 'Cantidad', -- Cambiado de quantity a supply_quantity
    sw.location AS 'Ubicación del Subalmacén'
FROM 
    ORDERS o
JOIN 
    STATUS s ON o.id_status = s.id_status
JOIN 
    SUPPLY su ON o.id_order = su.id_order
JOIN 
    SUPPLIER sp ON su.id_supplier = sp.id_supplier
JOIN 
    RECEIVED_MATERIAL rm ON su.id_supply = rm.id_supply
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
    
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    
        $command->close();
        $connection->close();
    
        return $orders;
    }
}
