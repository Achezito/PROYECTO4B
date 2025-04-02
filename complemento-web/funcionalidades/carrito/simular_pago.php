<?php
session_start();

$_SESSION['carrito'] = [];
?>

<script>
    alert( "<?= "Se Realizo El Pago" ?>" );
    window.location.href = "carrito.php";
</script>