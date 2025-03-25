<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para Transactions
class Transaction {
    private $id_transaction;
    private $id_material;
    private $id_sub_warehouse;
    private $type;
    private $quantity;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_transaction, $id_material, $id_sub_warehouse, $type, $quantity, $created_at, $updated_at) {
        $this->id_transaction = $id_transaction;
        $this->id_material = $id_material;
        $this->id_sub_warehouse = $id_sub_warehouse;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdTransaction() { return $this->id_transaction; }
    public function setIdTransaction($id_transaction) { $this->id_transaction = $id_transaction; }

    public function getIdMaterial() { return $this->id_material; }
    public function setIdMaterial($id_material) { $this->id_material = $id_material; }

    public function getIdSubWarehouse() { return $this->id_sub_warehouse; }
    public function setIdSubWarehouse($id_sub_warehouse) { $this->id_sub_warehouse = $id_sub_warehouse; }

    public function getType() { return $this->type; }
    public function setType($type) { $this->type = $type; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Obtener todas las transacciones
    public static function getAllTransactions() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "
    SELECT 
        t.id_transaction AS 'ID Transaccion',
        t.created_at AS 'Fecha de Transaccion', -- Cambiado de transaction_date a created_at
        t.type AS 'Tipo de Transaccion',
        t.quantity AS 'Cantidad',
        sw.location AS 'Almacen de destino',
        rm.serial_number AS 'Material',
        rm.description AS 'Descripcion del Material'
    FROM TRANSACTIONS t
    JOIN SUB_WAREHOUSE sw ON t.id_sub_warehouse = sw.id_sub_warehouse
    JOIN RECEIVED_MATERIAL rm ON t.id_material = rm.id_material
    ";

    $command = $connection->prepare($query);
    $command->execute();
    $result = $command->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

        return $transactions;
    }

    // Obtener transacción por ID
    public static function getTransactionById($id_transaction) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_transaction, id_material, id_sub_warehouse, type, quantity, transaction_date, created_at, updated_at FROM TRANSACTIONS WHERE id_transaction = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_transaction);
        $command->execute();
        $command->bind_result($id_transaction, $id_material, $id_sub_warehouse, $type, $quantity, $transaction_date, $created_at, $updated_at);

        if ($command->fetch()) {
            return [
                "id_transaction" => $id_transaction,
                "id_material" => $id_material,
                "id_sub_warehouse" => $id_sub_warehouse,
                "type" => $type,
                "quantity" => $quantity,
                "transaction_date" => $transaction_date,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Transacción no encontrada.";
        }
    }

  
 
 
public static function getTransactionsBySubWarehouseId($id_sub_warehouse) {
    $connection = Conexion::get_connection();
    if ($connection->connect_error) {
        return "Error en la conexión: " . $connection->connect_error;
    }

    $query = "
    SELECT 
        t.id_transaction AS 'ID Transacción',
        t.created_at AS 'Fecha de Transacción', -- Cambiado de transaction_date a created_at
        t.type AS 'Tipo de Transacción',
        t.quantity AS 'Cantidad',
        sw.location AS 'Ubicación del Subalmacén',
        rm.serial_number AS 'Material',
        rm.description AS 'Descripción del Material'
    FROM TRANSACTIONS t
    JOIN SUB_WAREHOUSE sw ON t.id_sub_warehouse = sw.id_sub_warehouse
    JOIN RECEIVED_MATERIAL rm ON t.id_material = rm.id_material
    WHERE t.id_sub_warehouse = ?;
    ";

    $command = $connection->prepare($query);
    $command->bind_param('i', $id_sub_warehouse);
    $command->execute();
    $result = $command->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    $command->close();
    $connection->close();

    return $transactions;
}

public static function insertTransaction($id_material, $id_sub_warehouse, $type, $quantity) {
    try {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            throw new Exception("Error en la conexión: " . $connection->connect_error);
        }

        // Verificar si el material y el subalmacén existen
        $queryCheck = "
            SELECT COUNT(*) AS count
            FROM SUB_WAREHOUSE_MATERIAL
            WHERE id_material = ? AND id_sub_warehouse = ?;
        ";
        $stmtCheck = $connection->prepare($queryCheck);
        $stmtCheck->bind_param('ii', $id_material, $id_sub_warehouse);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();

        if ($rowCheck['count'] === 0) {
            throw new Exception("El material o el subalmacén no existen.");
        }

        // Insertar la transacción
        $queryInsert = "
            INSERT INTO TRANSACTIONS (id_material, id_sub_warehouse, `type`, quantity)
            VALUES (?, ?, ?, ?);
        ";
        $stmtInsert = $connection->prepare($queryInsert);
        $stmtInsert->bind_param('iisi', $id_material, $id_sub_warehouse, $type, $quantity);
        $stmtInsert->execute();

        $stmtInsert->close();
        $connection->close();

        return true;
    } catch (Exception $e) {
        error_log("Error al insertar transacción: " . $e->getMessage());
        return $e->getMessage();
    }
}
}
