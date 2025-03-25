<?php
require_once '../../phpfiles/modals/orders.php'; // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_order'])) {
    $response = Order::delete($_POST['id_order']);

    if ($response === "Orden eliminada correctamente") {
        // Redirigir a la página anterior si está disponible
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: orders_list.php"); // Página predeterminada si no hay HTTP_REFERER
        }
        exit;
    } else {
        // Redirigir con mensaje de error
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode($response));
        } else {
            header("Location: orders_list.php?error=" . urlencode($response));
        }
        exit;
    }
} else {
    // Redirigir si no se recibió el ID de la orden
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode("ID de orden no proporcionado"));
    } else {
        header("Location: orders_list.php?error=" . urlencode("ID de orden no proporcionado"));
    }
    exit;
}
?>
