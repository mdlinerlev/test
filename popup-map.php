<div class="popup-b2b _map">
    <div class="popup-b2b__wrp">
        <div class="popup-b2b__map">
            <div class="popup-b2b__map-wrp" id="map">

            </div>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPFZGERGOCULXP_1r-UCZtTcxBBY7OaEI&amp;callback=initMap" async defer></script>
            <script>
                let coords = '<?= $_GET["coords"] ?>';

                function initMap() {
                    // The location of Uluru
                    let crds = coords.split(",");
                    let uluru = {lat: parseFloat(crds[0]), lng: parseFloat(crds[1])}

                    const directionsService = new google.maps.DirectionsService();
                    const directionsRenderer = new google.maps.DirectionsRenderer();

                    // The map, centered at Uluru
                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 13,
                        center: uluru,
                    });
                    // The marker, positioned at Uluru
                    const marker = new google.maps.Marker({
                        position: uluru,
                        map: map,
                    });

                    infoWindow = new google.maps.InfoWindow();

                    const locationButton = document.createElement("button");

                    locationButton.textContent = "Проложить маршрут";
                    locationButton.classList.add("padding-bottom");
                    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(locationButton);
                    locationButton.addEventListener("click", () => {
                        // Try HTML5 geolocation.
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    const pos = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude,
                                    };

                                    infoWindow.setPosition(pos);
                                    infoWindow.setContent("Вы находитесь здесь");
                                    new google.maps.Marker({
                                        position: pos,
                                        map: map,
                                    });
                                    infoWindow.open(map);
                                    calculateAndDisplayRoute(directionsService, directionsRenderer, pos, uluru, map);
                                    map.setCenter(pos);
                                },
                                () => {
                                    handleLocationError(true, infoWindow, map.getCenter());
                                }
                            );
                        } else {
                            // Browser doesn't support Geolocation
                            handleLocationError(false, infoWindow, map.getCenter());
                        }
                    });
                }

                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(
                        browserHasGeolocation
                            ? "Error: The Geolocation service failed."
                            : "Error: Your browser doesn't support geolocation."
                    );
                    infoWindow.open(map);
                }

                function calculateAndDisplayRoute(directionsService, directionsRenderer, start, end, map) {
                    directionsService
                        .route({
                            origin: start,//google.maps.LatLng(start),
                            destination: end, //google.maps.LatLng(end),
                            travelMode: google.maps.TravelMode.DRIVING,
                        })
                        .then((response) => {
                            directionsRenderer.setMap(map);
                            directionsRenderer.setDirections(response);
                        })
                        .catch((e) => window.alert("Directions request failed due to " + e));
                }
            </script>
        </div>
    </div>
</div>

<style>
    .mfp-wrap.w820 .mfp-content {
        max-width: 820px;
    }
    .popup-b2b {
        background-color: #fff;
        padding: 25px 40px 40px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        overflow: hidden;
    }

    .padding-bottom {
        bottom: 30px !important;
    }
</style>