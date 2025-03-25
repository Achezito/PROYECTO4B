<?php
include("menu.php");

$file = file_get_contents("http://localhost/PROYECTO4B-1/phpfiles/react/supplier_api.php");
$data = json_decode($file, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome/fontawesome.css" rel="stylesheet">
    <link href="css/fontawesome/solid.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body style="background-color: rgb(223, 223, 223);">

<div id="body" class="container mt-4">
    <h2 class="text-center mb-4">Lista de Proveedores</h2>
    <div class="row">
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $supplier) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-industry"></i> <?= htmlspecialchars($supplier['name']) ?>
                            </h5>
                            <p class="card-text"><i class="fas fa-envelope"></i> <?= htmlspecialchars($supplier['contact_info']) ?></p>
                            <p class="card-text"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($supplier['address']) ?></p>
                            <p class="text-muted"><small>Registrado el: <?= htmlspecialchars($supplier['created_at']) ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center">No hay proveedores disponibles.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/fontawesome/solid.js"></script>

</body>
</html>
