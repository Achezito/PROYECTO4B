
<?php
$url = 'https://api.mockaroo.com/api/c0c5aaa0?count=6&key=39d3ed90';

$reseña_data = file_get_contents($url);

$reseñas = json_decode($reseña_data, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link href="../../css/details.css" rel="stylesheet">
</head>
<body style="display: flex; flex-direction:column;">

    <?php  if ($reseñas) { ?>

    <div class="containdor" style="width: 80%; ">
        <div class="section-title">Reseñas</div>
            <div class="row">
            <?php foreach ($reseñas as $reseña): ?>
                <div class="col-md-12 reseña" style="margin-bottom: 15px;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($reseña['review_title']) ?></h5>
                            <p class="card-text">
                                <strong>Usuario:</strong> <?= htmlspecialchars($reseña['user_name']) ?><br>
                                <strong>Calificación:</strong> 
                                <span class="rating" style="color: #FFD700; font-size: 16px;"><?= str_repeat("★", $reseña['rating']) . str_repeat("☆", 5 - $reseña['rating']) ?></span> (<?= $reseña['rating'] ?> estrellas)<br>
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

    <?php } ?>

<br><br>
</body>
</html>
