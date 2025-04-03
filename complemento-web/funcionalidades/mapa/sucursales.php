<?php
include("../../menu.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de tiendas</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="../../css/sucursal.css" type="text/css">
    <script src="https://openlayers.org/en/v6.5.0/build/ol.js" type="text/javascript"></script>
</head>
<body>
    <div id="sidebar"></div>
    <div id="map"></div>
    <div id="mostrar_info" class="mostrar_info"></div>

    <script>
        // variables de mapa
        var map = new ol.Map({
            layers: [ // incia el mapa
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            target: 'map', // elemento donde se mostrara el mapa
            view: new ol.View({ // vista por defecto del mapa
                center: ol.proj.fromLonLat([-116.87, 32.45]),
                zoom: 12
            })
        });

        const tiendas = [{
                nombre: "Ubicacion \n tienda Paseo 2000",
                imagen: "images/2000.jpg",
                direccion: "Blvd. 2000, Col. Ejido Francisco Villa, 22205 Tijuana, Baja California, México",
                horario: "10:00 AM - 10:00 PM",
                coords: [-116.83787284650768, 32.45500438234049]
            }, {
                nombre: "Ubicacion \n tienda Sendero",
                imagen: "images/senderos.jpg",
                direccion: "Carretera Tecate - Tijuana 25420, 22245 Tijuana, Baja California, México",
                horario: "10:00 AM - 10:00 PM",
                coords: [-116.866, 32.467]
            }, {
                nombre: "Ubicacion \n tienda Monarca",
                imagen: "images/monarca.jpg",
                direccion: "Blvd. Manuel J. Clouthier 5561, 22250 Tijuana, Baja California, México",
                horario: "9:00 AM - 9:00 PM",
                coords: [-116.944, 32.482]
            }, {
                nombre: "Ubicacion \n tienda Oasis",
                imagen: "images/oasis.webp",
                direccion: "Blvd. Real de Baja California 23911, Fracc. Real de San Francisco, 22235 Tijuana, Baja California, México",
                horario: "8:00 AM - 10:30 PM",
                coords: [-116.880, 32.440]
            }];

        let localizaciones = new ol.source.Vector(); // almacena los marcadores


        tiendas.forEach(tienda => { // funcion para almacenar todos los marcadores
            let marcador = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat(tienda.coords)), // asigna las coordenadas a los marcadores por cada tienda
                nombre: tienda.nombre,
                imagen: tienda.imagen,
                direccion: tienda.direccion,
                horario: tienda.horario
            });

            marcador.setStyle(new ol.style.Style({ // pone estilo a la imagen del marcador
                image: new ol.style.Icon({
                    src: "images/default.png",
                    scale: 0.6
                })
            }));

            localizaciones.addFeature(marcador); // añade marcador a la variable que almacena los marcadores

            let punto = new ol.layer.Vector({ // convierte y almacena los valores de los marcadores para dibujarlos
            source: localizaciones
            });

            map.addLayer(punto); // dibuja los puntos de los marcadores en el mapa
        });

        map.on('click', function(event) { // funcion al dar click a un marcador
            let info_local = map.forEachFeatureAtPixel(event.pixel, function(info_local) {
                return info_local;
            });

            if (info_local) { // busca la informacion del marcador al que se le da click
                let coords = info_local.getGeometry().getCoordinates();
                let nombre = info_local.get('nombre');
                let imagen = info_local.get('imagen');
                let direccion = info_local.get('direccion');
                let horario = info_local.get('horario');

                // le añade la informacion al cuadro para mostralo
                document.getElementById('mostrar_info').innerHTML = `<img src="${imagen}" class="imagen_local"> <br> <strong>${nombre}</strong><br><strong>Direccion:</strong> ${direccion}<br> <strong>Horario:</strong> ${horario}`;
                document.getElementById('mostrar_info').style.display = 'block';
            }
        });

        tiendas.forEach(tienda => { // crea un boton por cada ubicacion de las tiendas
            let button = document.createElement('button');
            button.innerText = tienda.nombre;
            button.onclick = function() {
                map.getView().animate({ // transiciona con una animacion a las coordenas de la tienda
                    center: ol.proj.fromLonLat(tienda.coords),
                    zoom: 16,
                    duration: 1000
                });
            };
            document.getElementById('sidebar').appendChild(button); // añade los botones al sidebar
        });

    </script>
</body>
</html>
