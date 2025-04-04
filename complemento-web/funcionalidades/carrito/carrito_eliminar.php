<?php
session_start();

$electronico = $_POST['producto-carrito'] ?? '';

if ($electronico != '') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $valor = array_search($electronico, $_SESSION['carrito']); // Busca el producto en el array

        // Elimina el producto del array
        unset($_SESSION['carrito'][$valor]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el array
?>
<script> // Manda mensaje de que se elimino el producto del carrtio y redirecciona al carrito
    alert( "<?= "Se elimno el producto '".$electronico["Model"]."'" ?>" );
    window.location.href = "carrito.php";
</script>
<?php
    }
}
?>
