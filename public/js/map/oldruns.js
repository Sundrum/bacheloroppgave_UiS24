function getOldRuns(serialnumber, days) {
    $.ajax({
      url: "/oldruns/" + serialnumber + "/" + days,
      type: 'GET',
      data: {
      "_token": token,
      },
  
      success: function (data) {
        if (data) {
          for (var i in data) {
            oldruns.push({startpoint_lat: data[i].startpoint_lat, startpoint_lng: data[i].startpoint_lng, endpoint_lat: data[i].endpoint_lat, endpoint_lng: data[i].endpoint_lng, run_id: data[i].run_id, starttime: data[i].starttime, green: data[i].green, days: data[i].days});
          }
          console.log(oldruns);
          addOldRuns();
        }
      },
    })
}

function addOldRuns(){
    for (t = 0; t < oldruns.length; t++) {
        coordinates.push(new google.maps.LatLng(oldruns[t].startpoint_lat, oldruns[t].startpoint_lng), new google.maps.LatLng(oldruns[t].endpoint_lat, oldruns[t].endpoint_lng));
        if (oldruns[t].green) {
            oldrungreen = new google.maps.Polyline({
                path: [coordinates[t*2], coordinates[t*2 + 1]],
                geodesic: true,
                strokeColor: '#a7c49d',
                strokeOpacity: 0.8,
                strokeWeight: 33,
                map: map
            });
    
            google.maps.event.addListener(oldrungreen, 'click', (function(oldrungreen, t) {
                return function(event) {
                    infowindow.setContent('Run: ' + oldruns[t].run_id + '<br>' + oldruns[t].days + ' days ago <br> Startime: ' + oldruns[t].starttime + '<br> Endtime: ' + oldruns[t].irrigation_endtime);
                    console.log(oldruns[t]);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map);
                }
            })(oldrungreen, t));
        } else {
            oldrunyellow = new google.maps.Polyline({
                path: [coordinates[t*2], coordinates[t*2 + 1]],
                geodesic: true,
                strokeColor: '#fed16d',
                strokeOpacity: 0.8,
                strokeWeight: 33,
                map: map
            });
    
            google.maps.event.addListener(oldrunyellow, 'click', (function(oldrunyellow, t) {
                return function(event) {
                    infowindow.setContent('<h5 class="my-0">Run: ' + oldruns[t].run_id + '</h5><br>' + oldruns[t].days + ' days ago <br> Startime: ' + oldruns[t].starttime);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map);
                }
            })(oldrunyellow, t));
        }
    }
}

function setZoomValues() {
    //if (typeof irrigationpath !== 'undefined') irrigationpath.setOptions({strokeWeight: 4});
    /*if (map.getZoom() == 16) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 24});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 24});
    } else if (map.getZoom() == 15) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 22});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 22});
    } else if (map.getZoom() == 14) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 16});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 16});
    } else if (map.getZoom() == 13) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 11});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 11});
    } else if (map.getZoom() == 12) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 11});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 11});
    } else if (map.getZoom() < 12) {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 8});
        if (typeof oldrunyellow !== 'undefined') oldrunyellow.setOptions({strokeWeight: 8});
    } else {
        if (typeof oldrungreen !== 'undefined') oldrungreen.setOptions({strokeWeight: 33});
        if (typeof oldrunyellow !== 'undefined')oldrunyellow.setOptions({strokeWeight: 33});
    }*/
}