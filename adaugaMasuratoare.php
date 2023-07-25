<?php
session_start();

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
        <link rel="stylesheet" href="/dist/style.css">
        <link href="../dist/output.css" rel="stylesheet">
    </head>
    <body>
        <div class="topnav" id="myTopnav" style="font-family: Poppins">
            <a href="/index.php">Harta</a>
            <a href="/adaugaMasuratoare.php">Adauga Masuratoare</a>
            <a href="/studii.php">Studii</a>
            <a href="/adaugaStudii.php">Adauga Studii</a>
            <?php
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Show the link only if the user is not logged in
            echo '<a href="/formular.php">Devino Membru</a>';
        }
    ?>
            <?php
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                // Show the "Login" button when the user is not logged in
                echo '<a href="/logIn.php" style="float:right;">Login</a>';
            } else {
                // Show the "Logout" button when the user is logged in
                echo '<a href="logout.php" style="float:right;">Logout</a>';
            }
        ?>
        </div>
        <div class="flex font-sans">
            <div class="w-1/5 bg-gray-900 h-screen">
                <!-- LOGO -->
                <div class="flex justify-center pt-8 pb-8 border-b border-b-white">
                    <img src="../dist/logo3.jpeg" class="h-14" />
                </div>
                <!-- HEADING -->
                <h3 class="text-white font-bold  text-center text-3xl mt-8 flex items-center w-full justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Adauga o masuratoare
                </h3>
    
                <!-- ADD EVENT FORM -->
                <div class="mb-6 w-5/6 mx-auto mt-6">
                    <label for="eventDenumire" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Denumire</label>
                    <input type="text" id="eventDenumire" class="input-field" placeholder="Piata Unirii"
                        required>
                </div>
    
                <div class="mb-6 w-5/6 mx-auto mt-6">
                    <label for="eventPeak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Peak</label>
                    <input type="text" id="eventPeak" class="input-field" placeholder="Introdu o valoare in V/m"
                        required>
                </div>
    
                <div class="mb-6 w-5/6 mx-auto mt-6">
                    <label for="eventAverage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Average</label>
                    <input type="text" id="eventAverage" class="input-field" placeholder="Introdu o valoare in uW/m2"
                        required>
                </div>
                
                <div class="mb-6 w-5/6 mx-auto mt-6">
                    <label for="geocoder" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Event Address</label>
                    <div id="geocoder"></div>
                </div>
               
                <div class="w-5/6 mx-auto flex justify-end">
                    <button type="button" class="submit-button" onclick="addEvent()">Adauga masuratoare</button>
                </div>
            </div>
            <div class="h-screen w-full" id="map"></div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoibHVwdXNhbGV4IiwiYSI6ImNraGYwcTJ3cjBlNTcycm80emp0cWR5eTgifQ.7ZO9RkFPRcOPuZzXZqYvbw';

        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [26.102464, 44.434773],
            zoom: 11.15
        });

        map.on('load', function () {
            //place object we will add our events to
            map.addSource('places',{
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': []
                }
            });
            //add place object to map
            map.addLayer({
                'id': 'places',
                'type': 'symbol',
                'source': 'places',
                'layout': {
                    'icon-image': '{icon}',
                    'icon-allow-overlap': true
                }
            });

            //Handle popups
            map.on('click', 'places', (e) => {
                const coordinates = e.features[0].geometry.coordinates.slice();
                const description = e.features[0].properties.description;
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }
            new mapboxgl.Popup()
                .setLngLat(coordinates)
                .setHTML(description)
                .addTo(map);
            });

            //Handle pointer styles
            map.on('mouseenter', 'places', () => {
                map.getCanvas().style.cursor = 'pointer';
            });
            map.on('mouseleave', 'places', () => {
                map.getCanvas().style.cursor = '';
            });

            //get our data from php function
            getAllEvents();
        });

        function getAllEvents(){
            $.ajax({
                url: "../api/getAllEvents.php",
                contentType: "application/json",
                dataType: "json",
                success: function (eventData) {
                    console.log(eventData)
                    map.getSource('places').setData({
                            'type': 'FeatureCollection',
                            'features': eventData
                    });
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

        // Handle form
        const geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
        });

        geocoder.addTo('#geocoder');

        var geocoderResult = {};
        geocoder.on('result', (e) => {
            geocoderResult = e.result, null, 2;
        });

        // Clear results container when search is cleared.
        geocoder.on('clear', () => {
            geocoderResult = {};
        });


        function addEvent(){
            if(geocoderResult=={}){
                return false;
            }
            var postObj = {
                denumire:$("#eventDenumire").val(),
                peak:$("#eventPeak").val(),
                average:$("#eventAverage").val(),
                lat:geocoderResult.center[0],
                lng:geocoderResult.center[1],
            }

            $.ajax({
                url: "../api/createEvent.php",
                type:'POST',
                data:postObj,
                dataType: "json",
                success: function (resp) {
                    if(resp=='success'){
                        //reset form & get new data
                        $("#eventDenumire").val('');
                        $("#eventPeak").val('');
                        $("#eventAverage").val('');
                        geocoder.clear();
                        getAllEvents();
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    </script>
    </body>
</html>
