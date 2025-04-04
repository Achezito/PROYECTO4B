<?php
session_start();
$electronico = $_POST['producto-carrito'] ?? ''; // Se obtiene el parametro enviado con POST

if ($electronico != '') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['carrito'][] = $electronico; //añade al array la informacion del producto
        ?>
        <script>// Manda mensaje de que se añadio el producto al carrtio y redirecciona al carrito
            alert( "<?= "El Producto '".$electronico["Model"]."' se ha añadido al carrito" ?>" );
            window.location.href = "http://localhost/PROYECTO4B-1/complemento-web/funcionalidades/carrito/carrito.php";
        </script>
<?php }
}

?>