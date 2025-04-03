<?php

include("../../menu.php");
if (!isset($_SESSION['usuario'])) {
    echo "<p class='text-center text-danger'>No hay información del usuario. Inicia sesión primero.</p>";
    exit;
}

$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['usuario']['nickname'] = $_POST['nickname'];
    $_SESSION['usuario']['name'] = $_POST['name'];
    $_SESSION['usuario']['email'] = $_POST['email'];

    header("location: perfil.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="<?= $local?>css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $local?>css/styles.css" rel="stylesheet">
</head>

<style>

    .user{
        width: 120px;
        height: 120px;
        border: 3px solid #007bff;
        border-radius: 60px;
    }
    
    .id{
        font-size: 15pt;
    }

    label{
        font-size: 16pt;
        margin-bottom: 5px;
    }
</style>

<body id="body" class="center"><br>

    <div class="shadow p-4 text-center" style="width: 50%; margin-top: 120px; margin-bottom:120px;">
        <img src="<?php echo $usuario['picture']; ?>" class="user" alt="Foto de perfil">
        <h4 class="mt-3">Perfil de Usuario</h4>
        
        <p class="id"><strong>User ID:</strong> <?php echo $usuario['sub']; ?></p>

        <form method="POST" class="text-start">
            <div class="mb-3">
                <label>Nickname:</label>
                <input type="text" name="nickname" class="form-control" value="<?php echo $usuario['nickname']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $usuario['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Correo Electrónico:</label>
                <input type="text" name="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Actualizar Perfil</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>