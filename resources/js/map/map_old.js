function getLatestRun(serialnumber) 
{

    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: "/irrigationrun/" + serialnumber,
        type: 'GET',
        data: {
            "serialnumber": serialnumber,
            "_token": token,
        },

        success: function (data) {
            for (var i = 0; i < data.result.length; i++) {
                lat = data[i].lat;
                lng = data[i].lng;
                console.log("Lat = " + lat);
            }
        },
    })
}



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
    
var oldRun = [{lat: 59.392400, lng: 10.458281},
    {lat: 59.392333, lng: 10.459293}, 
    {lat: 59.389464, lng: 10.458324}, 
    {lat: 59.389566, lng: 10.456756}];

var oldRun1 = [{lat: 59.392284, lng: 10.459304},
    {lat: 59.392223, lng: 10.460341},
    {lat: 59.389384, lng: 10.459684}, 
    {lat: 59.389500, lng: 10.458326}];
    
var oldRun2 = [{lat: 59.393125, lng: 10.456368},
    {lat: 59.392584, lng: 10.455936}, 
    {lat: 59.392875, lng: 10.452013}, 
    {lat: 59.393332, lng: 10.452498}];

var oldRun3 = [{lat: 59.393074, lng: 10.457156},
    {lat: 59.392780, lng: 10.462704}, 
    {lat: 59.392284, lng: 10.462686}, 
    {lat: 59.392663, lng: 10.456887}];

var newRun = [{lat: 59.392320, lng: 10.460150},
    {lat: 59.392277, lng: 10.461438}, 
    {lat: 59.390942, lng: 10.461230}, 
    {lat: 59.390982, lng: 10.459880}];

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
addOldRun();
addSMSmarker();
//marker.setAnimation(google.maps.Animation.BOUNCE);
}	// end initMap

google.maps.event.addDomListener(window, "load", initMap);

var latlngs = Array();
var endIcon = new google.maps.MarkerImage("../img/irr_icon_final.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var activeIcon = new google.maps.MarkerImage("../img/irr_icon_present.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
//var activeIcon = {path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW, strokeColor: "#FFFFFF", scale: 2, fillColor: "#008000"};
var startIcon = new google.maps.MarkerImage("../img/irr_icon_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var poiIcon = new google.maps.MarkerImage("../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
//var activeIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 5, strokeColor: '#008000', fillColor: '#008000' };
var runIcon = { path: google.maps.SymbolPath.CIRCLE, 
scale: 2};

function addMarkers(){
for (i = positions.length - 1; i >= 0; i--) {
if (i == (positions.length - 1)) {
iconR = startIcon;
latlngs.push(new google.maps.LatLng(positions[i]));
    //console.log("Start Icon at: " + markerLatLon[i][1] + markerLatLon[i][2] + "    i   = " + i);
} else if (i == 0) {
iconR = activeIcon;
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
}

function addSMSmarker(){
poiMarker = new google.maps.Marker({
position: {lat: 59.390171, lng: 10.460470},
icon: poiIcon,
map: map
});
}

function addEndPoint(){
endMarker = new google.maps.Marker({
position: new google.maps.LatLng(endpoint[0]),
icon: endIcon,
map: map
});
latlngs.push(new google.maps.LatLng(endpoint[0]));


var irrigationpath = new google.maps.Polyline({
path: latlngs,
geodesic: true,
strokeColor: '#004079',
strokeOpacity: 0.8,
strokeWeight: 2
});

irrigationpath.setMap(map);
irrigationpath.setPath(latlngs);
}

function addOldRun() {
var greeninfo = "<div>2 days ago</div>";
var greeninfo3 = "<div>3 days ago</div>";
var redinfo = "<div>9 days ago</div>";
var blueinfo = "<div>Less than a day</div>";
var yellowinfo ="<div>6 days ago</div>";

addPoly(oldRun, greeninfo,"#008000","#008000");
addPoly(oldRun1, greeninfo3,"#008000","#008000");
addPoly(oldRun2, redinfo,"#FF0000","#FF0000");
addPoly(newRun, blueinfo,"#004079","#004079");
addPoly(oldRun3, yellowinfo,"#FEE23E","#FEE23E");
}

function addPoly(polyPath, myInfo, line_colour, fill_colour) {
myPoly = new google.maps.Polygon({
paths: polyPath,
strokeColor: line_colour,
strokeOpacity: 0.5,
strokeWeight: 2,
fillColor: fill_colour,
fillOpacity: 0.4
});
//var center = findCenter(polyPath);
var infowindow = new google.maps.InfoWindow({
content: myInfo,
position: polyPath[0]
});
google.maps.event.addListener(myPoly, 'click', infoCallback(infowindow));

myPoly.setMap(map);
}

function infoCallback(infowindow) {
return function() {
infowindow.open(map);
};
}
