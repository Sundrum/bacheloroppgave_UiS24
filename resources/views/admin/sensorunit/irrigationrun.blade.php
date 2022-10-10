@extends('layouts.admin')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

@section('content')

<section class="container-fluid">
    <div class="col-12">
        <div class="mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-md-6 card card-rounded">
                    <h5 class="m-4 text-center">Edit a Irrigation run</h5>
                    <p> The run you are about to edit is: {{$run->irrigation_run_id ?? 'ERROR'}} ({{$run->log_id}})<br> <strong>Are you unsure? Then you shouldnt edit the run.</strong></p>
                    <p>Changes made are none reverseable. An are visable for customers.</p>
                    <div class="mt-1 mb-3">
                        <div class="card-rounded" id="map_1" style="min-height: 650px; position:relative; height: 100%; width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
var positions = Array();
var latlngs = Array();
var start = new google.maps.LatLng(@json($run['irrigation_startpoint']));
console.log(start);
getLatestRun(@json($run['log_id']));
var bounds = new google.maps.LatLngBounds();

var endIcon = new google.maps.MarkerImage("../img/irr_icon_final.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var activeIcon = new google.maps.MarkerImage("../img/irr_icon_present.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var startIcon = new google.maps.MarkerImage("../img/irr_icon_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var poiIcon = new google.maps.MarkerImage("../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2};

function initMap() {
    map = new google.maps.Map(document.getElementById('map_1'), {
        //center: positions[0],
        center: {lat:59.4,lng:10.4},
        mapTypeControl: true,
        zoom: 12,
        streetViewControl: false,
        tilt: 0
    });
    //marker();
    //autoSizing();
}




function getLatestRun(id) {
    $.ajax({
        url: "/admin/irrigationstatus/irrigation/run/" + id,
        type: 'GET',

        success: function (data) {
            console.log('log_id: ' + data.log_id);
            console.log('irrigation_run_id: ' + data.irrigation_run_id);
            console.log('serialnumber: ' + data.serialnumber);
            console.log('irrigation_starttime: ' + data.irrigation_starttime);
            console.log('irrigation_endtime: ' + data.irrigation_endtime);
            console.log('irrigation_startpoint: ' + data.irrigation_startpoint);
            console.log('irrigation_endpoint: ' + data.irrigation_endpoint);
            console.log('irrigation_nozzlewidth: ' + data.irrigation_nozzlewidth);
            console.log('irrigation_nozzlebar: ' + data.irrigation_nozzlebar);
            console.log('irrigation_note: ' + data.irrigation_note);
            console.log('irrigation_nozzleadjustment: ' + data.irrigation_nozzleadjustment);
            console.log('hidden: ' + data.hidden);
            console.log('portal_endpoint: ' + data.portal_endpoint);
            console.log('Run: ' + data.data);

            /*for (var i in data) {
                positions.push({lat: data[i].lat, lng: data[i].lng});
            }*/
        },
    })
}

function marker() {
    var marker = new google.maps.Marker({
        position: markerpos,
        map: map
    });
    bounds.extend(marker.position);
}

function autoSizing() {
    map.setCenter(bounds.getCenter());
}

google.maps.event.addDomListener(window, "load", initMap);

</script>
@endsection