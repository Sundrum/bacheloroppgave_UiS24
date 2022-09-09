@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

@section('content')
<h2 id="name_unit" style="display: none;">{{$unit->sensorunit_location ?? $serial}} - ({{$serial}})</h2>
<div class="row justify-content-center">
  <div class="col-md-6 col-sm-12 card-rounded bg-grey mt-2">
    <div class="row">
      <div class="col">
        <h4 class="text-center mt-2 mb-2">
          @if($unit->sensorunit_location)
            {{$unit->sensorunit_location ?? $serial}} - ({{$serial}})
          @else
            {{$serial}}
          @endif
        </h4>
      </div>
    </div>
    <hr class="mt-0 mb-0">
    @foreach($unit['probe'] as $row)
    {{-- @dd($unit['probe']) --}}
      @if($unit['dip_switch'] >= '08' && $row['unittype_id'] == 47)
        @continue
      @endif
      @if($row['probenumber'] == 14 && (Auth::user()->roletype_id_ref < 80))
        @continue
      @endif
      @if($row['probenumber'] == 15 && (Auth::user()->roletype_id_ref < 80))
        @continue
      @endif
        <div class="row">
          <div class="col">
            {{$row['unittype_description'] ?? ''}}
          </div>
          <div class="col">
            @if($row['unittype_icon']) <img class="image-responsive" src="{{ $row['unittype_icon'] }}" width="20" height="20" title="{{ $row['unittype_description'] }}" rel="tooltip" alt="" style=""> @endif
            {{-- {{round($row['value'],$row['unittype_decimals'])}} {{$row['unittype_shortlabel'] ?? ''}} --}}
            {{-- state 1 is idle state, state 2 is active state --}}
            @if($unit['state'] == 1 && ($row['probenumber'] == 0 ))
              {{'Idle, state (1)'}}
            @elseif($unit['state'] == 2 && $row['probenumber'] == 0 )
              {{'Active, state (2)'}}
            @else
              {{round($row['value'],$row['unittype_decimals'])}} {{$row['unittype_shortlabel'] ?? ''}}
            @endif
          
          </div>
        </div>
        <hr class="m-0">
    @endforeach
  </div>
  <div class="col-md-6 mt-2">
    <div class="card-rounded" id="map_1" style="min-height: 350px; position:relative; height: 100%; width:100%;"></div>
  </div>
</div>

<input type="hidden" name="lat" id="lat" value="{{$unit['lat'] ?? ''}}">
<input type="hidden" name="lng" id="lng" value="{{$unit['lng'] ?? ''}}">
<div class="row justify-content-center mt-3">
  <div class="col-md-12 card card-rounded bg-grey">
    <div class="row mb-3 mt-3">
        <div class="col">
          <div class="col-xs-4">
            <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/temperature.png" height="40" alt="" >
            <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/humidity.png" height="40" alt="">
          </div>
        </div>
        <div class="col">
            @lang('general.timeperiod')
            {{-- @lang('graph.header.periodlabel') --}}
            <select id="days" onchange="refreshgraph()">
              <option selected="selected" value="10">10 dager</option>
              <option value="31">1 mnd</option>                            
              <option value="91">3 mnd</option>
              <option value="182">6 mnd</option>
              <option value="274">9 mnd</option>
              <option value="365">1 år</option>
              <option value="548">1,5 år</option>
              <option value="720">2 år</option>
              <option value="1095">3 år</option>
              <option value="1826">5 år</option>                          
            </select>
        </div>
    </div>       
    <div id="container" class="bg-grey mb-3" style="height: 500px; min-width: 310px"></div>
  </div>
</div>


<script>
var lat_position = @json($unit['lat']);
var lng_position = @json($unit['lng']);
var bounds = new google.maps.LatLngBounds();
var markerpos = new google.maps.LatLng(lat_position,lng_position);
function initMap() {
    if (lat_position !== 0 && lng_position !== 0) {
        console.log(markerpos);
        map = new google.maps.Map(document.getElementById('map_1'), {
            center: {lat:59,lng:10},
            mapTypeControl: true,
            zoom: 15,
            streetViewControl: false,
            tilt: 0
        });
        marker();
        autoSizing();
    } else {
        $('#map_1').remove();
    }
}

function marker() {
    var marker = new google.maps.Marker({
        position: markerpos,
        map: map
    });
    bounds.extend(marker.position);
}

function autoSizing() {
    map.setCenter(bounds.getCenter());
}

google.maps.event.addDomListener(window, "load", initMap);


var now = new Date();
var strDateTimeStart = Date.now();

let seriesOptions = [],
seriesCounter = 0;
let sensorenheter = [];
let sensornames = [];
let antenheter=0;
let probenr=0;
let antsenprobes=0;
let valgtsensortype=0;
let valgtnumbdays=10;
let countnames=0;
let nodata_count=0;

