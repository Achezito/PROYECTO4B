<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/supply.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes metodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener warehouse
        $supply = Supply::getSupplies();

        if (is_array($supply)) {
            echo json_encode($supply, true);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>

