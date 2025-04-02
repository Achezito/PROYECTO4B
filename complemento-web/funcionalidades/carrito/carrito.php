<?php
include("../../menu.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="http://localhost/PROYECTO4B-1/complemento-web/css/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/carrito.css">

</head>
<body>
    <div class="container mt-5">
        <br><br><br>
        <h1 class="cart-header">Tu Carrito de Compras</h1>
        
        <?php
        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            echo '<p class="text-center text-danger">Tu carrito está vacío.</p>';
        } else {
            foreach ($_SESSION['carrito'] as $producto) { ?>
                <div class="cart-item">
                    <img src="<?= $producto['Thumbnail'] ?>" onerror="this.onerror=null;this.src='../../images/laptop.png';">
                    <div class="cart-details">
                        <h5 class="text-dark"><?= $producto['Model'] ?></h5>
                        <p><strong>Precio:</strong> Mex$ <?= $producto['Price'] ?></p>
                    </div>
                    <div class="cart-actions">
                        <form action="carrito_eliminar.php" method="POST">
                            <input type="hidden" name="producto-carrito[id]" value="<?= $producto['id'] ?>">
                            <input type="hidden" name="producto-carrito[Category]" value="<?= $producto['Category'] ?>">
                            <input type="hidden" name="producto-carrito[Model]" value="<?= $producto['Category'] ?>">
                            <input type="hidden" name="producto-carrito[Thumbnail]" value="<?= $producto['Category'] ?>">
                            <input type="hidden" name="producto-carrito[Price]" value="<?= $producto['Category'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php }
        }
        ?>
        
        <?php
        if (!empty($_SESSION['carrito'])) { ?>
        <div class="total-box">
            <p>Total estimado: <span id="total">Mex$ <?= array_sum(array_column($_SESSION['carrito'], 'Price')) ?></span></p>
            <form action="simular_pago.php">
            <button type="submit" class="checkout-btn">Continuar al Pago</button>
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>
