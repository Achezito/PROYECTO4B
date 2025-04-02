<?php
$url = 'https://api.mockaroo.com/api/c0c5aaa0?count=6&key=39d3ed90'; // Cambia count a 6

// Obtener datos JSON desde Mockaroo
$json_data = @file_get_contents($url);

if ($json_data === false) {
    die('Error al obtener los datos de la API.');
}

// Decodificar JSON en un array asociativo
$reseñas = json_decode($json_data, true);

if ($reseñas === null) {
    die('Error al decodificar los datos JSON.');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas de Productos</title>
    <!-- Agregar el CDN de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reseña { margin-bottom: 15px; }
        .rating { color: #FFD700; font-size: 16px; }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Reseñas de Productos Electrónicos</h2>
    <div class="row">
        <?php foreach ($reseñas as $reseña): ?>
            <div class="col-md-12 reseña">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($reseña['review_title']) ?></h5>
                        <p class="card-text">
                            <strong>Usuario:</strong> <?= htmlspecialchars($reseña['user_name']) ?><br>
                            <strong>Calificación:</strong> 
                            <span class="rating"><?= str_repeat("★", $reseña['rating']) . str_repeat("☆", 5 - $reseña['rating']) ?></span> (<?= $reseña['rating'] ?> estrellas)<br>
                            <strong>Fecha de Reseña:</strong> <?= $reseña['review_date'] ?><br><br>
                            <strong>Comentario:</strong><br>
                            <?= nl2br(htmlspecialchars($reseña['review_text'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


</body>
</html>
