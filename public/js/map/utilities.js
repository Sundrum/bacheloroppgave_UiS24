var map;
var endMarker;
var poiMarker1;
var poiMarker2;
var latlngs = Array();
var latsnlngs = Array();
var activelngs = Array();
var previous_distance, change_lat, change_lon, newmedianpath, marker, i, iconR;
var firstpoint =  new google.maps.LatLng(0,0);
var endpoint = new google.maps.LatLng(0,0);
var activepoint = new google.maps.LatLng(0,0);
var polyline = Array();
var oldruns = Array();
var polypath, oldrungreen, oldrunorange;
var snapToRoute1 = null, snapToRoute2 = null;
var latlngbounds = new google.maps.LatLngBounds();
var coordinates = Array();


var irrigationpath = new google.maps.Polyline({
	path: latlngs,
	geodesic: true,
	strokeColor: '#214466',
	strokeOpacity: 0.8,
	strokeWeight: 2
});

var irrigationpath2 = new google.maps.Polyline({
	path: activelngs,
	geodesic: true,
	strokeColor: '#00265a',
	strokeOpacity: 0.8,
	strokeWeight: 33
});


function medianPoint(points, offset, count)
{
	var length = points.length;

	var pointsLat = [];
	var pointsLng = [];

	while(count > 0)
	{

		pointsLat.push(points[offset]["lat"]);
		pointsLng.push(points[offset]["lng"]);

		offset++;
		count--;
	}
	pointsLat.sort(function(a, b){return a-b});
	pointsLng.sort(function(a, b){return a-b});


	var medianLat, medianLng;

	var Nmid = Math.trunc(pointsLat.length / 2);

	if (Nmid % 2 == 0)	// Even number of elements in the array
	{
		medianLat = pointsLat[Nmid];
		medianLat += pointsLat[Nmid - 1];
		medianLat /= 2;

		medianLng = pointsLng[Nmid];
		medianLng += pointsLng[Nmid - 1];
		medianLng /= 2;
	} else
	{
		medianLat = pointsLat[Nmid];
		medianLng = pointsLng[Nmid];
	}

	var latlng = "medianLat = " + medianLat + ", medianLng = " + medianLng;
	return [ medianLat, medianLng ];
}


function meadianLine(pointsArray, m)
{
	var size, top, middle, bottom, medianTop, medianMiddle, medianBottom;
	var points = Math.round(m/3);

	size = pointsArray.length;

	top = points;
	middle = m - (points*2);
	bottom = points;

	   medianTop = medianPoint(pointsArray, size - top,  top);
	medianMiddle = medianPoint(pointsArray, size - top - middle,  middle);
	medianBottom = medianPoint(pointsArray, size - top - middle - bottom, bottom);

	var slope = (medianTop[0] - medianBottom[0]) / (medianTop[1] - medianBottom[1]);
	console.log("Slope = " + slope);

	if (irrigationpath != null)
	{	irrigationpath.setMap(null);
		irrigationpath = null;
	}

	irrigationpath = new google.maps.Polyline({
		path: [],
		geodesic: true,
		strokeColor: '#FFFFFF',
		strokeOpacity: 1.0,
		strokeWeight: 2
	});

	var newPoint;
	newPoint = new google.maps.LatLng(medianBottom[0], medianBottom[1]);
	irrigationpath.getPath().push(newPoint);
	newPoint = new google.maps.LatLng(medianMiddle[0], medianMiddle[1]);
	irrigationpath.getPath().push(newPoint);
	newPoint = new google.maps.LatLng(medianTop[0], medianTop[1]);
	irrigationpath.getPath().push(newPoint);

	var intercept = ((medianBottom[0] - (slope*medianBottom[1])) + (medianMiddle[0] - (slope*medianMiddle[1])) + (medianTop[0] - (slope*medianTop[1]))) / 3;
	console.log("Intercept = ", intercept);

	if (newmedianpath != null)
	{	newmedianpath.setMap(null);
		newmedianpath = null;
	}
	newmedianpath = new google.maps.Polyline({
		path: [],
		geodesic: true,
		strokeColor: '#00FFFF',
		strokeOpacity: 1.0,
		strokeWeight: 4
	});

	var xy;

	if ((slope > -4.0) || (slope < 4.0))
	{
		xy = new google.maps.LatLng(intercept+slope*pointsArray[0]["lng"], pointsArray[0]["lng"]);
		newmedianpath.getPath().push(xy);
		xy = new google.maps.LatLng(intercept+slope*pointsArray[size-1]["lng"], pointsArray[size-1]["lng"]);
		newmedianpath.getPath().push(xy);		// xy.lat(); xy.lng();

		console.log("Choosing first version");
	} else
	{
		var new_lng;

		new_lng = (pointsArray[0]["lat"] - intercept) / slope;
		xy = new google.maps.LatLng(pointsArray[0]["lat"], pointsArray[0]["lng"]);
		newmedianpath.getPath().push(xy);

		new_lng = (pointsArray[size-1]["lat"] - intercept) / slope;
		xy = new google.maps.LatLng(pointsArray[size-1]["lat"], pointsArray[size-1]["lng"]);
		newmedianpath.getPath().push(xy);		// xy.lat(); xy.lng();

		console.log("Choosing second version");
	}

	newmedianpath.setMap(map);
}

