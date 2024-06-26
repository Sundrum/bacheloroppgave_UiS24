@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<div class="row">
    <div class="col-6">
        <a class="mb-2" href="/admin/irrigationstatus/{{$run->serialnumber ?? ''}}" style="color: black; text-decoration: none;"><button class="btn-outline-7r"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til {{$run->serialnumber ?? ''}}</strong></button></a>
    </div>
    <div class="col-6">
        <button class="float-end btn-7r" onclick="deleteRun({{$run->log_id}})">Delete run</button>
    </div>
</div>
<div class="mt-3 mb-3">
    <div class="row justify-content-center">
        <div class="col-md-10 bg-white card-rounded px-3">
            <h5 class="text-center">Edit a Irrigation run</h5>
            <hr>
            <p> The run you are about to edit is: {{$run->irrigation_run_id ?? 'ERROR'}} ({{$run->log_id}})<br> <strong>Are you unsure? Then you shouldnt edit the run.</strong></p>
            <p>Changes made are none reverseable. An are visable for customers.</p>
            <div class="mt-1 mb-2">
                <div class="card-rounded" id="map_1" style="height: 500px; position:relative; width:100%;"></div>
            </div>
        </div>
        <div class="col-md-10 bg-white card-rounded mt-3 px-3">
            <div class="row">
                <div class="col-6">
                    <h5 class="mt-2">Log</h5>
                </div>
                <div class="col-6">
                    <button class="btn-7g mt-2 float-end" onclick="cleanUp();"> Clean Up Velocity</button>
                </div>
            </div>

            <hr>
            <table id="logtable" class="display" width="100%"></table>
        </div>
    </div>
</div>
<script>
setTitle('Edit Run ' + @json($run['log_id']));
var positions = Array();
var latlngs = Array();
var bounds = new google.maps.LatLngBounds();
var endMarker;
var startMarker;
var startpoint;
var endpoint;
var log_id;
let starttime;
let endttime;
let serialnumber;
var startIcon = new google.maps.MarkerImage("/img/irrigation/start.png", null, null, null, new google.maps.Size(54,85.5));
var endIcon = new google.maps.MarkerImage("/img/irrigation/finish.png", null, null, null, new google.maps.Size(54,85.5));
var poiIcon = new google.maps.MarkerImage("/img/irrigation/sms.png", null, null, null, new google.maps.Size(54,85.5));
var activeIcon = new google.maps.MarkerImage("/img/irrigation/current.svg", null, null, new google.maps.Point(25, 25), new google.maps.Size(50,50));
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 3, strokeColor:'#880808' };// strokeColor: '#FF0000', fillColor: '#FF0000' };

function cleanUp() {
    $.ajax({
        url: "/admin/irrigationstatus/irrigationrun/cleandata",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serialnumber,
            "starttime": starttime,
            "endtime": endtime,
            "_token": token,
        },
        success: function(data) {
            console.log(data);
            successMessage('Success');
            
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong');

        }
    });

}

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
            serialnumber = data.serialnumber;
            starttime = data.irrigation_starttime;
            endtime = data.irrigation_endtime;
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
            console.log(data.data);
            var points = data.data;
            points.forEach(generateMarkers);
            addMarkers();
            autoSizing();
            generateTable(data.data);


        },
        error: function (data) {
            console.log('ERROR '+data);
        },
    })
}

function deleteRun(id) {
    
    var confirmed = confirm('Do you want to delete this run?');
    if(confirmed) {
        $.ajax({
            url: "/irrigation/deleterun",
            type: 'POST',
            dataType: 'json',
            data: { 
                "id": id,
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                successMessage('You successfully deleted this run');
                
            },   
            error: function(data) {
                console.log(data);
                errorMessage('Something went wrong');
            }
        });
    } else {
        successMessage('Cancelled');
    }

}

