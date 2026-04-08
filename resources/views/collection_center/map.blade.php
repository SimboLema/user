<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    let map, marker;

    function initMap() {
        if (map) return; // prevent re-initialization
        map = L.map('map').setView([-6.7924, 39.2083], 6); // Default center (Dar es Salaam)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            $("#latitude").val(lat);
            $("#longitude").val(lng);

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    }

    $('#largeModal').on('shown.bs.modal', function () {
        setTimeout(() => {
            initMap();
            map.invalidateSize();
        }, 500); // wait for modal animation
    });
    </script>
