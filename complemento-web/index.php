<?php
include("menu.php");

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiId = "67e4a2198ef8a6bad72e7e06";
$apiKey = "19dd28ac-0bc1-4dc3-bd69-e80ae90eb358";
$client = new Client();

$categoriaSeleccionada = $_GET['categoria'] ?? 'Laptops';
$busqueda = $_GET['busqueda'] ?? '';
$ordenar = $_GET['ordenar'] ?? '';
$page = isset($_GET['page']) ? max(0, (int)$_GET['page']) : 0;

$productos = [];
$totalPages = 1;

try {
    $url = "https://api.techspecs.io/v5/products/search?category=" . urlencode($categoriaSeleccionada) . "&query=" . urlencode($busqueda) . "&keepCasing=true&page=$page&size=20";
    
    $response = $client->request('GET', $url, [
        'headers' => [
            'X-API-ID' => $apiId,
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]
    ]);
    
    $data = json_decode($response->getBody(), true);
    $productos = $data['data'] ?? [];
    $totalPages = $data['total_pages'] ?? 1;
} catch (RequestException $e) {
    echo "Error al obtener productos: " . $e->getMessage();
}


$categoriasJson = 'categories.json';
$categoriasData = json_decode(file_get_contents($categoriasJson), true);
$categorias = $categoriasData['data'] ?? [];

// Ordenar productos alfabéticamente si está activado
if ($ordenar === 'asc') {
    usort($productos, function ($a, $b) {
        return strcmp($a['Product']['Model'] ?? '', $b['Product']['Model'] ?? '');
    });
} elseif ($ordenar === 'desc') {
    usort($productos, function ($a, $b) {
        return strcmp($b['Product']['Model'] ?? '', $a['Product']['Model'] ?? '');
    });
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogos</title>
    <link href="css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script>
        function toggleOrder() {
            let urlParams = new URLSearchParams(window.location.search);
            let currentOrder = urlParams.get('ordenar');
            if (currentOrder === 'asc') {
                urlParams.set('ordenar', 'desc');
            } else {
                urlParams.set('ordenar', 'asc');
            }
            window.location.search = urlParams.toString();
        }
    </script>
</head>
<body style="display: flex; flex-direction:column;">
    <div class="container mt-4 mb-4">
        <br><br><br>
        <form method="GET" class="mb-4">
            <div class="row" style=" justify-content: flex-end;">
                <div class="col-md-2">
                    <label>Filtrar por categoría:</label>
                    <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria ?>" <?= $categoria == $categoriaSeleccionada ? 'selected' : '' ?>>
                                <?= $categoria ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="busqueda">Buscar producto:</label>
                    <input type="text" name="busqueda" id="busqueda" class="form-control" value="<?= $busqueda ?>" placeholder="Buscar por modelo">
                </div>
            </div>
        </form>
        <button class="btn btn-secondary mb-4" onclick="toggleOrder()">Ordenar por nombre: <?= $ordenar === 'asc' ? 'A-Z' : 'Z-A' ?></button>
        <div class="row">
            <?php if (empty($productos)): ?>
                <p class="text-center">No hay productos disponibles.</p>
            <?php else: ?>
                <?php foreach ($productos as $item): ?>
                <?php
                    $modelo = $item['Product']['Model'] ?? 'Modelo desconocido';
                    $thumbnail = isset($item['Product']['Thumbnail']) && $item['Product']['Thumbnail'] != 'Please upgrade your plan to get access to product images' ? $item['Product']['Thumbnail'] : '/images/laptop.png';
                    $precio = rand(4999, 24999);

                    $producto = [
                        'id' => $item['Product']['id'],
                        'Category' => $item['Product']['Category'],
                        'Model' => $modelo,
                        'Thumbnail' => $thumbnail ];
    
                ?>
                    <div class="col-md-4 mb-4">
                        <a style="text-decoration: none; color: black;" href="funcionalidades/detalles/details.php?id=<?=$item['Product']['id']?>&thumbnail=<?=$thumbnail?>&precio=<?=$precio?>">
                        <div class="card shadow-sm">
                            <img src="<?= $thumbnail ?>" alt="Imagen del producto" onerror="this.onerror=null;this.src='images/laptop.png';">
                            <div class="card-body">
                                <h5 class="card-title"><?= $modelo ?></h5>
                                <p class="card-text"><strong>Marca:</strong> <?= $item['Product']['Brand'] ?? 'N/A' ?></p>
                                <p class="card-text"><strong>Precio:</strong> <?= "$".number_format($precio, 2) ?></p>
                                <div class="center" style="justify-content: space-evenly; font-size: 16pt;">
                                    <form action="funcionalidades/carrito/carrito_agregar.php" method="POST">
                                        <input type="hidden" name="producto-carrito[id]" value="<?= $item['Product']['id'] ?>">
                                        <input type="hidden" name="producto-carrito[Category]" value="<?= $item['Product']['Category'] ?>">
                                        <input type="hidden" name="producto-carrito[Model]" value="<?= $modelo ?>">
                                        <input type="hidden" name="producto-carrito[Thumbnail]" value="<?= $thumbnail ?>">
                                        <input type="hidden" name="producto-carrito[Price]" value="<?= $precio ?>">
                                        
                                        <button type="submit" style="border-radius: 17px; padding: 14px; color:white; background:rgb(0, 140, 255); border: none;">🛒 Añadir al Carrito</button>
                                    </form>

                                    <form action="funcionalidades/favoritos/lista.php" method="POST">
                                        <input type="hidden" name="producto-deseado[id]" value="<?= $item['Product']['id'] ?>">
                                        <input type="hidden" name="producto-deseado[Category]" value="<?= $item['Product']['Category'] ?>">
                                        <input type="hidden" name="producto-deseado[Model]" value="<?= $modelo ?>">
                                        <input type="hidden" name="producto-deseado[Thumbnail]" value="<?= $thumbnail ?>">
                                        <button type="submit" style="background: none; border:none; font-size:27pt;" >
                                            <?php
                                                if (array_search($producto, $_SESSION['deseados']) === false) {
                                                    echo "<i class='fas fa-plus-circle' ></i>";
                                                } else {
                                                    echo "<i class='fas fa-bookmark' style='color: red;'></i> ";
                                                }
                                                ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>


    <nav>
            <ul class="pagination justify-content-center mt-4">
                <?php if ($page > 0): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&categoria=<?= urlencode($categoriaSeleccionada) ?>&busqueda=<?= urlencode($busqueda) ?>&ordenar=<?= urlencode($ordenar) ?>">Anterior</a></li>
                <?php endif; ?>
                <li class="page-item"><span class="page-link">Página <?= $page + 1 ?> de <?= $totalPages ?></span></li>
                <?php if ($page < $totalPages - 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&categoria=<?= urlencode($categoriaSeleccionada) ?>&busqueda=<?= urlencode($busqueda) ?>&ordenar=<?= urlencode($ordenar) ?>">Siguiente</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    <?php include("contactanos.php"); ?>
</body>
</html>
