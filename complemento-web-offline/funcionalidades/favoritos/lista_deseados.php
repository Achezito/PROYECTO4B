<?php
include("../../menu.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Deseados</title>
    <link rel="stylesheet" href="http://localhost/PROYECTO4B-1/complemento-web/css/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/deseados.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5"><br><br>
        <div class="page-header">
            <h1 class="text-center mb-4 text-primary">Mi Lista De Deseados</h1>
        </div>
        <div class="row">
            <?php

            if (!isset($_SESSION['deseados']) || empty($_SESSION['deseados'])) {
                echo '<p class="text-center text-danger">No hay productos en la lista de deseados.</p>';
            } else {
                foreach ($_SESSION['deseados'] as $producto) {
                    $producto_actual = [
                        'id' => $producto['id'],
                        'Category' => $producto['Category'],
                        'Model' => $producto['Model'],
                        'Thumbnail' => $producto['Thumbnail']
                    ];
                    $precio = rand(4999, 24999);
                    ?>
                <div class="col-md-4 mb-4">
                    <a style="text-decoration: none; color: black;" href="../detalles/details.php?id=<?=$producto['id']?>&thumbnail=<?=$producto['Thumbnail']?>&precio=<?=$precio?>">
                    <div class="card producto-card">
                        <img class="imagen" src="<?= $producto['Thumbnail']?>" onerror="this.onerror=null;this.src='../../images/laptop.png';">
                            <div class="card-body">
                            <h5 class="card-title text-dark"><?= $producto['Model'] ?> </h5>
                            <p class="card-text"><label style="font-size: 14pt;">MXN</label> <strong style="font-size: 17pt;">$<?=  $precio ?></strong> </p>

                            <div class="center formul">
                                    <form action="../carrito/carrito_agregar.php" method="POST">
                                        <input type="hidden" name="producto-carrito[id]" value="<?= $producto['id'] ?>">
                                        <input type="hidden" name="producto-carrito[Category]" value="<?= $producto['Category'] ?>">
                                        <input type="hidden" name="producto-carrito[Model]" value="<?= $producto['Model'] ?>">
                                        <input type="hidden" name="producto-carrito[Thumbnail]" value="<?= $producto['Thumbnail'] ?>">
                                        <input type="hidden" name="producto-carrito[Price]" value="<?= $precio ?>">
                                        <button type="submit" class="añadir">🛒 Añadir al carrito</button>
                                    </form>

                                    <form action="lista.php" method="POST">
                                        <input type="hidden" name="producto-deseado[id]" value="<?= $producto['id'] ?>">
                                        <input type="hidden" name="producto-deseado[Category]" value="<?= $producto['Category'] ?>">
                                        <input type="hidden" name="producto-deseado[Model]" value="<?= $producto['Model'] ?>">
                                        <input type="hidden" name="producto-deseado[Thumbnail]" value="<?= $producto['Thumbnail'] ?>">
                                        <button type="submit" class="eliminar" >
                                            eliminar
                                        </button>
                                    </form>
                                </div>
                        </div>
                    </div>
                    </a>
                </div>
                
            <?php }
            }
            ?>
        </div>
    </div>
</body>
</html>
