<?php
require_once __DIR__ . '/../modals/User.php';
require_once __DIR__ . '/../config/conection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user"];
    $password = $_POST["password"];

    $user = User::Login($username, $password);

    $user = User::Login($username, $password);

    if ($user instanceof User) {
        // Si el login fue exitoso
        $_SESSION["user_id"] = $user->getIdUser();
        $_SESSION["user_name"] = $user->getName();
        $_SESSION["user_role"] = $user->getIdRole();  // Aquí puedes gestionar el rol del usuario
        header("Location: ../../HTML/dashboard.php");  // Redirigir al dashboard
        exit();
    } else {
        // Si hubo un error en el login
        header("Location: ../../index.php?error=" . urlencode($user));
        exit();
    }
}
