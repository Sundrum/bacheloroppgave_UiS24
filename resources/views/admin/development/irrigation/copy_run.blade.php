@extends('layouts.app')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      /* Optional: Makes the sample page fill the window. */
      .fill-container {
        display: grid;
        height: 100%;
        width: 100%;
        grid-template-columns: 175px 125px 1fr;
        grid-template-rows: 80px 1fr;
        grid-template-areas:"logo nav head"
                            "side side main"
      }

      .select {
        position: relative;
        display: block;
        width: 70%;
        margin: 0 auto;
        font-family: 'Open Sans', 'Helvetica Neue', 'Segoe UI', 'Calibri', 'Arial', sans-serif;
        font-size: 15px;
        color: #60666d;
      }
      #run-button {
        position: relative;
        margin: 0 auto;
      }
      p {
        text-align: left;
        margin-left: 45px;
        margin-bottom: 5px;
        font-size: 17px;
      }
      .button {
        border-radius: 5px;
        border: 0.1em solid #474747;
        background-color: #ffffff;
        cursor: pointer;
        color: #474747;
        font-family: roboto, sans-serif;
        font-size: 17px;
        padding: 10px 25px;
        margin-top: 10px;
        text-decoration:none;
        transition-duration: 0.1s;
       }
      .button:hover {
        color: white;
        background-color: #474747;
      }
      #cr {
        opacity: 40%;
        text-decoration: underline;
      }
    </style>

@section('content')
    <script>
      let map;

      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: 0, lng: 0 },
          zoom: 2,
          mapTypeId: "satellite",
        });
      }
    </script>
<div class="row fill-container">
    <div class="col-2 bg-white">
        <div class="col-12 text-center">
            <h2>Select run: </h2>
        </div>
        <div class="col-12">
            <p>Customer:</p>
            <select name="customer" id="main-menu" class="select">
            <option value="" disabled selected>Customer</option>
            </select>
        </div>
        <div class="col-12">
            <p>Sensor:</p>
            <select name="sensor" id="sub-menu" class="select">
            <option value="" disabled selected>Sensor</option>
            </select>
        </div>
        <div class="col-12">
            <p>Time:</p>
            <select name="time" id="time-sub-menu" class="select">
            <option value="" disabled selected>Time</option>
            </select>
        </div>
        <div class="col-12">
            <p>Delay:</p>
            <select name="delay" id="delay-sub-menu" class="select">
            <option value="" disabled selected>Delay</option>
            </select>
        </div>
        <div class="col-12">
            <div class="row justify-content-center">
                <button class="button" onClick="placeMarker()">Run</button>
            </div>
        </div>
    </div>
    <div class="col-10">
        <div id="map"></div>
    </div>
</div>
 
