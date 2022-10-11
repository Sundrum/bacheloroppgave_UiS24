@extends('layouts.admin')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

@section('content')

<section class="container-fluid">
    <div class="col-12">
        <div class="mt-3 mb-3">
            <div class="row justify-content-center">
                <h3>Edit Run</h3>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10 bg-grey rcorners3">
                    <h5 class="text-center">Edit a Irrigation run</h5>
                    <hr>
                    <p> The run you are about to edit is: {{$run->irrigation_run_id ?? 'ERROR'}} ({{$run->log_id}})<br> <strong>Are you unsure? Then you shouldnt edit the run.</strong></p>
                    <p>Changes made are none reverseable. An are visable for customers.</p>
                    <div class="mt-1 mb-3">
                        <div class="card-rounded" id="map_1" style="min-height: 600px; position:relative; height: 100%; width:100%;"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 bg-grey rcorners3">
                    <br><br>
                    <h5 class="text-center">Log</h5>
                    <hr>
                    <table id="logtable" class="display" width="100%"></table>
                </div>

            </div>
        </div>
    </div>
</section>
<script>
var token = "{{ csrf_token() }}";
var positions = Array();
var latlngs = Array();
var bounds = new google.maps.LatLngBounds();
var endMarker;
var startMarker;
var startpoint;
var endpoint;
var log_id;
var endIcon = new google.maps.MarkerImage("../../../img/irr_flag_destination.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
var activeIcon = new google.maps.MarkerImage("../../../img/irr_icon_present.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var startIcon = new google.maps.MarkerImage("../../../img/irr_flag_start.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
var poiIcon = new google.maps.MarkerImage("../../../img/irr_icon_poi.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(25,25));
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 3, strokeColor:'#880808'};

function initMap() {
    map = new google.maps.Map(document.getElementById('map_1'), {
        //center: positions[0],
        center: {lat:59.4,lng:10.4},
        mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 17,
        streetViewControl: false,
        tilt: 0
    });
    getLatestRun(@json($run['log_id']));
    //marker();
    //autoSizing();

}

function getLatestRun(id) {
    $.ajax({
        url: "/admin/irrigationstatus/irrigation/run/" + id,
        type: 'GET',

        success: function (data) {
            data = JSON.parse(data);
            console.log('log_id: ' + data.log_id);
            log_id = data.log_id;
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
        
            startpoint = data.irrigation_startpoint.split(",");
            endpoint = data.irrigation_endpoint.split(",");

            startPoint(startpoint);
            endPoint(endpoint);

            var points = data.data;
            points.forEach(generateMarkers);
            addMarkers();
            autoSizing();


        },
        error: function (data) {
            console.log('ERROR '+data);
        },
    })
}

function generateMarkers(item) {
    if(item[2] !== 0) {
        positions.push({lat: item[2], lng: item[3], timestamp: item[0] });
    } else {
        console.log('Not started')
    }
}

function addMarkers(){
    for (i = positions.length - 1; i >= 0; i--) {
        pushMarker();
    }
}

function pushMarker() {
    var check = 0;
    if (i == (positions.length - 1)) {
    /*activepoint = positions[i];
    iconR = activeIcon;
    map.setCenter(positions[i]);
    
    activeMarker = new google.maps.Marker({
        position: new google.maps.LatLng(positions[i]),
        icon: iconR,
        map: map
    });*/

    } else {
        if (i == 0) {
            console.log(positions[i]);
        } else {
            iconR = runIcon;
        }
        check = 1;
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(positions[i]),
            icon: iconR,
            map: map
        });
    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {

            infowindow.setContent(positions[i].timestamp);
            
            infowindow.open(map, marker);
            }
        })(marker, i));
    }
}

function endPoint(item) {
    var endMarker = new google.maps.Marker({
        position: new google.maps.LatLng(item[0],item[1]),
        draggable: true,
        icon: endIcon,
        map: map
    });
    bounds.extend(endMarker.position);
    console.log("Endpoint added with: " + item);
    google.maps.event.addListener(endMarker, 'drag', function (e) {
        console.log("drag");
    });
    google.maps.event.addListener(endMarker, 'dragend', function (e) {
        console.log("dragend");
        console.log(e.latLng);
        showContextMenu(e.latLng);
    });
}

function startPoint(item) {
    var startMarker = new google.maps.Marker({
        position: new google.maps.LatLng(item[0],item[1]),
        draggable: true,
        icon: startIcon,
        map: map
    });
    bounds.extend(startMarker.position);
    console.log("Startpoint added with: " + item);
    google.maps.event.addListener(startMarker, 'drag', function (e) {
        console.log("Drag startMarker");
    });
    google.maps.event.addListener(startMarker, 'dragend', function (e) {
        console.log("dragend for startmarker");
        console.log(e.latLng);
        showContextMenuStart(e.latLng);
    });
}

function autoSizing() {
    map.setCenter(bounds.getCenter());
}

