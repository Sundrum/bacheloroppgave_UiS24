@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3 no-print">
        <div class="col">
            <div class="row p-2">
                <a class="btn-primary-outline" href="/admin" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til admin</strong></a>
            </div>
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>


<div class="container">
    <div class="row">
        
        <div class="col-sm-6 col-md-6 col-lg-6">
            <h3 class="text-left"><strong>Fleet management</strong></h3>
            <p class="text-left text-muted">More information will be displayed if you click on one of your sensors</p>
            <div id="map" style="max-width: auto; height: 700px;"></div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 ">
            <br>
            <div class="col-md-2 card card-rounded text-center">
            {{-- <h3 class="text-center"><strong></strong></h3> --}}
            <a class="btn-secoundary-outline" href="/admin" style="color: black; text-decoration: none;"><img class=""> <strong>Show log</strong></a>
            </div>
            <section class="container">
                    <div class="row mt-3 mb-3">
                        <div class="col-md-12">
                            <br>
                            <div class="col-md-12 card card-rounded">
                                <div class="row m-1 mt-3 mb-2">
                                    <div class="col-md-4">
                                        <img class="" src="{{ asset('/img/irr_icon_present.png') }}" style="max-width: 100px; height: 65px;">
                                    </div> 
                                    <div class="col-md-4">
                                        <h4 class="text-center">Gun 1</h4>
                                    </div>           
                                </div>
                                <div class="row m-1 mt-1 mb-3 justify-content-center">
                                    <div class="col-md-8">
                                        {{-- <a href="https://minside.ice.no/minbedrift/3772189/abonnement">
                                            <div class="btn-primary-filled">
                                                Go to
                                            </div>
                                        </a> --}}
                                        <p class="text-center">On @ "date/time"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <br>
                            <div class="col-md-12 card card-rounded">
                                <div class="row m-1 mt-3 mb-2">
                                    <div class="col-md-4">
                                        <img class="" src="{{ asset('/img/idle_green_marker.svg') }}" style="max-width: 100px; height: 65px;">
                                    </div>  
                                    <div class="col-md-4">
                                        <h4 class="text-center">Boom 1</h4>
                                    </div>           
                                </div>
                                <div class="row m-1 mt-1 mb-3 justify-content-center">
                                    <div class="col-md-8">
                                        
                                        <p class="text-center">On @ "date/time"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <br>
                            <div class="col-md-12 card card-rounded">
                                <div class="row m-1 mt-3 mb-2">
                                    <div class="col-md-4">
                                        <img class="" src="{{ asset('/img/irrigation_blue_marker.svg') }}" style="max-width: 100px; height: 65px;">
                                    </div> 
                                    <div class="col-md-4">
                                        <h4 class="text-center">Gun 2</h4>
                                    </div>           
                                </div>
                                <div class="row m-1 mt-1 mb-3 justify-content-center">
                                    <div class="col-md-8">

                                        <p class="text-center">On @ "date/time"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <br>
                            <div class="col-md-12 card card-rounded">
                                <div class="row m-1 mt-3 mb-2">
                                    <div class="col-md-4">
                                        <img class="" src="{{ asset('/img/idle_green_marker.svg') }}" style="max-width: 100px; height: 65px;">
                                    </div> 
                                    <div class="col-md-4">
                                        <h4 class="text-center">Pump 1</h4>
                                    </div>           
                                </div>
                                <div class="row m-1 mt-1 mb-3 justify-content-center">
                                    <div class="col-md-8">
                                        <div class="pie-wrapper progress-75 style-2">
                                            <span class="label">75<span class="smaller">%</span></span>
                                        <div class="pie">
                                            <div class="left-side half-circle"></div>
                                            <div class="right-side half-circle"></div>
                                        </div>
                                        <div class="shadow"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
    </div>
</div>


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
    addMarkers();
    autoSizing();
}

google.maps.event.addDomListener(window, "load", initMap);

function autoSizing() {
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
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