var endIcon = new google.maps.MarkerImage("../img/irr_icon_final.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var activeIcon = new google.maps.MarkerImage("../img/irr_icon_present.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
//var activeIcon = {path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW, strokeColor: "#FFFFFF", scale: 2, fillColor: "#008000"};
var startIcon = new google.maps.MarkerImage("../img/irr_icon_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var poiIcon = new google.maps.MarkerImage("../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
//var activeIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 5, strokeColor: '#008000', fillColor: '#008000' };
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2};

function initMap() {
    var mapDiv = document.getElementById('map');
        map = new google.maps.Map(mapDiv, {
        center: {lat: 59.390982, lng: 10.460590},
        mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 16,
        streetViewControl: false,
        tilt: 0
    });
    //addMarkers();
    //addEndPoint();
    //addOldRun();
    //addSMSmarker();
}