function showContextMenu(currentLatLng)
{
	var lat = currentLatLng.lat();
	var lon = currentLatLng.lng();
	var projection;
	var contextmenuDir;
	projection = map.getProjection();
	$('.contextmenu').remove();
	contextmenuDir = document.createElement("div");
	contextmenuDir.className  = 'contextmenu';
	contextmenuDir.innerHTML = "<div style='padding-left:5px; padding-top:5px; padding-bottom:5px;'> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='update_point("+lat+","+lon+",2);'> <img class='pull-left' src='../../../img/flag_destination_50x50.png' width='30'>&nbsp&nbsp Set New Endpoint<\/a> \
									<button type='button'style='margin-top: 1px; margin-right: 2px; position:absolute; top:0; right:0;' onclick='closeContextmenu();' class='btn-md btn-rounded' aria-label='Close'> \
									<span aria-hidden='true'>&times;</span></button> \
								<\/div>";
	$(map.getDiv()).append(contextmenuDir);
	setMenuXY(currentLatLng, '.contextmenu');
	contextmenuDir.style.visibility = "visible";
}

function showContextMenuStart(currentLatLng)
{
	var lat = currentLatLng.lat();
	var lon = currentLatLng.lng();
	var projection;
	var contextmenuDir;
	projection = map.getProjection();
	$('.contextmenu').remove();
	contextmenuDir = document.createElement("div");
	contextmenuDir.className  = 'contextmenu';
	contextmenuDir.innerHTML = "<div style='padding-left:5px; padding-top:5px; padding-bottom:5px;'> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='update_point("+lat+","+lon+",1);'> <img class='pull-left' src='../../../img/flag_destination_50x50.png' width='30'>&nbsp&nbsp Set New Startpoint<\/a> \
									<button type='button'style='margin-top: 1px; margin-right: 2px; position:absolute; top:0; right:0;' onclick='closeContextmenu();' class='btn-md btn-rounded' aria-label='Close'> \
									<span aria-hidden='true'>&times;</span></button> \
								<\/div>";
	$(map.getDiv()).append(contextmenuDir);
	setMenuXY(currentLatLng, '.contextmenu');
	contextmenuDir.style.visibility = "visible";
}

function setMenuXY(currentLatLng, contextclass)
{
	var mapWidth = $('#map').width();
	var mapHeight = $('#map').height();
	var menuWidth = $(contextclass).width();	// '.contextmenu'
	var menuHeight = $(contextclass).height();
	var clickedPosition = getCanvasXY(currentLatLng);
	var x = clickedPosition.x ;
	var y = clickedPosition.y + 40;

	if((mapWidth - x ) < menuWidth)   x = x - menuWidth;
	if((mapHeight - y ) < menuHeight) y = y - menuHeight;
	
	if (x < 0) {
		x = 0;
	}

	$(contextclass).css('left', x);
	$(contextclass).css('top', y);
	$(contextclass).css('border','none');
}

function getCanvasXY(currentLatLng)
{
	var scale = Math.pow(2, map.getZoom());
	var nw = new google.maps.LatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng());
	var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
	var worldCoordinate = map.getProjection().fromLatLngToPoint(currentLatLng);
	var currentLatLngOffset = new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
	return currentLatLngOffset;
}

function update_point(lat, lng, point_id) {
    
	$('.contextmenu').remove();

    $.ajax({
      url: "/admin/irrigationstatus/irrigationrun/update",
      type: 'POST',
      data: { 
        "lat": lat,
        "lng": lng,
        "log_id": log_id, 
        "point_id": point_id,
        "_token": token,
      },
      success: function(msg) {
        console.log(msg);  
      },   
      error:function(msg) {
      }
    });
}

function closeContextmenu() {
	$('.contextmenu').remove();
    alert('The markers position is not changed...');
    console.log("Clicked");
}

google.maps.event.addDomListener(window, "load", initMap);
var infowindow = new google.maps.InfoWindow();
    function infoCallback(infowindow) {
        return function() {
        infowindow.open(map);
    };
}

$(document).ready(function () {
    var dataSet = @php echo $log; @endphp;
    var table = $('#logtable').DataTable({
        data: dataSet,
        pageLength: 25, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 4,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
        'select': {
            style: 'multi'
        },
        columns: [
            { title: "Timestamp" },
            { title: "0" },
            { title: "1" },
            { title: "2" },
            { title: "3" },
            { title: "4" },
            { title: "5" },
            { title: "6" },
            { title: "7" },
            { title: "8" },            
            { title: "9" },
            { title: "10" },
            { title: "11" },
            { title: "12" },
            { title: "13" },            
            { title: "14" },
            { title: "15" },
            { title: "16" },
            { title: "17" },
            { title: "18" },
        ],
    });
    
    $('#unittable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var id = datarow[0];
        window.location='sensorunit/'+id;
    });
});

</script>
@endsection