@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry"> type="text/javascript"></script>

@section('content')

    <div class="row bg-white card-rounded">
        <div class="col-4 col-md pt-3">
            <span>
                Distance: <span id="meter" name="meter"></span> 243
            </span>
        </div>
        <div class="col">
            <button class="btn-7s" onclick="addPOI(0,0,1);">SMS<sub>1</sub></button>
        </div>
        <div class="col">
            <button class="btn-7s" onclick="addPOI(0,0,2);">SMS<sub>2</sub></button>
        </div>
        <div class="col">
            <div class="float-end">
                <img class="image-responsive float-right pt-1" role="button" href="#" data-bs-toggle="dropdown" src="../img/info_60x60.png" width="40" alt="">
                <ul class="dropdown-menu bg-white">
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/start.png" width="40"> @lang('map.startpoint')
                    </li>
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/current.svg" width="40"> @lang('map.current')
                    </li>
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/sms.png" width="40"> @lang('map.poi')
                    </li class="px-3">
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/finish.png" width="40"> @lang('map.endpoint')
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="map"></div>

<script>
    setTitle('Irrigation sensor');
    setName('Demo');
    setCustomer('7Sense Agritech')
var endIcon = new google.maps.MarkerImage("../img/irrigation/finish.png", null, null, null, new google.maps.Size(54,85.5));
var activeIcon = new google.maps.MarkerImage("../img/irrigation/current.svg", null, null, new google.maps.Point(25, 25), new google.maps.Size(50,50));
var startIcon = new google.maps.MarkerImage("../img/irrigation/start.png", null, null, null, new google.maps.Size(54,85.5));
var poiIcon = new google.maps.MarkerImage("../img/irrigation/sms.png", null, null, null, new google.maps.Size(54,85.5));
//var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 4, fillColor:'#FF0000' };
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2, strokeOpacity: 0.8, strokeColor:'#122e53' };// strokeColor: '#FF0000', fillColor: '#FF0000' };
    
    var positions = Array();
    var positions = [{lat: 59.390982, lng: 10.460590},
    {lat: 59.391120, lng: 10.460620},
    {lat: 59.391242, lng: 10.460635},
    {lat: 59.391397, lng: 10.460660},
    {lat: 59.391540, lng: 10.460689}, 
    {lat: 59.391687, lng: 10.460699},
    {lat: 59.391798, lng: 10.460715}, 
    {lat: 59.391909, lng: 10.460730}, 
    {lat: 59.392031, lng: 10.460750}, 
    {lat: 59.392245, lng: 10.460799}];
    
var oldRun = [{lat: 59.392386, lng: 10.458759},
    {lat: 59.389511, lng: 10.457919}];

var oldRun2 = [{lat: 59.392305, lng: 10.459687},
{lat: 59.389492, lng: 10.458984}];

var oldRun3 = [{lat: 59.392917, lng: 10.457096},
{lat: 59.392737, lng: 10.462611}];
    
var endpoint = [{lat: 59.389329, lng: 10.460300}]
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

    addMarkers();
    addEndPoint();

    var old = new google.maps.Polyline({
        path: oldRun,
        geodesic: true,
        strokeColor: '#a7c49d',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    old.setMap(map);
    old.setPath(oldRun);

    var old = new google.maps.Polyline({
        path: oldRun2,
        geodesic: true,
        strokeColor: '#a7c49d',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    old.setMap(map);
    old.setPath(oldRun2);

    var old = new google.maps.Polyline({
        path: oldRun3,
        geodesic: true,
        strokeColor: '#fed16d',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    old.setMap(map);
    old.setPath(oldRun3);
}

google.maps.event.addDomListener(window, "load", initMap);
var latlngs = Array();
var latlngs2 = Array();

function addMarkers(){
    for (i = positions.length - 1; i >= 0; i--) {
        if (i == (positions.length - 1)) {
            iconR = startIcon;
            latlngs.push(new google.maps.LatLng(positions[i]));
            latlngs2.push(new google.maps.LatLng(positions[i]));

                //console.log("Start Icon at: " + markerLatLon[i][1] + markerLatLon[i][2] + "    i   = " + i);
        } else if (i == 0) {
            iconR = activeIcon;
            latlngs.push(new google.maps.LatLng(positions[i]));
            //console.log("Active Icon at: " + markerLatLon[i][1] + markerLatLon[i][2] + "    i   = " + i);
        } else {
            iconR = runIcon;
            //console.log("Run Icon at: " + markerLatLon[i][1] + markerLatLon[i][2] + "    i   = " + i);
        }
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(positions[i]),
            icon: iconR,
            map: map
        });  
    }

    var irrigationpath = new google.maps.Polyline({
        path: latlngs,
        geodesic: true,
        strokeColor: '#00265a',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    irrigationpath.setMap(map);
    irrigationpath.setPath(latlngs);
}

function addEndPoint(){
    endMarker = new google.maps.Marker({
        position: new google.maps.LatLng(endpoint[0]),
        icon: endIcon,
        map: map
    });
    latlngs2.push(new google.maps.LatLng(endpoint[0]));

    var irrigationpath2 = new google.maps.Polyline({
        path: latlngs,
        geodesic: true,
        strokeColor: '#122e53',
        strokeOpacity: 0.8,
        strokeWeight: 2
    });

    irrigationpath2.setMap(map);
    irrigationpath2.setPath(latlngs2);

}
  
</script>
@endsection
