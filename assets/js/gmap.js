
const $map = $('#map');
const latitude = $map.attr('data-latitude');
const longitude = $map.attr('data-longitude');
const hintContent = $map.attr('data-hintContent');
const balloonContent = $map.attr('data-balloonContent');

function initMap() {
    // Creating the map.

    let map_center = { lat: latitude, lng:  longitude};
    let infoWindow = new google.maps.InfoWindow();
    let map = new google.maps.Map(
        document.getElementById("map"), 
        {
            zoom: 13,
            center: map_center,
        });


    let marker = new google.maps.Marker({
            map_center,
            map,
            title: balloonContent,
            label: balloonContent,
            optimized: false,
            });

    // Add a click listener for each marker, and set up the info window.
    marker.addListener("click", () => {
    infoWindow.close();
    infoWindow.setContent(marker.getTitle());
    infoWindow.open(marker.getMap(), marker);
    });

}

$map.css({
    width: '100%',
    height: '280px',
    'padding-top': '20px'
});
