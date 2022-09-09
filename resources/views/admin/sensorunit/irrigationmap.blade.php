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
var activeIcon =  new google.maps.MarkerImage("../../img/irr_irrigation_green.png", null, null, null, new google.maps.Size(30,30));
var activeLatLng, arrayLength;
var bounds = new google.maps.LatLngBounds();


function initMap() {
var mapDiv = document.getElementById('map');
    map = new google.maps.Map(mapDiv, {
        center: {lat: 59.390982, lng: 10.460590},
        // mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 16,
        streetViewControl: false,
        tilt: 0
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