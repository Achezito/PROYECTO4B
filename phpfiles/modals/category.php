<?php

require_once __DIR__ . '/../config/conection.php';

class Category {
    private $id_category;
    private $name;
    private $description;
    private $created_at;
    private $updated_at;

    // Constructor
    public function __construct($id_category, $name, $description, $created_at, $updated_at) {
        $this->id_category = $id_category;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters y Setters
    public function getIdCategory() { return $this->id_category; }
    public function setIdCategory($id_category) { $this->id_category = $id_category; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    // Obtener todas las categorías
    public static function getAllCategories() {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_category, name, description, created_at, updated_at FROM CATEGORY";
        $command = $connection->prepare($query);
        $command->execute();
        $command->bind_result($id_category, $name, $description, $created_at, $updated_at);

        $categories = [];
        while ($command->fetch()) {
            $categories[] = [
                "id_category" => $id_category,
                "name" => $name,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        }
        return $categories;
    }

    public static function insert($name, $description) {
        $connection = Conexion::get_connection();

        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $command = $connection->prepare("INSERT INTO CATEGORY (`name`, `description`) VALUES (?, ?)");
        $command->bind_param('ss', $name, $description);

        if ($command->execute()) {
            return "Categoría agregada correctamente";
        } else {
            return "Error al agregar la categoría: " . $connection->error;
        }
    }

    // Obtener categoría por ID
    public static function getCategoryById($id_category) {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexión: " . $connection->connect_error;
        }

        $query = "SELECT id_category, name, description, created_at, updated_at FROM CATEGORY WHERE id_category = ?";
        $command = $connection->prepare($query);
        $command->bind_param('i', $id_category);
        $command->execute();
        $command->bind_result($id_category, $name, $description, $created_at, $updated_at);

        if ($command->fetch()) {
            return [
                "id_category" => $id_category,
                "name" => $name,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ];
        } else {
            return "Categoría no encontrada.";
        }
    }
}
