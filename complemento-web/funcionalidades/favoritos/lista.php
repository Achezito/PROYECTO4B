<?php
session_start();

$electronico = $_POST['producto-deseado'] ?? '';

if ($electronico != '') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $valor = array_search($electronico, $_SESSION['deseados']); // verifica si el producto ya esta en la lista

        if ($valor === false) { // Si no está en la lista, lo añade
            $_SESSION['deseados'][] = $electronico;
            print_r($_SESSION['deseados']);  ?>
            <script>
                alert( "<?= "Producto '".$electronico["Model"]."' añadido a la lista de deseado" ?>" );
                window.location.href = "lista_deseados.php";
            </script>
    <?php } else { // Si ya está en la lista, lo elimina
            unset($_SESSION['deseados'][$valor]);
            $_SESSION['deseados'] = array_values($_SESSION['deseados']); // Reindexar el array 
            print_r($_SESSION['deseados']);?>
            <script>
                alert( "<?= "Se elimno el producto '".$electronico["Model"]."'" ?>" );
                window.location.href = "http://localhost/PROYECTO4B-1/complemento-web/index.php";
            </script>
<?php }
    }
}


echo "<pre>";
print_r($_SESSION['deseados']);
echo "</pre>";

?>
