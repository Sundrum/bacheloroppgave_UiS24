var now = new Date();
var strDateTimeStart = Date.now();

let countnames=0;
let seriesOptions = [],
seriesCounter = 0;
let sensorenheter = [];
let antenheter=0;
let probenr=0;
let antsenprobes=0;
let valgtsensortype=0;
let valgtnumbdays=90;
let nodata_count=0;
let sensornames=[];
let antnavn=0;

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
var units = '°C';      // Bruke Vue her
/**
 * Create the chart when all data is loaded
 * @returns {undefined}
 */
$(document).ready(function () {
    // Sort sensortypes
    //sortsensortypes();
    // Get all senorunits for user
    getUnits();
});


 function createChart() {

  Highcharts.stockChart('container', {

    rangeSelector: {
      selected: 4
    },
    yAxis: {
      labels: {
        formatter: function () {
          return (this.value > 0 ? ' + ' : '') + this.value + ' ' +units;
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
      selected: 1
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
    
    tooltip: {
      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y} ' + units + '</b><br/>',
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
  
    GetsenordataProbes();
}

  function sortsensortypes() {
    var opt = $("#probetype option").sort(function (a,b) { return a.value.toUpperCase().localeCompare(b.value.toUpperCase()) });
    $("#probetype").append(opt);

      // Default sensortype
      $("#probetype").val(0);
  }

  function getUnits() {
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
      url: "/graph/getunitslist",
      type: 'GET',
      data: {
        "_token": token,
      },
      success: function (data) {

        for (var i in data) {
            sensorenheter.push(data[i].serialnumber);
            sensornames.push(data[i].sensorunit_location);
            console.log(sensorenheter[i]);
            console.log(sensornames[i]);
        }
        antenheter=sensorenheter.length;
        antnavn=sensornames.length;
  
        GetsenordataProbes();
      },
    })

  }

  function GetsensorprobeUnit(serialnumber,probetype) {
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
      url: "/graph/getprobeinfo/" + serialnumber + "/" + probetype,
      type: 'GET',
      data: {
        "_token": token,
      },
      success: function (data) {
        for ( var i = 0; i < data.result.length; i++) {
          probenr = data.result[i].unittype_id;
          console.log("Probenumber");
        } 
        GetsenordatafromAPI(serialnumber,probenr);

         },
       })
  }

  function GetsenordataProbes(){
      for ( var i = 0; i < antenheter; i++) {
            antsenprobes=0;
            GetsensorprobeUnit(sensorenheter[i],valgtsensortype);
      }
  }

  function GetsenordatafromAPI(serialnumber,probenr)
  {
    var token = $("meta[name='csrf-token']").attr("content");

  $.ajax({
    url: '/graph/getsensordata/' + serialnumber +'/' + valgtnumbdays + '/' + probenr,
    dataType: 'json',      
    data: {
        "_token": token,
    },

    success: function( data ) {
      
      if (seriesCounter == 0)
      {
           seriesOptions[countnames] = {
             name: sensornames[seriesCounter],
             data: data,
             visible: true
           };
      }  
      else
      {
          seriesOptions[countnames] = {
              name: sensornames[seriesCounter],
              data: data,
              visible: false
          };
      }  
    // As we're loading the data asynchronously, we don't know what order it
    // will arrive. So we keep a counter and create the chart when all the data is loaded.
    seriesCounter += 1;
    countnames+=1;
    
    if (seriesCounter > 0) {
      createChart();
    }

  },

  error: function( data ) {
    nodata_count += 1;
          //alert( "Ingen data funnet for sensor.");
        }
      });
   }