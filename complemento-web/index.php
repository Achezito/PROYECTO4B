<?php
include("menu.php");

$URL = "productos.json";
$file = file_get_contents($URL);
$data = json_decode($file, true);

$productos = $data['data'] ?? [];

$categoriasJson = 'categories.json';
$categoriasData = json_decode(file_get_contents($categoriasJson), true);
$categorias = $categoriasData['data'] ?? [];

$categoriaSeleccionada = $_GET['categoria'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';
$ordenar = $_GET['ordenar'] ?? '';

// Filtrar productos por categoría
if (!empty($categoriaSeleccionada)) {
    $productos = array_filter($productos, function ($item) use ($categoriaSeleccionada) {
        return isset($item['Product']['Category']) && $item['Product']['Category'] === $categoriaSeleccionada;
    });
}

// Filtrar productos por búsqueda
if (!empty($busqueda)) {
    $productos = array_filter($productos, function ($item) use ($busqueda) {
        return stripos($item['Product']['Model'] ?? '', $busqueda) !== false;
    });
}

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
    <title>Lista de Productos</title>
    <link href="css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

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

    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
        }
        .card img {
            object-fit: cover;
            height: 250px;
        }
        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: scale(1.02);
        }

    </style>
</head>
<body>
    <div class="container mt-4 mb-4">
        <br><br><br>
        <form method="GET" class="mb-4">
            <div class="row" style=" justify-content: flex-end;">
            
                <div class="col-md-2">
                    <label>Filtrar por categoría:</label>
                    <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
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
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <?php
                            $modelo = $item['Product']['Model'] ?? 'Modelo desconocido';
                            $thumbnail = isset($item['Product']['Thumbnail']) && $item['Product']['Thumbnail'] != 'Please upgrade your plan to get access to product images'
                                ? $item['Product']['Thumbnail'] : '/images/laptop.png';
                            ?>
                            <img src="<?= $thumbnail ?>" class="card-img-top" alt="Imagen del producto" onerror="this.onerror=null;this.src='images/laptop.png';">
                            <div class="card-body">
                                <h5 class="card-title"><?= $modelo ?></h5>
                                <p class="card-text"><strong>Marca:</strong> <?= $item['Product']['Brand'] ?? 'N/A' ?></p>

                                <?php
                                $producto = [
                                    'id' => $item['Product']['id'],
                                    'Category' => $item['Product']['Category'],
                                    'Model' => $modelo,
                                    'Thumbnail' => $thumbnail ];
                                ?>

                                
                                
                                <div class="center" style="justify-content: space-evenly; font-size: 16pt;">
                                    <form action="funcionalidades/carrito/carrito_agregar.php" method="POST">
                                        <input type="hidden" name="producto-carrito[id]" value="<?= $item['Product']['id'] ?>">
                                        <input type="hidden" name="producto-carrito[Category]" value="<?= $item['Product']['Category'] ?>">
                                        <input type="hidden" name="producto-carrito[Model]" value="<?= $modelo ?>">
                                        <input type="hidden" name="producto-carrito[Thumbnail]" value="<?= $thumbnail ?>">
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
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