<script>
var token = "{{ csrf_token() }}";
var customerName = @json($customerName);
var sensorData = @json($sensorData);
// Getting the main and sub menus
var main = document.getElementById('main-menu');
var sub = document.getElementById('sub-menu');
var time = document.getElementById('time-sub-menu');
var delay = document.getElementById('delay-sub-menu');
var run = document.getElementById('run-button');
var startEndMarker = new Array();
var midMarker = new Array();
var marker = new Array();
var line;
var constLine;
var startLat = null;
var startLng = null;
var endLat = null;
var endLng = null;
var endTime = null;

  
  customerName.forEach(function(el){
    let optionName = new Option(el['name'], el['name']);
    // Append the child option in sub menu
    main.appendChild(optionName);
  });
  function addDelayOptions() {
    // Delay in milliseconds
    // Everything over 1 second should be considered useless for testing purposes, and maybe also for showing off runs.
    // 0.005 sec
    delay.appendChild(new Option("0.005 sekunder", "5"));
    // 0.01 sec
    delay.appendChild(new Option("0.01 sekund", "10"));
    // 0.015 sec
    delay.appendChild(new Option("0.015 sekunder", "15"));
    // 0.02 sec
    delay.appendChild(new Option("0.02 sekunder", "20"));
    // 0.05 sec
    delay.appendChild(new Option("0.05 sekunder", "50"));
    // 0.1 sec
    delay.appendChild(new Option("0.1 sekund", "100"));
    // 0.5 sec
    delay.appendChild(new Option("0.5 sekunder", "500"));
    // 1 sec
    delay.appendChild(new Option("1 sekund", "1000"));
    // 2 sec
    delay.appendChild(new Option("2 sekunder", "2000"));
    // 5 sec
    delay.appendChild(new Option("5 sekunder", "5000"));
    // 10 sec
    delay.appendChild(new Option("10 sekunder", "10000"));
    // 15 sec
    delay.appendChild(new Option("15 sekunder", "15000"));
    // 20 sec
    delay.appendChild(new Option("20 sekunder", "20000"));
  }
  addDelayOptions();
  // Trigger the Event when main menu change occurs
  main.addEventListener('change',function(){
    // Getting a selected option
    var selectedName = document.getElementById("main-menu").value;
    // Removing the sub menu options using while
    while(sub.options.length > 0){
      sub.options.remove(0);
    }
    while(time.options.length > 0){
      time.options.remove(0);
    }
    let defaultSensor = new Option("Sensor");
    sub.appendChild(defaultSensor);
    defaultSensor.disabled = true;
    let defaultTime = new Option("Time");
    time.appendChild(defaultTime);
    defaultTime.disabled = true;
    var checkExist = false;
    for(var i = 0; i < customerName.length; i++){
      if(selectedName == customerName[i]['name']){
        for(var j = 0; j < customerName[i]['sensor_id'].length; j++){
          for(var o = 0; o < sub.length; o++){
            if(sub.options[o].value == customerName[i]['sensor_id'][j]){
              checkExist = true;
            }
          }
          if(!checkExist){
            let optionSensor = new Option(customerName[i]['sensor_id'][j],customerName[i]['sensor_id'][j]);
            // Append the child option in sub menu
            sub.appendChild(optionSensor);
          }
          checkExist = false;
        }
      }
    }
  });
  // Triggers when the sub-manu changes
  sub.addEventListener('change',function(){
    // Getting a selected option
    var selectedSensor = document.getElementById("sub-menu").value;
    // Removing the sub menu options using while
    while(time.options.length > 0){
      time.options.remove(0);
    }
    let defaultTime = new Option("Time");
    time.appendChild(defaultTime);
    defaultTime.disabled = true;
    var checkExist = false;
    for(var i = 0; i < Object.keys(sensorData).length; i++){
      if(selectedSensor.trim() == sensorData[i][1]['sensor_id'].trim()){
        checkExist = true;
        for(var j = 0; j < Object.keys(sensorData[i]).length; j++){
          var allTime = sensorData[i][j+1]['irrigation_starttime'];
          var timeToString = allTime.toString();
          var timeSplit = timeToString.split(" ");
          var lastTimeSplit = timeSplit[1].split(".");
          var showTime = "Date: " + timeSplit[0] + " Time: " + lastTimeSplit[0];
          let optionTime = new Option(showTime,sensorData[i][j+1]['irrigation_starttime']);
          // Append the child option in sub menu
          time.appendChild(optionTime);
        }
      }
    }
    
    if(!checkExist){
      let noValue = new Option("No data");
      time.appendChild(noValue);
      noValue.disabled = true;
    }
  });

  function placeMarker(){
    if(main.value == "" || main.value == "Customer" || sub.value == "" || sub.value == "Sensor" || time.value == "" || time.value == "Time" || delay.value == "" || delay.valu == "Delay"){
      window.alert("Fill every field");
    }
    else{
      for(i = 0; i < sensorData.length; i++){
        for(j = 0; j < Object.keys(sensorData[i]).length; j++){
          if(sub.value == sensorData[i][j+1]['sensor_id'].trim() && time.value == sensorData[i][j+1]['irrigation_starttime'].trim()){
            startLat = sensorData[i][j+1]['irrigation_startpoint']['lat'];
            startLng = sensorData[i][j+1]['irrigation_startpoint']['lng'];
            if(sensorData[i][j+1]['irrigation_endpoint']['lat'] != null && sensorData[i][j+1]['irrigation_endpoint']['lng'] != null){
              endLat = sensorData[i][j+1]['irrigation_endpoint']['lat'];
              endLng = sensorData[i][j+1]['irrigation_endpoint']['lng'];
            }
            if(sensorData[i][j+1]['irrigation_endtime'] != null) {
              endTime = sensorData[i][j+1]['irrigation_endtime'];
            }
            var sensor = sub.value;
            var startTime = time.value;
            getData(sensor, startTime, endTime);
          }
        }
      }
    }
  }

