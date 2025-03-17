<?php
session_start();  // Asegúrate de que la sesión esté iniciada correctamente
if ($_SESSION["user_role"] != 'Admin') {
    header("Location: ../index.php");  // Redirige a index si no es admin
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Inventario</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables.js -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        /* Estilos para la barra lateral */
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
        }

        /* Para que el contenido no quede detrás del sidebar */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Estilos para hacer que la tabla se vea bien */
        .table-container {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="menuLateral" class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar py-3 collapse show">
            <h4 class="text-center">Inventario</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Productos</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Almacenes</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Reportes</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="content col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Botón para abrir el menú en móviles -->
            <button class="btn btn-dark d-md-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#menuLateral">
                ☰ Menú
            </button>

            <h2 class="mt-4">Listado de Productos</h2>

            <!-- Botón para agregar producto -->
            <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#modalProducto">Agregar Producto</button>

            <!-- Tabla de productos -->
            <div class="table-container">
                <table class="table table-bordered table-hover" id="tablaProductos">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Laptop HP</td>
                            <td>Computadoras</td>
                            <td>15</td>
                            <td>$800</td>
                            <td>
                                <button class="btn btn-primary btn-sm">Editar</button>
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Modal para agregar producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductoLabel">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            <option>Computadoras</option>
                            <option>Accesorios</option>
                            <option>Periféricos</option>
                        </select>
                        <div class="invalid-feedback">Por favor, selecciona una categoría.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" class="form-control" required>
                        <div class="invalid-feedback">Ingrese una cantidad válida.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <input type="text" class="form-control" required>
                        <div class="invalid-feedback">Ingrese un precio válido.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar DataTables -->
<script>
    $(document).ready(function () {
        $('#tablaProductos').DataTable({
            "language": {
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });
</script>

<!-- Script para validación del formulario -->
<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

</body>
</html>
