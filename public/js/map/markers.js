var startIcon = new google.maps.MarkerImage("../img/irrigation/start.png", null, null, null, new google.maps.Size(54,85.5));
var endIcon = new google.maps.MarkerImage("../img/irrigation/finish.png", null, null, null, new google.maps.Size(54,85.5));
var poiIcon = new google.maps.MarkerImage("../img/irrigation/sms.png", null, null, null, new google.maps.Size(54,85.5));
var activeIcon = new google.maps.MarkerImage("../img/irrigation/current.svg", null, null, new google.maps.Point(25, 25), new google.maps.Size(50,50));
var runIcon = { path: google.maps.SymbolPath.CIRCLE, scale: 2, strokeOpacity: 0.8, strokeColor:'#214466' };// strokeColor: '#FF0000', fillColor: '#FF0000' };

function getLatestRun(serialnumber) {
  $.ajax({
    url: "/irrigationrun/" + serialnumber,
    type: 'GET',
    data: {
    "_token": token,
    },

    success: function (data) {
      console.log(data);
      if (data != -1) {
        for (var i in data) {
          positions.push({lat: data[i].lat, lng: data[i].lng, timestamp: data[i].timestamp, vibration: data[i].vibration});
        }
        addMarkers();
      }
    },
  })
}
  
function addEndMarker(currentLatLng) {
  endMarker = new google.maps.Marker({
    position: currentLatLng,			//markerLatLon[i][1], markerLatLon[i][2]),
    draggable: true,
    icon: endIcon,
    map: map
  });
  latlngbounds.extend(endMarker.position);
  if (count > 0) {
    latlngs.push(firstpoint);
    latlngs.push(currentLatLng);
    irrigationpath.setMap(map);
    irrigationpath.setPath(latlngs);
    if (count > 1) {
      /*if (activeMarker) {
        snap = new SnapToRoute(map, activeMarker, irrigationpath);
      }*/
      // console.log(snap.lat());
      // activeMarker.setMap(map);
    }
    /*var newdistance = google.maps.geometry.spherical.computeDistanceBetween(latlngs[1],latlngs[0]);
    document.getElementById("meter").value = newdistance.toFixed(0);*/
  }
  
  google.maps.event.addListener(endMarker, 'drag', function (e) {
    // Set the new position of the marker as it drags
    //this.setPosition(e.latLng);
    // Update the path
    if (count > 0) {
      latlngs[1] = e.latLng;
      //irrigationpath.setPath(latlngs);

      var newdistance = google.maps.geometry.spherical.computeDistanceBetween(latlngs[1],latlngs[0]);
      console.log( newdistance.toFixed(0));
      document.getElementById("meter").innerHTML = newdistance.toFixed(0);

    }
    console.log("drag");
  });

  google.maps.event.addListener(endMarker, 'dragend', function (e) {
    console.log("dragend");
    showContextMenu(e.latLng);
  });
}
  
