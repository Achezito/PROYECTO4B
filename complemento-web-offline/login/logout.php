<?php
session_start();

// Destruir la sesión local
$_SESSION = [];
session_destroy();

// Redirigir a la URL de logout de Auth0
$return_to = "http://localhost/PROYECTO4B-1/complemento-web/login/log.php"; // Página a la que se redirige después del logout

$logout_url = "https://dev-dgx7ryyu8ig8jyhz.us.auth0.com/v2/logout?client_id=aTgVvNIyqCrMRgpD0iKj1uzLDREPGbaX&returnTo=".$return_to;

header("Location: $logout_url");
?>