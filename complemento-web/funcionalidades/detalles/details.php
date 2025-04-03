<?php
include("../../menu.php");

$id = $_GET['id'];
$imagen = $_GET['thumbnail'];
$precio = $_GET['precio'];

$productJson = 'product_productid.json';
$productData = json_decode(file_get_contents($productJson), true);
$product = $productData['data']['Product'] ?? [];
$design = $productData['data']['Design'] ?? [];
$display = $productData['data']['Display'] ?? [];
$ports = $productData['data']['Ports'] ?? [];
$camera = $productData['data']['Camera'] ?? [];
$software = $productData['data']['Inside']['Software'] ?? [];
$battery = $productData['data']['Inside']['Battery'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link href="../../css/details.css" rel="stylesheet">
</head>
<body style="display: flex; flex-direction:column;">
    <br><br><br><br>
    <div class="containdor" style="width: 80%; ">
        <h1 class="text-center mb-4"><?= $product['Model'] ?? 'Modelo desconocido'; ?></h1>

        <div class="row row-cols-md-2">
            <div class="col" style="display: flex; justify-content: center; align-items: center; "  >
                <img src="<?= $imagen; ?>" alt="Imagen del producto" style="width: 60%;" onerror="this.onerror=null;this.src='../../images/laptop.png';">
            </div>
            <div class="col">
                <div class="section-title">Información del Producto</div>
                <table class="table">
                    <tr><th>Modelo</th><td><?= $product['Model'] ?? 'Modelo desconocido'; ?></td></tr>
                    <tr><th>Marca</th><td><?= $product['Brand'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Categoría</th><td><?= $product['Category'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Sistema Operativo</th><td><?= $software['Operating System Version'] ?? 'N/A'; ?></td></tr>
                </table>

                <div class="section-title"><label style="font-size: 20pt;"><?= "\$MXN " . number_format($precio, 2) ?? 'N/A'; ?></label></div>

            <div style="display: flex; flex-direction:row; margin-top:3px; justify-content:center; align-items:center;">
                <form action="../carrito/carrito_agregar.php" method="POST" style="width: 85%;">
                    <input type="hidden" name="producto-carrito[id]" value="<?=$id?>">
                    <input type="hidden" name="producto-carrito[Category]" value="<?=$product['Category']?>">
                    <input type="hidden" name="producto-carrito[Model]" value="<?=$product['Model']?>">
                    <input type="hidden" name="producto-carrito[Thumbnail]" value="<?=$imagen?>">
                    <input type="hidden" name="producto-carrito[Price]" value="<?=$precio?>">

                    <button type="submit" class="btn-add-cart" >Añadir al Carrito</button>
                </form>
                <form action="../favoritos/lista.php" method="POST">
                    <input type="hidden" name="producto-deseado[id]" value="<?=$id?>">
                    <input type="hidden" name="producto-deseado[Category]" value="<?=$product['Category']?>">
                    <input type="hidden" name="producto-deseado[Model]" value="<?=$product['Model']?>">
                    <input type="hidden" name="producto-deseado[Thumbnail]" value="<?=$imagen?>">
                    
                    <button type="submit" style="background: none; border:none; font-size:27pt;" >
                    <?php
                    $producto = [ 'id' => $id, 'Category' => $product['Category'], 'Model' => $product['Model'], 'Thumbnail' => $imagen ];

                    if (array_search($producto, $_SESSION['deseados']) === false) {
                        echo "<i class='fas fa-plus-circle' ></i>";
                    } else {
                        echo "<i class='fas fa-bookmark' style='color: red;'></i>";
                    }
                    ?>
                    </button>
                </form>
            </div>
        </div>
    <div class="col">
    <div class="section-title">Diseño</div>
        <table class="table">
            <tr><th>Tipo</th><td><?= $design['Body']['Type'] ?? 'N/A'; ?></td></tr>
            <tr><th>Color</th><td><?= $design['Body']['Colors'] ?? 'N/A'; ?></td></tr>
            <tr><th>Peso</th><td><?= $design['Body']['Weight'] ?? 'N/A'; ?></td></tr>
        </table>
    </div>
    <div class="col">
        <table class="table">
            <div class="section-title"> </div>
            <tr><th>Dimension</th><td><?= $display['Diagonal'] ?? 'N/A'; ?></td></tr>
            <tr><th>Resolución</th><td><?= $display['Resolution (H x W)'] ?? 'N/A'; ?></td></tr>
            <tr><th>Relación de aspecto</th><td><?= $display['Aspect Ratio'] ?? 'N/A'; ?></td></tr>
        </table>
    </div>
</div>
<a href="../componentes/details_component.php?id=<?=$id?>" class="btn-consultar-componentes">Consultar Componentes</a>
</div>

<?php
include("../resenas/resenas.php");

include("../../contactanos.php");
?>

    <br><br>
</body>
</html>