function generateMarkers(item) {
    if(item[13] && item[13] !== 0) {
        positions.push({lat: item[13], lng: item[14], timestamp: item['timestamp'], tilt: item[4], tilt_relative: item[5], pressure: item[21], vibration: item[1], state: item[0] });
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
    // map.setCenter(positions[i]);*/
    
    // activeMarker = new google.maps.Marker({
    //     position: new google.maps.LatLng(positions[i]),
    //     icon: activeIcon,
    //     map: map
    // });

    } else {
        if (i == 0) {
            console.log(positions[i]);
            iconR = runIcon;
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
                let point = positions[i];
                console.log(point);
                const main_info = document.createElement("div");
                main_info.className = "p-3";
                main_info.innerHTML ='<div class="row"><span class="col-6">Vibration</span><span class="col-5">'+point.vibration+'</span></div>';
                main_info.innerHTML +='<div class="row"><span class="col-6">Tilt</span><span class="col-5">'+point.tilt+'</span></div>';
                main_info.innerHTML +='<div class="row"><span class="col-6">Tilt relative</span><span class="col-5">'+point.tilt_relative+'</span></div>';
                main_info.innerHTML +='<div class="row"><span class="col-6">Pressure</span><span class="col-5">'+point.pressure+'</span></div>';
                main_info.innerHTML +='<div class="row"><div class="col-12"><button class="btn-7r" onclick="removePosition('+i+');">Remove position</button></div></div>';

                infowindow.setContent('<div class="row"><strong>Readings at '+positions[i].timestamp+'</strong></div><hr class="m-0">' + main_info.innerHTML);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
}

function removePosition(index) {
    console.log(positions[index])
    
    $.ajax({
        url: "/admin/irrigation/removepoistion",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serialnumber,
            "point": positions[index],
            "_token": token,
        },
        success: function(data) {
            console.log(data);
            successMessage('Success');
            
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong');

        }
    });
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
    errorMessage('The markers position is not changed...');
    console.log("Clicked");
}

google.maps.event.addDomListener(window, "load", initMap);
var infowindow = new google.maps.InfoWindow();
    function infoCallback(infowindow) {
        return function() {
        infowindow.open(map);
    };
}

function generateTable(data) {
    const dataSet = data;
    console.log(data);
    var table = $('#logtable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        data: dataSet,
        pageLength: 100, // Number of entries
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
            { title: "Timestamp",
            data: "timestamp",
            defaultContent: "<i>Not set</i>"},
            { title: "State",
            data: "0",
            defaultContent: "<i>Not set</i>"},
            { title: "Vib", 
            data: "1",
            defaultContent: "<i>Not set</i>"},
            { title: "Waterlost",
            data: "2",
            defaultContent: "<i>Not set</i>" },
            { title: "Tilt Alert",
            data: "3",
            defaultContent: "<i>Not set</i>"},
            { title: "Tilt Abs",
            data: "4",
            defaultContent: "<i>Not set</i>" },
            { title: "Tilt Rel",
            data: "5",
            defaultContent: "<i>Not set</i>" },            
            { title: "Btn",
            data: "9",
            defaultContent: "<i>Not set</i>" },
            { title: "Temp",
            data: "10",
            defaultContent: "<i>Not set</i>" },
            { title: "RH",
            data: "11",
            defaultContent: "<i>Not set</i>" },
            { title: "Lat",
            data: "13",
            defaultContent: "<i>Not set</i>" },            
            { title: "Lng",
            data: "14",
            defaultContent: "<i>Not set</i>" },
            { title: "Vbat",
            data: "15",
            defaultContent: "<i>Not set</i>" },
            { title: "Bar",
            data: "21",
            defaultContent: "<i>Not set</i>" },
            { title: "Velocity",
            data: "22",
            defaultContent: "<i>Not set</i>" },
            { title: "RSSI",
            data: "16",
            defaultContent: "<i>Not set</i>" },
        ],
    });
}

</script>
@endsection