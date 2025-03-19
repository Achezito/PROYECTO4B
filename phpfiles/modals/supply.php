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
    public static function getSupplies(){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_supply, nombre, descripcion, id_supplier FROM SUPPLY";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_supply, $nombre, $descripcion, $id_supplier);

        $supplies = [];
        while ($command->fetch()) {
            $supplies[] = [
                "id_supply" => $id_supply,
                "nombre" => $nombre,
                "descripcion" => $descripcion,
                "id_supplier" => $id_supplier
            ];
        }

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
}
