@extends('layouts.app')

@section('content')

<script src='{{asset('js/fullcalendar.min.js')}}'></script>

<div class="card-rounded p-3 bg-white">
    <div id="calendar" style="color: black"></div>
</div>

<div class="modal fade" id="updateUnit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-a-grey">
      <div class="modal-body">
        <div class="row">
          <div class="col-9">
            <h2 class="modal-title fc-title" id="eventtitle"></h2>
          </div>
          <div class="col-3 text-end"> 
            <span id="closeModal" onclick="$('#updateUnit').modal('hide');" data-dismiss="modal"> <i class="fa fa-lg fa-times"></i> </span> 
          </div>
        </div>
        <div id="irrigationcontent" class="mt-1 mb-1">
          <div class="border-7s p-3 mb-2">
            <div class="row">
              <div class="col-12">
                <h4 class="text-center">@lang('general.summary')</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <span>@lang('general.totaldistance')</span>
              </div>
              <div class="col-6">
                <span id="totalMeters"></span>
              </div>
            </div>
            <hr class="my-1">
            <div class="row">
              <div class="col-6">
                <span>@lang('general.time')</span>
              </div>
              <div class="col-6">
                <span id="totalTime"></span>
              </div>
            </div>
            <hr class="my-1">
            <div class="row">
              <div class="col-6">
                <span>ID #</span>
              </div>
              <div class="col-6">
                <span id="runid"></span>
              </div>
            </div>
          </div>
          <div class="card-rounded" id="map_1" style="height: 500px; position:relative; width:100%;"></div>
        </div>
      </div>
  </div>
</div>

<script>
const user = @json(Auth::user());
setTitle('Calendar');
getIrrigationLog();
function getIrrigationLog() {    
  $.ajax({
    url: "/irrigation/run",
    type: 'GET',
    dataType: "json",
    contentType: "application/json; charset=utf-8",
    
    success: function (data) { 
      successMessage('Loading');
      let logs = Array();
      let index = 0;
      console.log(data);
      for(let i in data) {
        let unit = data[i];
        for (let j in unit) {
          let stoptime;
          logs[index] = {
            id: unit[j].log_id,
            title: unit[j].irrigation_run_id + ": " + unit[j].serialnumber,
            start: unit[j].irrigation_starttime,
            end: unit[j].irrigation_endtime,
            allDays: true,
            hours: 10,
            backgroundColor: getColor(i),
            borderColor: getColor(i),
          };
          index++;
        }
      }
      initCalendar(logs);
      console.log(logs);
      var quantity = $('.fc-event').length;
      console.log(quantity);
    },
    error: function (data) {
        errorMessage('Failed');
    }
  });
}

function initCalendar(irrigationEvents) {
  let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
    themeSystem: 'bootstrap5',
    headerToolbar: {
      start: '',
      center: 'title',
      end: 'prev,today,next',
    },
    buttonText: {
      today: '  Today  ',
    },
    selectable: true,
    initialView: 'dayGridMonth',
    eventTimeFormat: {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false
    },
    contentHeight: 800,
    eventOrder: "title",
    eventDisplay: "block",
    firstDay: 1,
    eventClick: function (info) {
      console.log(info.event.id);
      document.querySelector('#eventtitle').innerText = info.event.title;
      removeMarkers();
      $.ajax({ 
        url: '/irrigation/runlog/'+info.event.id,
        dataType: 'json',      
        success: function( data ) {
          console.log(data)
          removeMarkers();
          document.getElementById("runid").innerText = data.run.irrigation_run_id + " ("+ data.run.log_id+") ";

          startpoint = data.run.irrigation_startpoint.split(",");
          endpoint = data.run.irrigation_endpoint.split(",");
          var starttime = new Date(data.run.irrigation_starttime).getTime();
          var endtime = new Date(data.run.irrigation_endtime).getTime();

          let totaltime = toHoursAndMinutes((endtime-starttime)/1000);
          if(user.user_language == 1) {
            document.getElementById("totalTime").innerText = totaltime.h +" timer " + totaltime.m + " minutter";
          } else {
            document.getElementById("totalTime").innerText = totaltime.h +" hours " + totaltime.m + " minutes";
          }
          setPoints(startpoint, endpoint);
        },
        error: function( data ) {
            errorMessage(@json(__('general.somethingwentwrong')));
            console.log(data);
        }
      });

      $('#updateUnit').modal('show');
    },
    events: irrigationEvents

  });
  calendar.render();
}

