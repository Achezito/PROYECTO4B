<?php
session_start();

$_SESSION['access_token'] = $_GET['access_token'];
$_SESSION['id_token'] = $_GET['id_token'];
$_SESSION['usuario'] = parseJwt($_SESSION['id_token']);
$_SESSION['carrito'] = [];
$_SESSION['deseados'] = [];

print_r($_SESSION['usuario']);

function parseJwt($token) { // Función para decodificar el ID Token
$parts = explode('.', $token);
$base64 = $parts[1];
return json_decode(base64_decode($base64), true);
}

header("Location: ../perfil.php");
exit();
?>
