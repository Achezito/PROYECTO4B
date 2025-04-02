<?php
session_start();

$electronico = $_POST['producto-carrito'] ?? '';

if ($electronico != '') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['carrito'][] = $electronico;
            ?>
            <script>
                alert( "<?= "El Producto '".$electronico["Model"]."' se ha añadido al carrito" ?>" );
                window.location.href = "http://localhost/PROYECTO4B-1/complemento-web/funcionalidades/carrito/carrito.php";
            </script>
    <?php }
    }


?>