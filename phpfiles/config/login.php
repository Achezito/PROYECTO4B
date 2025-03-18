<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../modals/User.php';
require_once __DIR__ . '/../config/conection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // Validar que los campos no estén vacíos
     /* if (empty($username) || empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "Usuario y contraseña son obligatorios."
        ]);
        exit();
    } */

    // Intentar iniciar sesión
    $user = User::Login($username, $password);

    if ($user instanceof User) {
        // Si el login fue exitoso
        $_SESSION["user_id"] = $user->getIdUser();
        $_SESSION["user_name"] = $user->getName();
        $_SESSION["user_role"] = $user->getIdRole();  // Aquí puedes gestionar el rol del usuario

        echo json_encode([
            "success" => true,
            "message" => "Inicio de sesión exitoso.",
            "user" => [
                "id" => $user->getIdUser(),
                "name" => $user->getName(),
                "role" => $user->getIdRole()
            ]
        ]);
    } else {
        // Si hubo un error en el login
        echo json_encode([
            "success" => false,
            "message" => "Credenciales incorrectas."
        ]);
    }
    exit();
}

// Si no es una solicitud POST
echo json_encode([
    "success" => false,
    "message" => "Método no permitido."
]);
exit();