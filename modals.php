<?php
require_once ('phpfiles/modals/material_hardware.php');
require_once ('phpfiles/modals/warehouse.php');
require_once ('phpfiles/modals/category.php');
require_once ('phpfiles/react/category_api.php');

//$procesadores = hardware::getHardware("processor");
//$warehouses = Warehouse::getWarehouse();

$data = Category::getAllCategories();

//print_r($data);

echo json_encode($data);


/*
?>

<?php foreach($procesadores as $as) { ?>
        <tr>
            <td><?php echo $as->getIdMaterial(); ?></td>
            <td><?php echo $as->getModel(); ?></td>
            <td><?php echo $as->getSpeed(); ?></td>
            <td><?php echo $as->getCores(); ?></td>
            <td><?php echo $as->getThreads(); ?></td>
            <td><?php echo $as->getCacheMemory(); ?></td>
            <td><?php echo $as->getFrecuency(); ?></td>
            <td><?php echo $as->getPowerConsumption(); ?></td>
            <td><?php echo $as->getBrand(); ?></td>
        </tr>
    <?php } ?>
</table>

<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Capacidad</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($warehouses as $warehouse) { ?>
                <tr>
                    <td><?php echo $warehouse->getIdWarehouse(); ?></td>
                    <td><?php echo $warehouse->getName(); ?></td>
                    <td><?php echo $warehouse->getLocation(); ?></td>
                    <td><?php echo $warehouse->getCapacity(); ?></td>
                    <td><?php echo $warehouse->getCreatedAt(); ?></td>
                    <td><?php echo $warehouse->getUpdatedAt(); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Inventario</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #f4f4f4;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    
</body>
</html>*/
?>