function getData(sensor, startTime, endTime) {
    $.ajax({
        method: "POST",
        dataType: "json",
        url: "/admin/dev/data",
        data: { 
        sensor: sensor, 
        start: startTime, 
        end: endTime,
        _token: token 
        },
        success:function(response){
        console.log(response);
        if(response) {
            try {
            var marker = Array();
            marker = response; 
            parseCoordinates();
            cleanMap();
            addStartEndPoint();
            console.log(delay.value);
            for(o = 1; o < marker.length; o++){
                addMidLine(marker, o);
            }
            } catch (e) {
                // You can read e for more info
                // Let's assume the error is that we already have parsed the payload
                // So just return that
                console.log(e);
            }
        }
        },
    });
}
function parseCoordinates() {
    // Converting to float (from string)
    startLat = parseFloat(startLat);
    startLng = parseFloat(startLng);
    endLat = parseFloat(endLat);
    endLng = parseFloat(endLng);
}

function cleanMap() {
    // Delete startEndMarker and line if they already exist
    if(startEndMarker.length > 0){
        startEndMarker[0].setMap(null);
        startEndMarker[1].setMap(null);
        for(o=0; o < midMarker.length; o++) {
            midMarker[o].setMap(null);
        }
        line.setMap(null);
        constLine.setMap(null);
    }
}

function addStartEndPoint(){
    // Start point
    startEndMarker[0] = new google.maps.Marker({
        position: {lat: startLat, lng: startLng},
        label: "S",
        map: map,
    });
    // End point
    var endIcon =  new google.maps.MarkerImage("../img/irr_flag_destination.png", null, null, new google.maps.Point(4, 28), new google.maps.Size(120,60));
    startEndMarker[1] = new google.maps.Marker({
        position: {lat: endLat, lng: endLng},
        label: "E",
        //icon: endIcon,
        map: map,
    });
}
function addMidLine(marker, o) {
    // Mid point(s)
    setTimeout(function() {
        console.log(marker[o].lat+','+marker[o].lng + ' - O = ' +o);
        midMarker[o] = new google.maps.Marker({
            position: new google.maps.LatLng(marker[o].lat,marker[o].lng),
            label: "M",
            map: map,
        });

        // midMarker.setMap(map);
        // var startEndCoor = [
        //     { lat: startLat, lng: startLng },
        //     { lat: endLat, lng: endLng },
        // ];
        // // Const line
        // constLine = new google.maps.Polyline({
        //     path: startEndCoor,
        //     geodesic: true,
        //     strokeColor: "#679df6",
        //     strokeOpacity: 1.0,
        //     strokeWeight: 5,
        // });
        // constLine.setMap(map);
        // // Drawing line
        // if(o == 0){
        // var midMarkerCoor = [
        //     {lat: startLat, lng: startLng},
        //     {lat: marker[o].lat, lng: marker[o].lng},
        //     {lat: marker[o].lat, lng: marker[o].lng},
        // ];
        // }
        // else if (o == marker.length-1){
        // var midMarkerCoor = [
        //     {lat: startLat, lng: startLng},
        //     {lat: endLat, lng: endLng},
        // ];
        // }
        // else{
        // var midMarkerCoor = [
        //     {lat: startLat, lng: startLng},
        //     {lat: marker[o].lat, lng: marker[o].lng},
        // ];
        // }
        // if(line){
        //     line.setMap(null);
        // }
        // line = new google.maps.Polyline({
        //     path: midMarkerCoor,
        //     geodesic: true,
        //     strokeColor: "#377ef3",
        //     strokeOpacity: 1.0,
        //     strokeWeight: 10,
        // });
        // line.setMap(map);
        
    }, o * delay.value);
    
    startPos = new google.maps.LatLng(startLat,startLng);

    
    var bounds = new google.maps.LatLngBounds();
    function autoSizing() {
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
    }
    
    if (isNaN(endLat) && isNaN(startLat)) {
    }
    else if(isNaN(endLat)) {
        bounds.extend(startPos);
    
        autoSizing();
    }
    else {
        endPos = new google.maps.LatLng(endLat,endLng);
        bounds.extend(startPos);
        bounds.extend(endPos);
        
        autoSizing();
    }
}
</script>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&amp&callback=initMap&libraries=&v=weekly" async></script>
@endsection