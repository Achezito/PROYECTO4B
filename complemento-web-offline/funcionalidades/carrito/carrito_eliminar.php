<?php
session_start();

$electronico = $_POST['producto-carrito'] ?? '';

if ($electronico != '') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $valor = array_search($electronico, $_SESSION['carrito']); // verifica si el producto ya esta en la lista

 // Si ya está en la lista, lo elimina
            unset($_SESSION['carrito'][$valor]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el array
            print_r($_SESSION['carrito']);?>
            <script>
                alert( "<?= "Se elimno el producto '".$electronico["Model"]."'" ?>" );
                window.location.href = "carrito.php";
            </script>
<?php }
}

echo "<pre>";
print_r($_SESSION['carrito']);
echo "</pre>";

?>
