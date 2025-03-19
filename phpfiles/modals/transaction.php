<?php

require_once __DIR__ . '/../config/conection.php';

// Clase para Transactions
class Transaction {
    private $id_transaction;
    private $id_material;
    private $id_sub_warehouse;
    private $type;
    private $quantity;
    private $transaction_date;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_transaction, $id_material, $id_sub_warehouse, $type, $quantity, $transaction_date, $created_at, $updated_at) {
        $this->id_transaction = $id_transaction;
        $this->id_material = $id_material;
        $this->id_sub_warehouse = $id_sub_warehouse;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->transaction_date = $transaction_date;
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

    public function getTransactionDate() { return $this->transaction_date; }
    public function setTransactionDate($transaction_date) { $this->transaction_date = $transaction_date; }

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

        $query = "SELECT id_transaction, id_material, id_sub_warehouse, type, quantity, transaction_date, created_at, updated_at FROM TRANSACTIONS";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_transaction, $id_material, $id_sub_warehouse, $type, $quantity, $transaction_date, $created_at, $updated_at);

        $transactions = [];
        while ($command->fetch()) {
            $transactions[] = [
                "id_transaction" => $id_transaction,
                "id_material" => $id_material,
                "id_sub_warehouse" => $id_sub_warehouse,
                "type" => $type,
                "quantity" => $quantity,
                "transaction_date" => $transaction_date,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
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
}
