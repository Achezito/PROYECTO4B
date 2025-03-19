<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/transaction.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Permite diferentes metodos
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener warehouse
       $transaction = Transaction::getAllTransactions();

        if (is_array($transaction)) {
            echo json_encode($transaction, true);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>

