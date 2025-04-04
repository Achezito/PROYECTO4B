<?php
session_start();
$_SESSION['carrito'] = []; // vacia el array del carrito
?>

<script> // Manda mensaje de que se elimino el producto del carrtio y redirecciona al carrito
    alert( "<?= "Se Realizo El Pago" ?>" );
    window.location.href = "carrito.php";
</script>