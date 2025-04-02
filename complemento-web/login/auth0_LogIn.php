<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.auth0.com/js/auth0/9.18/auth0.min.js"></script>
</head>
<body onload="autenticar()">
    <script>
        var auth0 = new auth0.WebAuth({
            domain: 'dev-dgx7ryyu8ig8jyhz.us.auth0.com',  // Codigo de la aplicacion de Auth0
            clientID: 'aTgVvNIyqCrMRgpD0iKj1uzLDREPGbaX',  // ID de cliente en auth
            redirectUri: 'http://localhost/PROYECTO4B-1/complemento-web/login/callback.php',  // redirige después del login
            responseType: 'token id_token',
            scope: 'openid profile email'
        });

        function autenticar() { // Redirige a Auth0 para que el usuario se registre
            auth0.authorize({
                redirectUri: 'http://localhost/PROYECTO4B-1/complemento-web/login/callback.php', // redirige después del login
                prompt: 'signup'
            });
        }
    </script>
</body>
</html>