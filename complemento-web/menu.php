<?php
session_start();
$local = "http://localhost/PROYECTO4B-1/complemento-web/";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= $local?>css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $local?>css/menu.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $local?>css/fontawesome/fontawesome.css">
    <?php echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">' ?>
    <link rel="stylesheet" href="<?= $local?>css/fontawesome/solid.css">
    <script src="<?= $local?>js/fontawesome/solid.js"></script>
</head>
<body>
    <div id="menu">
        <div id="menu-logo">
            <label id="label-logo">U-Store </label>
            <img src="<?= $local?>images/logo.png" width="55px" height="55px">
        </div>
        <div id="options">
            <a href="<?= $local?>index.php" class="menu-item"><i class="bi bi-shop"></i> Tienda</a>
            <a href="<?= $local?>funcionalidades/favoritos/lista_deseados.php" class="menu-item"><i class="bi bi-heart"></i> Lista de Deseados</a>
        </div>
        <div id="user">
            <a href="<?= $local?>funcionalidades/carrito/carrito.php" class="menu-item" style="margin-right: 20px;"><i class="bi bi-cart"></i> Carrito</a>
            <a href="<?= $local?>funcionalidades/perfil/perfil.php" class="menu-item" style="margin-right: 20px;"><i class="bi bi-person-circle"></i> Perfil</a>
            <a href="<?= $local?>login/logout.php" class="menu-item"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>