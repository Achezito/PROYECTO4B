<?php
include("menu.php");

if (!isset($_GET['id_order']) || empty($_GET['id_order'])) {
    echo "<script>alert('No se especificó una orden válida.'); window.location.href='index.php';</script>";
    exit;
}

$id_order = intval($_GET['id_order']);

$URL_order = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?id_order=".$id_order;
$URL_supply = "http://localhost/PROYECTO4B-1/phpfiles/react/supply_api.php?id_order=".$id_order;

$file_order = file_get_contents($URL_order);
$file_supply = file_get_contents($URL_supply);

$supplies = json_decode($file_supply, true); 
$order = json_decode($file_order, true);


// Validar que la respuesta sea un array y contenga datos
if (!$order || !isset($order[0])) {
    echo "<script>alert('No se encontró la orden.'); window.location.href='index.php';</script>";
    exit;
}

// Extraer la orden correctamente
$order = $order[0];

// Definir clases de Bootstrap para los estados
$statusClass = ($order['id_status'] == 'Pendiente') ? 'danger' : 'success';
$confirmationClass = ($order['confirmation'] == 'Confirmada') ? 'success' : 'secondary';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Orden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div id="body" style="background-color: white;"><br>
    <div class="container-order p-4" style="border: white;">
        <?php 
print_r($supplies);?>
        <h2 class="text-center mb-4">Detalles de la Orden #<?= htmlspecialchars($order['id_order']) ?></h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Estado:</strong> 
                    <span class="badge bg-<?= $statusClass ?>">
                        <?= htmlspecialchars($order['id_status']) ?>
                    </span>
                </p>
                <p><strong>Cantidad De Suministros:</strong> <?= htmlspecialchars($order['supply_quantity']) ?></p>
                <p><strong>Confirmación:</strong> 
                    <span class="badge bg-<?= $confirmationClass ?>">
                        <?= htmlspecialchars($order['confirmation']) ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Fecha de Creación:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                <p><strong>Última Actualización:</strong> <?= htmlspecialchars($order['updated_at']) ?></p>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Regresar</a>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
