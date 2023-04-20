@extends('layouts.app')

@section('content')
<div class="mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="bg-white card-rounded p-2">
                <div class="bg-white card-rounded">
                    <div class="row mx-2 mt-2 mb-3">
                        <div class="col my-auto">
                            <div class="col-xs-4">
                                <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/temperature.png" height="40" alt="" >
                                <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/humidity.png" height="40" alt="">
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 mx-auto">
                          @lang('general.timestamp')
                            <select id="timestamp" class="form-control" onchange="setTimestamp()">
                              <option value="1">@lang('general.exact')</option>
                              <option @if(request()->timestamp == 2) selected @endif value="2">Hver time</option>
                              <option @if(request()->timestamp == 3) selected @endif value="3">Hver dag</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 mx-auto">
                            @lang('general.choosesensortype')
                            <select class="form-control" id="probetype" name="typeofsensor" onchange="refreshgraph()">
                                @foreach($probes as $probe)
                                    <option value="{{$probe['unittype_id']}}"> {{ $probe['unittype_description'] }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 mx-auto">
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
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">        
                    <div id="container" style="height: 500px; min-width: 310px"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
setTitle(@json(__('navbar.graph')));

var collator = new Intl.Collator('nb'); // sort by name var
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
let valgtprojects = null;
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

/**
 * Create the chart when all data is loaded
 * @returns {undefined}
 */
$(document).ready(function () {
    // Sort sensortypes
    sortsensortypes();
    // Get all senorunits for user
    getUnits();
});

function setTimestamp() {
  var temptimestamp = document.getElementById('timestamp').value;
  location.href = '?timestamp='+temptimestamp;
  console.log(temptimestamp);
}

function createChart() {
  (function(H) {
    H.wrap(H.Legend.prototype, 'render', function(proceed) {
      var legend = this,
        chart = legend.chart,
        animation = H.pick(legend.options.navigation.animation, true);

      proceed.apply(this, Array.prototype.slice.call(arguments, 1));

      H.addEvent(legend.group.element, 'mousewheel', function(event) {
        e = chart.pointer.normalize(event);

        e.wheelDelta < 0 ? legend.scroll(1, animation) : legend.scroll(-1, animation);
      });
    });
  }(Highcharts));

  Highcharts.stockChart('container', {

    rangeSelector: {
      selected: 2
    },
    yAxis: {
      labels: {
        formatter: function () {
          return (this.value > 0 ? ' + ' : '') + this.value + ' ' + shortlabel;
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
      },
      states: {
        hover: {
        },
        select: {
          fill: '#039',
          style: {
            color: 'white'
          }
        }
        // disabled: { ... }
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
      selected: 2
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
      y: 40,
      maxHeight: 300,
      navigation:{
        activeColor:'#003399',
        animation:true,
        arrowSize:20,
        enabled:true,
        inactiveColor:'#cccccc',
        style:undefined
      }
    },
    credits: {
      enabled: false
    },
    
    tooltip: {
      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y} ' + shortlabel + '</b><br/>',
      valueDecimals: 2,
      split: true
    },

    series: seriesOptions
  });
}

function refreshgraph()
{
    var days = document.getElementById("days");
    valgtnumbdays = days.options[days.selectedIndex].value;

    var probetype = document.getElementById("probetype");
    valgtsensortype = probetype.options[probetype.selectedIndex].value;

    var projects = document.getElementById("projects");
    valgtprojects = projects.options[projects.selectedIndex].value || null;
  
    seriesCounter=0;
    countnames=0;
    seriesOptions=[];
  
    getProbes();
}

  function sortsensortypes() {
    var opt = $("#probetype option").sort(function (a,b) { return a.value.toUpperCase().localeCompare(b.value.toUpperCase()) });
    $("#probetype").append(opt);
    
      // Default sensortype
      $("#probetype").val(0);
  }

  function getUnits() {    
    $.ajax({
      url: "/graph/units",
      type: 'GET',
      success: function (data) {
        console.log(data);
        for (var i in data) {
            sensorenheter.push(data[i].serialnumber);
            if (data[i].sensorunit_location) {
              sensornames.push(data[i].sensorunit_location);
            } else {
              sensornames.push(data[i].serialnumber);
            }
        }
        antenheter=sensorenheter.length;
  
        getProbes();
      },
    })
  }
  
  function getUnitsProbe(serialnumber,probetype, name) {
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
      url: "/graph/getprobeinfo/" + serialnumber + "/" + probetype,
      type: 'GET',
      success: function (data) {
        for (var i in data) { shortlabel = data[i].unittype_shortlabel;
          // if ((valgtprojects != null) && (valgtprojects != data[i].productnumber)) { // MISSING CUSTOMER NUMBER IN API
          //   console.log('skipping', name, valgtprojects, data[i])
          //   continue
          // };

          probenr = data[i].sensorprobes_number;
          //console.log("Serialnumber = " + serialnumber + "  Probenumber = "+ probetype + probenr);
          getData(serialnumber,probenr, name, probetype);
        } 
      },
    })
  }

  function getProbes(){
      for ( var i = 0; i < antenheter; i++) {
            getUnitsProbe(sensorenheter[i],valgtsensortype, sensornames[i]);
      }
  }

  function getData(serialnumber,probenr, name, probetype) {
  var token = $("meta[name='csrf-token']").attr("content");
  var temptimestamp = document.getElementById('timestamp').value;
  console.log(temptimestamp)
  $.ajax({
    url: '/graph/getsensordata/' + serialnumber +'/' + valgtnumbdays + '/' + probenr + '/' + probetype + '/' + temptimestamp,
    dataType: 'json',      
    success: function( data ) {
      var count = countnames + nodata_count;
      if (!Array.isArray(data)) return; // if -1 and not arry skip code
      
      if (seriesCounter == 0) {
        seriesOptions[countnames] = {
          name: name,
          data: data,
          visible: true
        };
      } else {
        seriesOptions[countnames] = {
            name: name,
            data: data,
            visible: false
        };
      }  
      // As we're loading the data asynchronously, we don't know what order it
      // will arrive. So we keep a counter and create the chart when all the data is loaded.
      seriesCounter++;
      countnames++;

      // sort by name func
      seriesOptions.sort(function (a, b) {
          return collator.compare(a.name, b.name);
      })

      if (seriesCounter > 0) {
        createChart();
      }
    },
    error: function( data ) {
      nodata_count++;
      //alert("No data found");
    }
  });
}

</script>


@endsection