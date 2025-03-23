<?php
require_once __DIR__ . '/../config/conection.php';
require_once '../modals/category.php';

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Agregar OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD']; // se lee el metodo para llamar diferentes datos dependiendo del metodos

switch ($method) {
    case 'GET': // Obtener warehouse
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $categories = Category::getCategoryById(isset($_GET['id']));
            if (is_array($categories)) {
                echo json_encode($categories, true);
            }
        } else {
            $categories = Category::getAllCategories();
            if (is_array($categories)) {
                echo json_encode($categories, true);
            }
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['name']) && isset($data['description'])) {
                Category::insert($data['name'], $data['description']);
                echo json_encode(["message" => "Se ingresó la categoría en la base de datos"]);
            } else {
                echo json_encode(["error" => "No se pudieron ingresar los valores"]);
            }
            break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}
?>

