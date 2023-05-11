@extends('layouts.app')

@section('content')
<div class="row mb-3 no-print">
    <div class="col">
        {{-- <button class="btn-7g" href="/admin" ><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til admin</strong></button> --}}
    </div>
    <div class="col text-end">
        {{-- <a class="btn-7g" href="/admin" style="color: black; text-decoration: none;"><img class=""> <strong>Show log</strong></a> --}}

    </div>
</div>

<div class="row">
    <section class="col-md-6 col-lg-6">
        <div id="map" class="card-rounded" style="max-width: auto; min-height: 600px; height:90%"></div>
    </section>
    
    <section class="col-md-6 col-lg-6">
        <div class="row">
            <p class="text-left text-muted">More information will be displayed if you click on one of your sensors</p>
            @foreach ($irrigationunits as $unit)
                <div class="col-md-12 mt-1">
                    <div class="card-rounded bg-white irrigationlist" id="{{$unit['serialnumber']}}" onclick="loadContent('/include/view_irrigation.php?unit={{$unit['serialnumber']}}')">
                        <div class="row p-2">
                            <div class="col-md-3 align-self-center">
                                <img src="{{ asset($unit['markerimg'] ?? '/img/irrigation/marker_state_0.png') }}" width="50">
                            </div> 
                            <div class="col-md-6 text-center">
                                <div class="row">
                                    <div class="col-12 pt-2">
                                        <h4 class="text-center">{{ $unit['sensorunit_location'] ?? $unit['serialnumber']}}</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        @isset($unit['variable']['irrigation_state'])
                                            @if($unit['variable']['irrigation_state'] == '4' || $unit['variable']['irrigation_state'] == '5' || $unit['variable']['irrigation_state'] == '6') 
                                                <span>Started @ {{$unit['currentRun']['starttime'] ?? 'Starttime'}}</span>
                                            @else
                                                <span>Finished @ {{$unit['currentRun']['endtime'] ?? $unit['timestampComment'] ?? 'Endtime'}} </span>
                                            @endif
                                        @else
                                            <span>Last connected @ {{$unit['timestampComment'] ?? ''}} </span>
                                        @endisset
                                    </div>
 
                                </div>
                            </div>
                            @isset($unit['percent'])
                                <div class="col-md-3 float-end">
                                    <div class="semi-donut-full float-end" style="--percentage : {{$unit['percent'] ?? '0'}}; --fill: #00265a;">
                                        {{$unit['percent'] ?? '0'}}
                                    </div>   
                                </div> 
                            @endisset         
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>


<script type="module">
setTitle(@json(__('general.fleetmanagment')));

let markers;
let map;
let infoWindow;

import { MarkerClusterer } from "https://cdn.skypack.dev/@googlemaps/markerclusterer@2.0.3";
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

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 59.390982, lng: 10.460590},
        mapTypeControl: true,
        zoom: 16,
        streetViewControl: false,
        tilt: 0,
        styles: myStyles
    });

    infoWindow = new google.maps.InfoWindow({
        content: "",
        disableAutoPan: true,
    });
    
    addMarkers(@json($irrigationunits));
}

window.initMap = initMap;

function addMarkers(activeLatLng){
    let bounds = new google.maps.LatLngBounds();
    let counter = 0;
    const markers = activeLatLng.map((data, i) => {
        if(data['latest']['lat'] && data['latest']['lng'] && data['latest']['lat']) {
            const label = data['serialnumber'];
            const marker = new google.maps.Marker({
                position: new google.maps.LatLng(data['latest']['lat'], data['latest']['lng']),
                icon: new google.maps.MarkerImage(data['markerimg'], null, null, null, new google.maps.Size(27,42.75)),
                map: map
            });
            counter++;
            bounds.extend(marker.position);
            marker.id = "marker_"+data['serialnumber'];
            marker.addListener("click", () => {
                infoWindow.setContent(label);
                infoWindow.open(map, marker);
            });

            marker.addListener("mouseover", () => {
                document.getElementById(data['serialnumber']).className="bg-7g card-rounded irrigationlist";
                console.log(marker.id);
            });

            marker.addListener("mouseout", () => {
                document.getElementById(data['serialnumber']).className="bg-white card-rounded irrigationlist";
            });
            return marker;
        }
        
    });

    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
    // // Add a marker clusterer to manage the markers.
    if(counter > 3) {
        new MarkerClusterer({ map, markers });
    }

}

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry&callback=initMap" defer></script>

@endsection