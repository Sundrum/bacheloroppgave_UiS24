@extends('layouts.admin')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

@section('content')
<div class="container">
    <div class="row mb-3 no-print">
        <div class="col">
            <div class="row p-2">
                <a class="btn-primary-outline" href="/admin/irrigationstatus" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til Irrigation Status</strong></a>
            </div>
        </div>
    </div>
</div>
<div id="map"></div>
<script>
const parser = new DOMParser();
const sleepIcon = "../../img/idle_green_marker.svg";
const sleepIconElement = parser.parseFromString(
    sleepIcon,
  "image/svg+xml"
).documentElement;
var activeIcon =  new google.maps.MarkerImage("../../img/idle_green_marker.svg", null, null, null, new google.maps.Size(40,40));
var irrigationIcon =  new google.maps.MarkerImage("../../img/irrigation_blue_marker.svg", null, null, null, new google.maps.Size(40,40));

var activeLatLng, arrayLength;
var bounds = new google.maps.LatLngBounds();
var myStyles =[
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
              { visibility: "off" }
        ]
    }
];

function initMap() {
var mapDiv = document.getElementById('map');
    map = new google.maps.Map(mapDiv, {
        center: {lat: 59.390982, lng: 10.460590},
        mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 16,
        streetViewControl: false,
        tilt: 0,
        styles: myStyles 
    });
    getActive();
    addMarkers();
    autoSizing();
}

google.maps.event.addDomListener(window, "load", initMap);

function autoSizing() {
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
}

function getActive() {
    activeLatLng = @json($latlngs);
}

function addMarkers(){
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(59.409994,10.448804),
        icon: activeIcon,
        map: map
        
    });

    // marker = new google.maps.Marker({
    //         position: new google.maps.LatLng(59.409994,10.448804),
    //         icon: activeIcon,
    //         map: map
    // });
    bounds.extend(marker.position);

    marker = new google.maps.Marker({
            position: new google.maps.LatLng(59.433838,10.410157),
            icon: irrigationIcon,
            map: map
    });
    bounds.extend(marker.position);

    marker = new google.maps.Marker({
            position: new google.maps.LatLng(59.385768,10.438389),
            icon: irrigationIcon,
            map: map
    });
    bounds.extend(marker.position);

    for (i = 0; i < activeLatLng.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(activeLatLng[i]['lat'], activeLatLng[i]['lng']),
            icon: activeIcon,
            map: map
        });
        bounds.extend(marker.position);
    }
} 
</script>

@endsection