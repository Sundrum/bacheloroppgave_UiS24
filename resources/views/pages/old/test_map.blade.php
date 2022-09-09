@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry"> type="text/javascript"></script>

@section('content')
            <div id="map"></div>
            <div class="overlap" style="position: absolute; top:170px; margin-left:10px; width:50px; z-index:10; background: white;">
                <img class="image-responsive" onclick="$( '#infowindow' ).toggle();" src="../img/info_60x60.png" width="40" alt="" style="margin-left: 5px;">
            </div>
            <div id="infowindow" class="overlap" style="position: absolute; top:200px; width:250px; margin-left:10px; z-index:2; background: white; display:none;">
                <table>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="../img/irr_icon_start.png" width="50"></td>
                        <td class="tdspace"> Startpoint</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="../img/irr_icon_present.png" width="50"></td>
                        <td class="tdspace"> Point of interest </td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="../img/irr_icon_poi.png" width="50"></td>
                        <td class="tdspace"> Point of interest </td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="../img/irr_icon_final.png" width="50"></td>
                        <td class="tdspace"> Endpoint </td>
                    </tr>
                </table>
            </div>    
@endsection


<script>
    var endIcon = new google.maps.MarkerImage("../img/irr_flag_destination.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
var activeIcon = new google.maps.MarkerImage("../img/irr_flag_current.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
var startIcon = new google.maps.MarkerImage("../img/irr_flag_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
var poiIcon = new google.maps.MarkerImage("../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
//var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 4, fillColor:'#FF0000' };
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2, strokeColor:'#004079' };// strokeColor: '#FF0000', fillColor: '#FF0000' };
    
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
        strokeColor: '#008000',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    old.setMap(map);
    old.setPath(oldRun);

    var old = new google.maps.Polyline({
        path: oldRun2,
        geodesic: true,
        strokeColor: '#008000',
        strokeOpacity: 0.8,
        strokeWeight: 33
    });

    old.setMap(map);
    old.setPath(oldRun2);

    var old = new google.maps.Polyline({
        path: oldRun3,
        geodesic: true,
        strokeColor: '#FEE23E',
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
        strokeColor: '#004079',
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
        strokeColor: '#004079',
        strokeOpacity: 0.8,
        strokeWeight: 2
    });

    irrigationpath2.setMap(map);
    irrigationpath2.setPath(latlngs2);

}
  
</script>