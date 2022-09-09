var positions = Array();

function initMap() {
    getLatestRun('21-1020-AB-00139');

    var mapDiv = document.getElementById('map');
    map = new google.maps.Map(mapDiv, {
        center: positions[0],
        mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 16,
        streetViewControl: false,
        tilt: 0
    });

}	// end initMap

function getLatestRun(serialnumber) 
{
    //var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: "/irrigationrun/" + serialnumber,
        type: 'GET',

        success: function (data) {
            console.log('Data' + data);
            for (var i in data) {
                positions.push({lat: data[i].lat, lng: data[i].lng});
            }
            //map.setCenter(positions[0]);
        },
    })
}

google.maps.event.addDomListener(window, "load", getLatestRun);
google.maps.event.addDomListener(window, "load", initMap);

var latlngs = Array();
var endIcon = new google.maps.MarkerImage("../img/irr_icon_final.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var activeIcon = new google.maps.MarkerImage("../img/irr_icon_present.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var startIcon = new google.maps.MarkerImage("../img/irr_icon_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var poiIcon = new google.maps.MarkerImage("../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2};

function addMarkers(){
    for (i = positions.length - 1; i >= 0; i--) {
        if (i == (positions.length - 1)) {
            iconR = startIcon;
            latlngs.push(new google.maps.LatLng(positions[i]));
        } else if (i == 0) {
            iconR = activeIcon;
        } else {
            iconR = runIcon;
        }
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(positions[i]),
            icon: iconR,
            map: map
        });  

        /*google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
  
              var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[positions.length - 1]));
              if (i > 0) {
                  var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[i-1]));
              }
              var then = positions[count-1][4];
              var now = positions[i][4];
              var diff = moment.duration(moment(now).diff(moment(then)));
              var mt = (distance / (diff/1000))*3600;
  
  
              var back;
              if (count > 0) back = 0;
              else back = count;
  
              var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(markerLatLon[i-back][1], markerLatLon[i-back][2]), new google.maps.LatLng(markerLatLon[count-1][1], markerLatLon[count-1][2]));
              var then2 = markerLatLon[count-1][4];
              var now2 = markerLatLon[i-back][4];
              var diff2 = moment.duration(moment(now2).diff(moment(then2)));
              var mt2 = (distance2 / (diff2/1000))*3600;
  
              infowindow.setContent(markerLatLon[i][4]+'<br>'+markerLatLon[i][3]+'<br>Distanse = '+distance.toFixed(1)+' meters, '+mt.toFixed(1)+'('+mt.toFixed(1)+')'+' meters per hour ');
              
              infowindow.open(map, marker);
            }
          })(marker, i));*/
    }
}

function infoCallback(infowindow) {
    return function() {
        infowindow.open(map);
    };
}