// Lang Rangeselector
var columtxt_1 = @json( __('general.12h') );
var columtxt_2 = @json( __('general.day') );
var columtxt_3 = @json( __('general.week') );
var columtxt_4 = @json( __('general.month') );
var columtxt_5 = @json( __('general.all') );

var shortlabel = '°C';      // Bruke Vue her
var name_unit = $('#name_unit').text();
/**
 * Create the chart when all data is loaded
 * @returns {undefined}
 */
$(document).ready(function () {
  console.log(name_unit);
  getProbes('{{$serial}}');
});


function createChart() {
  Highcharts.stockChart('container', {
    rangeSelector: {
      selected: 4
    },
    title: {
      align: 'left',
      text: name_unit,
      style: {
        fontWeight: 'bold',
        fontSize: '20px'
      }
     
    },
    exporting: {
      filename: name_unit
    },
    yAxis: {
      labels: {
        formatter: function () {
          return (this.value > 0 ? ' + ' : '') + this.value;
        }
      },
      plotLines: [{
        value: 0,
        width: 2,
        color: 'silver'
      }]
    },
    rangeSelector: {
      buttonTheme: { // styles for the buttons
      fill: 'none',
      stroke: 'none',
      'stroke-width': 0,
      r: 8,
      style: {
        color: '#039',
        fontWeight: 'bold'
      }
    },
    inputBoxBorderColor: 'gray',
    inputBoxWidth: 120,
    inputBoxHeight: 18,
    inputStyle: {
      color: '#039',
      fontWeight: 'bold'
    },
    labelStyle: {
      color: 'silver',
      fontWeight: 'bold'
    },
    buttons: [{
        count: 12,
        type: 'hour',
        text: columtxt_1
      }, {
        count: 24,
        type: 'hour',
        text: columtxt_2
      }, {
        count: 7,
        type: 'day',
        text: columtxt_3
      }, {
        count: 1,
        type: 'month',
        text: columtxt_4
      }, {
        type: 'all',
        text: columtxt_5
      }],
      inputEnabled: true,
      selected: 4
    },
    legend: {
      labels: {
          filter: (legendItem, data) => {
          return false; // this should hide all legends
            }
          },
    
      enabled: true,
      layout: 'vertical',
      floating: false,
      align: 'right',
      x: 10,
      verticalAlign: 'top',
      y: 70
    },
    credits: {
      enabled: false
    },
    
    tooltip: {
      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y} </b><br/>',
      valueDecimals: 2,
      split: true
    },

    series: seriesOptions
  });
}

function refreshgraph() {
    var days = document.getElementById("days");
    valgtnumbdays = days.options[days.selectedIndex].value;
    seriesCounter=0;
    countnames=0;
    seriesOptions=[];
    getProbes('{{$serial}}');
}

function getProbes(serialnumber) {
  var token = $("meta[name='csrf-token']").attr("content");
  $.ajax({
    url: "/graph/getprobeinfo/" + serialnumber,
    type: 'GET',
    data: {
      "_token": token,
    },
    success: function (data) {
      for (var i in data) {
        var unittype_id = data[i].unittype_id;
        var probenr = data[i].sensorprobes_number;
        if(probenr == 15) continue; // skip Cell ID
        if(probenr == 14) continue; // skip Local Area Code
        var name = data[i].unittype_description;
        getData(serialnumber,probenr, name, unittype_id);
      } 
    },
  })
}

function getData(serialnumber,probenr, name, unittype_id){
  var token = $("meta[name='csrf-token']").attr("content");
  console.log(serialnumber + '   ' + valgtnumbdays + '    '+ probenr);
  $.ajax({
    url: '/graph/getsensordata/' + serialnumber +'/' + valgtnumbdays + '/' + probenr + '/' + unittype_id + '/1',
    dataType: 'json',      
    data: {
        "_token": token,
    }, 
    success: function( data ) {
      // if(data !== -1) {
        if (!Array.isArray(data)) return;  // if -1 and not array skip code
        // if ((data) > 100000) return; 
        if (seriesCounter == 0) {
          seriesOptions[seriesCounter] = {
            name: name,
            data: data,
            visible: true
          };
        } else {
          seriesOptions[seriesCounter] = {
              name: name,
              data: data,
              visible: false
          };
        }  
      
        // As we're loading the data asynchronously, we don't know what order it
        // will arrive. So we keep a counter and create the chart when all the data is loaded.
        seriesCounter++;
        
        // func for name sort alfabetical
        seriesOptions.sort(function (a, b) {
          return a.name > b.name ? 1 : -1;
        })

      if (seriesCounter) {
        createChart();
      }
    },
    error: function( data ) {
      console.log(data);
      nodata_count++;
    }
  });
}
</script>

@endsection