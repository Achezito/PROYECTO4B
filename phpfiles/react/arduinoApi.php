<?php
// Configuración de cabeceras para permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verifica si se recibieron datos en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // Extrae los datos enviados por el Arduino
    $distancia = isset($data['distancia']) ? $data['distancia'] : null;
    $cajas = isset($data['cajas']) ? $data['cajas'] : null;

    // Aquí puedes procesar los datos, por ejemplo, almacenarlos en una base de datos
    $response = [
        "status" => "success",
        "message" => "Datos recibidos correctamente",
        "distancia" => $distancia,
        "cajas" => $cajas
    ];

    // Devuelve la respuesta en formato JSON
    echo json_encode($response);
} else {
    // Si no se recibieron datos, devuelve un error
    $response = [
        "status" => "error",
        "message" => "No se recibieron datos"
    ];

    echo json_encode($response);
}
?>