<?php
include("menu.php");

$URL = "productos.json";
$file = file_get_contents($URL);
$data = json_decode($file, true);

$productos = $data['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link href="css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Lista de Productos</h2>
        <div class="row">
            <?php foreach ($productos as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php 
                        $thumbnail = isset($item['Product']['Thumbnail']) && $item['Product']['Thumbnail'] != 'Please upgrade your plan to get access to product images' ? htmlspecialchars($item['Product']['Thumbnail']) 
                            : '/images/laptop.png';
                        ?>
                        <img src="<?= $thumbnail ?>" class="card-img-top" alt="Imagen del producto" onerror="this.onerror=null;this.src='images/laptop.png';">
                        <div class="card-body">
                            <h5 class="card-title"> <?= htmlspecialchars($item['Product']['Model'] ?? 'Modelo desconocido') ?> </h5>
                            <p class="card-text"><strong>Modelo:</strong> <?= htmlspecialchars($item['Product']['Model'] ?? 'N/A') ?></p>
                            <p class="card-text"><strong>Marca:</strong> <?= htmlspecialchars($item['Product']['Brand'] ?? 'N/A') ?></p>
                            <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($item['Product']['Category'] ?? 'N/A') ?></p>
                            <?php /*
                                <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($item['Product']['id'] ?? 'N/A') ?></p>
                                */
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
