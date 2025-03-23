<?php

require_once __DIR__ . '/../config/conection.php';

class Order {
    private $id_order;
    private $order_date;
    private $id_status;
    private $id_supply;
    private $quantity;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_order, $order_date, $id_status, $id_supply, $quantity, $created_at, $updated_at){
        $this->id_order = $id_order;
        $this->order_date = $order_date;
        $this->id_status = $id_status;
        $this->id_supply = $id_supply;
        $this->quantity = $quantity;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdOrder(){ return $this->id_order; }
    public function setIdOrder($id_order){ $this->id_order = $id_order; }

    public function getOrderDate(){ return $this->order_date; }
    public function setOrderDate($order_date){ $this->order_date = $order_date; }

    public function getIdStatus(){ return $this->id_status; }
    public function setIdStatus($id_status){ $this->id_status = $id_status; }

    public function getIdSupply(){ return $this->id_supply; }
    public function setIdSupply($id_supply){ $this->id_supply = $id_supply; }

    public function getQuantity(){ return $this->quantity; }
    public function setQuantity($quantity){ $this->quantity = $quantity; }

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
    
        $query = "SELECT id_order, order_date, id_status, id_supply, quantity, created_at, updated_at FROM ORDERS";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result(
            $id_order,
            $order_date,
            $id_status,
            $id_supply,
            $quantity,
            $created_at,
            $updated_at
        );

        $orders = [];
        while ($command->fetch()) {
            $orders[] = [
                "id_order" => $id_order,
                "order_date" => $order_date,
                "id_status" => $id_status,
                "id_supply" => $id_supply,
                "quantity" => $quantity,
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

        $query="SELECT id_order, order_date, id_status, id_supply, quantity, created_at, updated_at 
                FROM ORDERS WHERE id_order = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_order);
        $command->execute();
        $command->bind_result(
            $id_order,
            $order_date,
            $id_status,
            $id_supply,
            $quantity,
            $created_at,
            $updated_at
        );

        if ($command->fetch()) {
            return [
                "id_order" => $id_order,
                "order_date" => $order_date,
                "id_status" => $id_status,
                "id_supply" => $id_supply,
                "quantity" => $quantity,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Order not found.";
        }
    }

    public static function insert($order_date, $id_status, $id_supply, $quantity) {
        $connection = Conexion::get_connection();
        
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }
    
        $command = $connection->prepare("INSERT INTO ORDERS (order_date, id_status, id_supply, quantity) VALUES (?, ?, ?, ?)");
        $command->bind_param('siii', $order_date, $id_status, $id_supply, $quantity);
    
        if ($command->execute()) {
            return "Orden agregada correctamente";
        } else {
            return "Error al agregar orden: " . $connection->error;
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
        o.order_date AS 'Fecha de Orden',
        s.description AS 'Estado',
        su.nombre AS 'Proveedor',
        o.quantity AS 'Cantidad',
        sw.location AS 'Ubicación del Subalmacén'
    FROM ORDERS o
    JOIN STATUS s ON o.id_status = s.id_status
    JOIN SUPPLY su ON o.id_supply = su.id_supply
    JOIN RECEIVED_MATERIAL rm ON o.id_supply = rm.id_supply
    JOIN SUB_WAREHOUSE_MATERIAL swm ON rm.id_material = swm.id_material
    JOIN SUB_WAREHOUSE sw ON swm.id_sub_warehouse = sw.id_sub_warehouse
    WHERE sw.id_sub_warehouse = ?;
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