function searchKeyPress(e)
{
    // look for window.event in case event isn't passed in
    e = e || window.event;
    if (e.keyCode == 13)
    {
        document.getElementById('btnSearch').click();
        return false;
    }
    return true;
}

function showContextMenu(currentLatLng)
{
	if (endMarker)
	{
		irrigationpath.setMap(null);
		endMarker.setPosition(currentLatLng);
	} else
	{
		addEndMarker(currentLatLng);
	}
	var lat = currentLatLng.lat();
	var lon = currentLatLng.lng();
	var projection;
	var contextmenuDir;
	projection = map.getProjection();
	$('.contextmenu').remove();
	contextmenuDir = document.createElement("div");
	contextmenuDir.className  = 'contextmenu';
	contextmenuDir.innerHTML = "<div style='padding-left:5px; padding-top:5px; padding-bottom:5px;'> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='update_point("+lat+","+lon+",0);'> <img class='pull-left' src='../img/flag_destination_50x50.png' width='30'>&nbsp&nbsp " + setEndpoint +"<\/a> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='deleteContextmenu_endMarker();'> <img class='pull-left' src='../img/trash_lg.png' width='30'>&nbsp&nbsp " + delEndpoint +"<\/a> \
									<button type='button'style='margin-top: 1px; margin-right: 2px; position:absolute; top:0; right:0;' onclick='closeContextmenu_endMarker();' class='btn-md btn-rounded' aria-label='Close'> \
									<span aria-hidden='true'>&times;</span></button> \
								<\/div>";
	$(map.getDiv()).append(contextmenuDir);
	setMenuXY(currentLatLng, '.contextmenu');
	contextmenuDir.style.visibility = "visible";
}

function showContextMenuPOI(currentLatLng, point_id)
{	
	if (poiMarker1) {
	
	} else if (poiMarker2) {

	} else {
		addPOI(currentLatLng, point_id);
	}
	var lat = currentLatLng.lat();
	var lon = currentLatLng.lng();
	
	var projection;
	var contextmenuDir;
	projection = map.getProjection();
	$('.contextmenuPOI').remove();
	contextmenuDir = document.createElement("div");
	contextmenuDir.className  = 'contextmenuPOI';
	contextmenuDir.innerHTML = "<div style='padding-left:5px; padding-top:5px; padding-bottom:5px;'> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='update_point("+lat+","+lon+","+point_id+");'> <img class='pull-left' src='../img/irr_sms_varsling.png' width='30'>&nbsp&nbsp " + setPOI + " "+point_id+"<\/a> \
									<a class='btn btn-normal' id='menu1' href='#' onclick='deleteContextmenu_POI("+point_id+");'> <img class='pull-left' src='../img/trash_lg.png' width='30'>&nbsp&nbsp " + delPOI + "<\/a> \
									<button type='button'style='margin-top: 1px; margin-right: 2px; position:absolute; top:0; right:0;' onclick='closeContextmenu_POI();' class='btn-md btn-rounded' aria-label='Close'> \
									<span aria-hidden='true'>&times;</span></button> \
								<\/div>";
	$(map.getDiv()).append(contextmenuDir);
	setMenuXY(currentLatLng, '.contextmenuPOI');
	contextmenuDir.style.visibility = "visible";
}

function closeContextmenu_endMarker() {
	$('.contextmenu').remove();
	window.location.href="/include/view_irrigation.php?unit="+serial;
}

function closeContextmenu_POI() {
	$('.contextmenuPOI').remove();
	window.location.href="/include/view_irrigation.php?unit="+serial;
}

function deleteContextmenu_endMarker() {
	$('.contextmenu').remove();
	endMarker.setMap(null);
	update_point(0,0,0);
	endMarker = 0;
	irrigationpath.setMap(null);
	latlngs.pop();
	//document.getElementById("meter").value = 0;
}

function deleteContextmenu_POI(markerId) {
	$('.contextmenuPOI').remove();
	if (markerId == 1) {
		update_point(0,0,1);
	} else if (markerId == 2) {
		update_point(0,0,2);
	}
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

function autoSizing(){
	
	//Get the boundaries of the Map.
        var bounds = new google.maps.LatLngBounds();
	//map.setCenter(latlngbounds.getCenter());
	//map.fitBounds(latlngbounds); // Auto Zoom
	if ((count > 1) && !endMarker) {
		map.setCenter(latlngbounds.getCenter());
		map.setZoom(17);
	}

	if ((firstpoint && endMarker) && count > 0) {
        	//Center map and adjust Zoom based on the position of all markers.
		var zoomDistance = google.maps.geometry.spherical.computeDistanceBetween(firstpoint, endpoint);
		map.setCenter(latlngbounds.getCenter());
        	
		if (zoomDistance == 0) {
			map.setZoom(16);
		} else if (zoomDistance < 60) {
			map.setZoom(19);
		} else if (zoomDistance < 150) {
			map.setZoom(18);
		} else if (zoomDistance < 600) {
			map.setZoom(17);
		} else if (zoomDistance < 5000) {
			map.setZoom(14);
		} else {
			map.fitBounds(latlngbounds); // Auto Zoom
		}
	}
}