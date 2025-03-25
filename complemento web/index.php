<?php
include("menu.php");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Órdenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome/fontawesome.css">
    <link rel="stylesheet" href="css/fontawesome/solid.css">
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/fontawesome/solid.js"></script>
</head>
<body>

<div id="body" class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Órdenes</h2>
        <div class="filtros" >
            <a href="index.php?todas=True">
                <label class="filtro  <?= isset($_GET["todas"]) && $_GET["todas"] != "" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Todas </label>
            </a>
            <a href="index.php?SinConfirmar=True">
                <label class="filtro  <?= isset($_GET["SinConfirmar"]) && $_GET["SinConfirmar"] != "" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Sin Confirmar </label>
            </a>
            <a href="index.php?confirmadas=True">
                <label class="filtro  <?= isset($_GET["confirmadas"]) && $_GET["confirmadas"] != "" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Confirmadas </label>
            </a>
            <a href="index.php?pendientes=True">
                <label class="filtro <?= isset($_GET["pendientes"]) && $_GET["pendientes"] != "" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Pendientes </label>
            </a>
            <a href="index.php?entregadas=True">
                <label class="filtro <?= isset($_GET["entregadas"]) && $_GET["entregadas"] != "" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Entregadas </label>
            </a>
                <div class="fecha-div">
                    <label class="fecha-1 <?= isset($_GET["fecha"]) && $_GET["fecha"] == "DESC" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"> Por Fecha </label>
            
            <a href="index.php?fecha=DESC">
                    <label class="fecha-2 <?= isset($_GET["fecha"]) && $_GET["fecha"] == "DESC"  ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"><i class="fas fa-arrow-down"></i></label>
            </a>
            <a href="index.php?fecha=ASC">
                    <label class="fecha-3 <?= isset($_GET["fecha"]) && $_GET["fecha"] == "ASC" ? "filtro-seleccionado" : "filtro-no-seleccionado" ; ?>"><i class="fas fa-arrow-up"></i></label>
            </a>
                </div>
            
        </div>

        <?php
        $URL="";
            if (isset($_GET['SinConfirmar'])) $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?confirmation=3";
                else if (isset($_GET['confirmadas'])) $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?confirmation=4";
                    else if (isset($_GET['pendientes'])) $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?status=1";
                        else if (isset($_GET['entregadas'])) $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?status=2";
                            else if (isset($_GET['fecha'])) $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php?fecha=".$_GET['fecha'];
                                else $URL = "http://localhost/PROYECTO4B-1/phpfiles/react/order_api.php";

            $file = file_get_contents($URL);
            $data = json_decode($file, JSON_PRETTY_PRINT);
        ?>

        <div class="row">
            <?php if (!(isset($data['message']))) {
            foreach ($data as $order) : ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">ID: <?= $order['id_order'] ?></h5>
                            <p class="card-text"><strong>Estado:</strong> <span class="badge <?= $order['id_status'] == 'Pendiente' ? 'bg-danger' : 'bg-success' ?>" >
                                <?= $order['id_status'] ?>
                            </span></p>
                            <p class="card-text"><strong>Cantidad:</strong> <?= $order['supply_quantity'] ?></p>
                            <p class="card-text"><strong>Confirmación:</strong>
                                <span class="badge <?= $order['confirmation'] == 'Confirmada' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $order['confirmation'] ?>
                                </span>
                            </p>
                            <p class="card-text"><small class="text-muted"><strong>Fecha de Creacion:</strong> <?= $order['created_at'] ?></small></p>
                            <p class="card-text"><small class="text-muted"><strong>Ultima Actualizacion:</strong> <?= $order['updated_at'] ?></small></p>
                            <div class="order-label-div center">
                                <?php if($order['confirmation'] == "Sin Confirmar") {?>
                                <label class="order-label" style="background-color: rgb(0, 72, 255);"> Modificar </label>
                                
                                <form method="POST" action="CRUD/delete_order.php" class="center">
                                    <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                                    <button type="submit" class="order-label" style="background-color: rgb(255, 0, 0); border: none; cursor: pointer;">
                                        Eliminar Orden
                                    </button>
                                </form>

                                <?php } else { ?>
                                <a href="order_details.php?id_order=<?= $order['id_order']; ?> ">
                                    <label class="order-label" style="background-color: rgb(255, 115, 0);"> Ver Detalles de la orden </label>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title center">Crear Nueva Orden</h5><br>
                            <form id="createOrderForm" class="center">
                                <input type="hidden" name="quantity" value=0>
                                <button type="submit" style="background: none; border: none;">
                                    <i class="fas fa-plus-circle fa-2x agreagarIcon "></i>
                                </button>
                            </form>
                            <script>
                                document.getElementById('createOrderForm').addEventListener('submit', function(event) {
                                    event.preventDefault();
                                    var formData = new FormData(this);
                                    fetch('../phpfiles/react/order_api.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data);
                                        alert("Se añadió una nueva orden");
                                    })
                                    .catch(error => alert("Se añadió una nueva orden") );
                                    location.reload();
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <?php
                } else { ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title center">No se Encontraron Ordenes</h5><br>
                        </div>
                    </div>
                </div>
            <?php }  ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>