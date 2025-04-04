<?php
include("../../menu.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Plazas en Tijuana</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v6.5.0/css/ol.css" type="text/css">
    <script src="https://openlayers.org/en/v6.5.0/build/ol.js" type="text/javascript"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
        }
        #map {
            width: 80%;
            height: 100%;
            position: fixed;
            margin-left: 20%;
            margin-top: 80px;
        }
        #sidebar {
            width: 20%;
            background-color: #f8f8f8;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            margin-top: 80px;
        }
        button {
            width: 90%;
            padding: 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mostrar_info {
            position: absolute;
            background-color: white;
            padding: 10px;
            border: 1px solid black;
            display: none;
            z-index: 100;
        }
    </style>
</head>
<body>
    <div></div><br><br>
    <div id="sidebar"></div>
    <div id="map"></div>
    <div id="mostrar_info" class="mostrar_info"></div>

    <script>
        var map = new ol.Map({
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            target: 'map',
            view: new ol.View({
                center: ol.proj.fromLonLat([-116.95, 32.5]),
                zoom: 12
            })
        });

        const plazas = [
            {
                nombre: "Ubicacion \n Plaza Paseo 2000",
                direccion: "Blvd. 2000, Col. Ejido Francisco Villa, 22205 Tijuana, Baja California, México",
                horario: "10:00 AM - 10:00 PM",
                coords: [-116.83787284650768, 32.45500438234049]
            },
            {
                nombre: "Ubicacion \n Plaza Sendero",
                direccion: "Carretera Tecate - Tijuana 25420, 22245 Tijuana, Baja California, México",
                horario: "10:00 AM - 10:00 PM",
                coords: [-116.866, 32.467]
            },
            {
                nombre: "Ubicacion \n Plaza Monarca",
                direccion: "Blvd. Manuel J. Clouthier 5561, 22250 Tijuana, Baja California, México",
                horario: "9:00 AM - 9:00 PM",
                coords: [-116.944, 32.482]
            },
            {
                nombre: "Ubicacion \n Plaza Oasis",
                direccion: "Blvd. Real de Baja California 23911, Fracc. Real de San Francisco, 22235 Tijuana, Baja California, México",
                horario: "8:00 AM - 10:30 PM",
                coords: [-116.880, 32.440]
            }
        ];

        let vectorSource = new ol.source.Vector();

        plazas.forEach(plaza => {
            let marcador = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat(plaza.coords)),
                nombre: plaza.nombre,
                direccion: plaza.direccion,
                horario: plaza.horario
            });

            marcador.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    src: "default.png",
                    scale: 0.6
                })
            }));

            vectorSource.addFeature(marcador);
        });

        let vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });

        map.addLayer(vectorLayer);

        let mostrar_info = document.getElementById('mostrar_info');

        map.on('click', function(event) {
            let feature = map.forEachFeatureAtPixel(event.pixel, function(feature) {
                return feature;
            });

            if (feature) {
                let coords = feature.getGeometry().getCoordinates();
                let nombre = feature.get('nombre');
                let direccion = feature.get('direccion');
                let horario = feature.get('horario');

                mostrar_info.innerHTML = `<strong>${nombre}</strong><br>${direccion}<br>Horario: ${horario}`;
                mostrar_info.style.display = 'block';

                let pixel = map.getPixelFromCoordinate(coords);
                mostrar_info.style.left = pixel[0] + 'px';
                mostrar_info.style.top = (pixel[1] - 20) + 'px';
            } else {
                mostrar_info.style.display = 'none';
            }
        });

        map.on('pointermove', function(event) {
            if (map.hasFeatureAtPixel(event.pixel)) {
                map.getViewport().style.cursor = 'pointer';
            } else {
                map.getViewport().style.cursor = '';
            }
        });

        let sidebar = document.getElementById('sidebar');

        plazas.forEach(plaza => {
            let button = document.createElement('button');
            button.innerText = plaza.nombre;
            button.onclick = function() {
                map.getView().animate({
                    center: ol.proj.fromLonLat(plaza.coords),
                    zoom: 16,
                    duration: 1000
                });
            };
            sidebar.appendChild(button);
        });

    </script>
</body>
</html>
