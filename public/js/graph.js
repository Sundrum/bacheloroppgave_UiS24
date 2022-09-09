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
let valgtnumbdays=90;
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
    $.ajax({
      url: "/graph/getprobeinfo/" + serialnumber + "/" + probetype,
      type: 'GET',
      success: function (data) {
        for (var i in data) {
          shortlabel = data[i].unittype_shortlabel;
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
  $.ajax({
    url: '/graph/getsensordata/' + serialnumber +'/' + valgtnumbdays + '/' + probenr + '/' + probetype,
    dataType: 'json',      
    success: function( data ) {
      var count = countnames + nodata_count;
      if (seriesCounter < 5) {
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