<script>
    window.onload = function() {
        var url = window.location.href; // URL de la pgina
            var fragment = url.split('#')[1];
            var params = new URLSearchParams(fragment);
            var accessToken = params.get('access_token');
            var idToken = params.get('id_token');
            // Este codigo convierte los parametros enviados con # para enviarlos a un archivo php que guarda la informacion

            var newUrl = 'session_start.php?access_token='+accessToken+'&id_token='+idToken;
            window.location.href = newUrl;
        };
</script>
