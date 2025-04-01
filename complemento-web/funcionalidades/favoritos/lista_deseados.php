<?php
include("../../menu.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Deseados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .producto-card {
            transition: transform 0.3s ease-in-out;
        }
        .producto-card:hover {
            transform: scale(1.05);
        }

        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
        }
        .card img {
            object-fit: cover;
            height: 250px;
        }
        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .botons{
            display: flex;
            justify-content: space-evenly;
        }

        .ver{
            width: 60%;
            background-color: skyblue;
        }
        .eliminar{
            width: 30%;
        }
    </style>
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
                    ?>
                <div class="col-md-4 mb-4">
                    <div class="card producto-card">
                        <img src="<?= $producto['Thumbnail']?>" class="card-img-top" onerror="this.onerror=null;this.src='../../images/laptop.png';">
                            <div class="card-body">
                            <h5 class="card-title text-dark"><?= $producto['Model'] ?> </h5>
                            <p class="card-text"><strong>Categoría:</strong> <?=  $producto['Category'] ?> </p>
                            <div class="botons">
                                <button href="#" class="btn ver">Ver</button>

                                    <button href="#" class="btn btn-danger eliminar ">Eliminar</button>
                            
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            }
            ?>
        </div>
    </div>
</body>
</html>