function addMarkers(){
  for (i = positions.length - 1; i >= 0; i--) {
    let zindex = 5;
    if (i == 0) {
      iconR = startIcon;
    } else if (i == positions.length-1){
      activepoint = positions[i];
      iconR = activeIcon;
      map.setCenter(positions[i]);
      setIrrigationpath();
      activeMarker = new google.maps.Marker({
        position: new google.maps.LatLng(positions[i]),
        icon: runIcon,
        zIndex: 5,
        map: map
      }); 
      zindex = 50;

    } else {
      iconR = runIcon;
    }

    marker = new google.maps.Marker({
      position: new google.maps.LatLng(positions[i]),
      icon: iconR,
      zIndex: zindex,
      map: map
    });
    
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
      /*var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[positions.length-1]));
      if (i > 0) var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[i-1]));
      var then = positions[positions.length-1].timestamp;
      var now = positions[i].timestamp;
      var diff = moment.duration(moment(now).diff(moment(then)));
      var mt = (distance / (diff/1000))*3600;


      var back;
      if (count > 0) back = 0;
      else back = positions.length;

      var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i-back]), new google.maps.LatLng(positions[positions.length-1]));
      var then2 = positions[positions.length-1].timestamp;
      var now2 = positions[i-back].timestamp;
      var diff2 = moment.duration(moment(now2).diff(moment(then2)));
      var mt2 = (distance2 / (diff2/1000))*3600;*/

      infowindow.setContent(positions[i].timestamp+ '<br> Vibration: ' + positions[i].vibration);
      
      infowindow.open(map, marker);
      }
    })(marker, i));
  }
  getPointOfInterest();
}
  
  function addPOI(lat, lon, markerId) {
    if (!endMarker){
      return;
    }
    
    polypath = new Array(
      new google.maps.LatLng(positions[positions.length-1]),
      endMarker.position
    );

    console.log(polypath);

    var inBetween;
    if (lat != 0) {
      inBetween = new google.maps.LatLng(lat, lon);
    } else {
      inBetween = new google.maps.geometry.spherical.interpolate(polypath[0], polypath[1], 0.5);
      console.log('inBetween = ' + inBetween);
    }
    console.log("Adding POI Marker");
    if (markerId == 1) {
      poiMarker1 = new google.maps.Marker({
        position: inBetween,			//markerLatLon[i][1], markerLatLon[i][2]),
        draggable: true,
        icon: poiIcon,
        map: map
      });
      latlngbounds.extend(poiMarker1.position);
      poiMarker1.setMap(map);
  
      snapToRoute1 = new SnapToRoute(map, poiMarker1, polypath);
  
      google.maps.event.addListener(poiMarker1, 'dragend', function (e) {
        console.log("POI 1: dragend");
        updatePOImarker(1);
      });
  
    } else if (markerId == 2) {
      poiMarker2 = new google.maps.Marker({
        position: inBetween,			//markerLatLon[i][1], markerLatLon[i][2]),
        draggable: true,
        icon: poiIcon,
        map: map
      });
      latlngbounds.extend(poiMarker2.position);
      
      poiMarker2.setMap(map);
      snapToRoute2 = new SnapToRoute(map, poiMarker2, polypath);
  
      google.maps.event.addListener(poiMarker2, 'dragend', function (e) {
        console.log("POI 2: dragend");
        updatePOImarker(2);
      });
    }
  }
  
  function updatePOImarker (markerId) {
    if (markerId == 1)
    {
      if (!poiMarker1) return;
    }
    else if (markerId == 2)
    {
      if (!poiMarker2) return;
    }
    else if (markerId == 0)
    {
      return;
    }
  
    console.log("Marker ID = " + markerId);
  
    if (polyline)
    {
      polyline.setMap(null);
  
      polypath = new Array(
        new google.maps.LatLng(positions[positions.length-1]),
        endMarker.position
      );
  
  
      if (markerId == 1)
      {
        snapToRoute1 = new SnapToRoute(map, poiMarker1, polypath);
      }
      else if (markerId == 2)
      {
        snapToRoute2 = new SnapToRoute(map, poiMarker2, polypath);
      }
    }
  }
  
  function setIrrigationpath(){
    if (count > 4) {
        if (firstpoint && activepoint) {
        activelngs.push(firstpoint);
        activelngs.push(activepoint);
        irrigationpath2.setMap(map);
        irrigationpath2.setPath(activelngs);
      }
    }
  }
  
  function update_point(lat, lng, point_id) {
    var newdistance = 0;
    
    if (point_id == 0) {
      $('.contextmenu').remove();
      if (count > 0) {
        var currentLatLng = new google.maps.LatLng(lat,lng);
        newdistance = google.maps.geometry.spherical.computeDistanceBetween(currentLatLng, firstpoint);
        console.log("distance = "+newdistance.toFixed(0));
      }
    } else {
      $(".contextmenuPOI").remove();
    }

    $.ajax({
      url: "/updatePoint",
      type: 'POST',
      data: { 
        "lat": lat,
        "lng": lng,
        "serial": serial, 
        "distance": newdistance,
        "point_id": point_id,
        "_token": token,
      },
      success: function(msg) {
        console.log(msg);  
        window.location = "/include/view_irrigation.php?unit="+serial;
      },   
      error:function(msg) {
      }
    });
  
    if (lat == 0)
    {
      closeContextmenu();
    }
  }

  function getDistance(){
    if(firstpoint && endpoint) {
      var newdistance = google.maps.geometry.spherical.computeDistanceBetween(endpoint,firstpoint);
      if(newdistance.toFixed(0) < 2000) {
        document.getElementById("meter").innerHTML = newdistance.toFixed(0);
      }
    }
  }