function getColor(input) {
  let color = input % 4;
  if (color == 0) return '#a7c49d';
  else if (color == 1) return '#00265a';
  else if (color == 2) return '#efa6a5';
  else if (color == 3) return '#fed16d';
  else return '#f3f2f1';
}

var positions = Array();
var latlngs = Array();
var endMarker;
var marker;
var startMarker;
var endMarker;
var startpoint;
var endpoint;
var log_id;
var oldrungreen;
let serialnumber;

function initMap() {
    map = new google.maps.Map(document.getElementById('map_1'), {
        center: {lat:59.4,lng:10.4},
        mapTypeId: 'satellite',
        mapTypeControl: true,
        zoom: 17,
        streetViewControl: false,
        tilt: 0
    });
}

function setPoints(itemstart, itemstop) {
  var bounds = new google.maps.LatLngBounds();
    endMarker = new google.maps.Marker({
        position: new google.maps.LatLng(itemstop[0],itemstop[1]),
        draggable: true,
        icon: new google.maps.MarkerImage("/img/irrigation/finish.png", null, null, null, new google.maps.Size(54,85.5)),
        map: map
    });
    console.log("Endpoint added with: " + itemstop);
    google.maps.event.addListener(endMarker, 'drag', function (e) {
        console.log("drag");
    });
    google.maps.event.addListener(endMarker, 'dragend', function (e) {
        console.log("dragend");
        console.log(e.latLng);
        showContextMenu(e.latLng);
    });

    bounds.extend(endMarker.position);

    startMarker = new google.maps.Marker({
        position: new google.maps.LatLng(itemstart[0],itemstart[1]),
        draggable: true,
        icon: new google.maps.MarkerImage("/img/irrigation/start.png", null, null, null, new google.maps.Size(54,85.5)),
        map: map
    });
    console.log("Startpoint added with: " + itemstart);
    google.maps.event.addListener(startMarker, 'drag', function (e) {
        console.log("Drag startMarker");
    });
    google.maps.event.addListener(startMarker, 'dragend', function (e) {
        console.log("dragend for startmarker");
        console.log(e.latLng);
        showContextMenuStart(e.latLng);
    });
    bounds.extend(startMarker.position);

    oldrungreen = new google.maps.Polyline({
        path: [startMarker.position, endMarker.position],
        geodesic: true,
        strokeColor: '#a7c49d',
        strokeOpacity: 0.8,
        strokeWeight: 33,
        zIndex: 2,
        map: map
    });
    
    var distance = google.maps.geometry.spherical.computeDistanceBetween(startMarker.position, endMarker.position);
    if(user.measurement == 2) {
      var total_distance = distance *3.28084;
      document.getElementById("totalMeters").innerText = total_distance.toFixed(0) +" ft";
    } else {
      document.getElementById("totalMeters").innerText = distance.toFixed(0) +" m";
    }

    map.setCenter(bounds.getCenter());
}

function removeMarkers() {
  if (startMarker) {
    startMarker.setMap(null);
  }

  if(endMarker) {
    endMarker.setMap(null);
  }
  if (oldrungreen) {
    oldrungreen.setMap(null);
  }
}

function toHoursAndMinutes(totalSeconds) {
  const totalMinutes = Math.floor(totalSeconds / 60);

  const seconds = totalSeconds % 60;
  const hours = Math.floor(totalMinutes / 60);
  const minutes = totalMinutes % 60;

  return { h: hours, m: minutes, s: seconds };
}


</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry&callback=initMap" defer></script>

@endsection