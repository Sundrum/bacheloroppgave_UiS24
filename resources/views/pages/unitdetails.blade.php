@extends('layouts.app')

@section('content')
<script>setTitle(@json($unit['sensorunit_location']))</script>
<div class="row text-center">
  <div class="col-md-6">
    <div class="row card-rounded bg-white mx-1">
      <h3 class="p-2">I dag</h3>
      @if(isset($unit['probes']) && count($unit['probes']) > 0)
        @foreach($unit['probes'] as $probe)
          @isset($probe['header'])
          <div class="col">
            <div class="row justify-content-center">
              <div class="semi-donut" style="--percentage : {{$probe['percent'] ?? '0'}}; --fill: #00265a;">
                {{$probe['header'] ?? 'NaN'}}
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                {{-- MIN VALUE --}}
                {{$probe['lowerthreshold'] ?? ''}}
              </div>
              <div class="col-6">
                {{-- Max VALUE --}}
                {{$probe['upperthreshold'] ?? ''}}
              </div>
            </div>
            <p class="col "><strong>{{$probe['unittype_description']}}</strong></p>
          </div>
          @endisset
        @endforeach
      @endif
    </div>
  </div>
  <div class="col-md-6">
    <div class="row card-rounded bg-white mx-1">
      <h3 class="p-2">Gjennomsnitt på 7 dager</h3>
      <p> Kommer snart! </p>
      {{-- @if(isset($unit['probes']) && count($unit['probes']) > 0)
        @foreach($unit['probes'] as $probe)
          @isset($probe['header'])
            <div class="col">
              <div class="row justify-content-center">
                <div class="semi-donut" style="--percentage : {{$probe['percent'] ?? '0'}}; --fill: #00265a;">
                  {{$probe['header'] ?? 'NaN'}}
                </div>
            </div>
            <div class="row">
              <div class="col-6">
                {{$probe['lowerthreshold'] ?? ''}}
              </div>
              <div class="col-6">
                {{$probe['upperthreshold'] ?? ''}}
              </div>
            </div>
            <p class="col "><strong>{{$probe['unittype_description']}}</strong></p>
          </div>
          @endisset
        @endforeach
      @endif --}}
    </div>
  </div>
</div>

<h2 id="name_unit" style="display: none;">{{$unit['sensorunit_location'] ?? $serial}}</h2>
<div class="row justify-content-center mt-2">
  <div class="col-md-12">
    <div class="bg-white card-rounded">
      <div class="row py-3 px-3">
        <div class="col">
          <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/temperature.png" height="40" alt="" >
          <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/humidity.png" height="40" alt="">
        </div>
        <div class="col-12 col-md-4 col-lg-3 mx-auto">
          @lang('general.timestamp')
          <select id="timestamp" class="form-control" onchange="setTimestamp()">
            <option value="1">@lang('general.exact')</option>
            <option @if(request()->timestamp == 2) selected @endif value="2">@lang('general.everyhour')</option>
            <option @if(request()->timestamp == 3) selected @endif value="3">@lang('general.everyday')</option>
          </select>
        </div>
        <div class="col-12 col-md-4 col-lg-3 mx-auto">
          @lang('general.timeperiod')
          {{-- @lang('graph.header.periodlabel') --}}
          <select class="form-control" id="days" onchange="refreshgraph()">
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
            <option value="3652">10 år</option>   
          </select>
        </div>
      </div>
      <div class="card-rounded" id="container" style="height: 500px; min-width: 310px;"></div>
    </div>
  </div>
</div>


<script>
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
/*var columtxt_1 = @json( __('graph.graphrange.colum1') );
var columtxt_2 = @json( __('graph.graphrange.colum2') );
var columtxt_3 = @json( __('graph.graphrange.colum3') );
var columtxt_4 = @json( __('graph.graphrange.colum4') );
var columtxt_5 = @json( __('graph.graphrange.colum5') );*/

var columtxt_1 = '12h';
var columtxt_2 = 'Day';
var columtxt_3 = 'Week';
var columtxt_4 = 'Month';
var columtxt_5 = 'All';
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

function setTimestamp() {
  var temptimestamp = document.getElementById('timestamp').value;
  location.href = '?timestamp='+temptimestamp;
  console.log(temptimestamp);
}

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
    plotOptions: {
      line: {
        sortKey: 'name',
      },
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
      itemHiddenStyle: {"fontWeight": "normal","text-decoration": "none"},
      itemStyle: {"fontWeight": "bold"},
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
      // value, mm -> calls from fuct "refreshgraph", "sucsses"
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
      console.log(data[1]);
      for (var i in data) {
        var unittype_id = data[i].unittype_id;
        var probenr = data[i].sensorprobes_number;
        var name = data[i].unittype_description;
        getData(serialnumber,probenr, name, unittype_id);
      } 
    },
  })
}

function getData(serialnumber,probenr, name, unittype_id){
  var token = $("meta[name='csrf-token']").attr("content");
  var temptimestamp = document.getElementById('timestamp').value;
  console.log(serialnumber + '   ' + valgtnumbdays + '    '+ probenr);
  $.ajax({
    url: '/graph/getsensordata/' + serialnumber +'/' + valgtnumbdays + '/' + probenr + '/' + unittype_id + '/' + temptimestamp,
    dataType: 'json',      
    data: {
        "_token": token,
    }, 
    
    success: function( data ) {
      // if(data !== -1) {
        if (!Array.isArray(data)) return;  // if -1 and not array skip code

        if (seriesCounter == 0) {
          seriesOptions[seriesCounter] = {
            name: name,
            data: data,
            visible: true,
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
      // }

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