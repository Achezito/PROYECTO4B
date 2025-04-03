<?php
// filepath: c:\xampp\htdocs\PROYECTO4B-1\phpfiles\react\arduinoApi.php

// Habilitar CORS si es necesario
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Archivo para almacenar los datos temporalmente
$dataFile = "arduinoData.json";

// Manejar solicitudes POST (datos enviados por el Arduino)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el cuerpo de la solicitud
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Validar los datos recibidos
    if (isset($data['distancia']) && isset($data['cajas'])) {
        // Guardar los datos en un archivo JSON
        file_put_contents($dataFile, json_encode($data));
        echo json_encode(["status" => "success", "message" => "Datos guardados correctamente."]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Datos inválidos."]);
    }
    exit;
}

// Manejar solicitudes GET (datos solicitados por la aplicación React Native)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($dataFile)) {
        // Leer los datos del archivo JSON
        $data = file_get_contents($dataFile);
        echo $data;
    } else {
        echo json_encode(["distancia" => 0, "cajas" => 0]); // Valores predeterminados si no hay datos
    }
    exit;
}

// Si el método no es POST ni GET
http_response_code(405);
echo json_encode(["status" => "error", "message" => "Método no permitido."]);