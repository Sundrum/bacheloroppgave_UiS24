@extends('layouts.app')

@section('content')

<section class="">
    <div class="row">
        <div class="col-md-12">
          <div id="sensor-choose"></div>
        </div>
      </div>
    <div class="col-12">
        <div class="mt-3 mb-3">
            <div class="row">
                <div class="@if(isset($unit))col-md-6 @else col-md-12 @endif card card-rounded">
                    <h5 class="m-4 text-center">Sensorenhet</h5>
                    <h5 class="text-center">{{$unit->serialnumber ?? ''}}</h5>
                    <div class="mt-3 mb-3">
                        <p>{{$unit->sensorunit_lastconnect ?? ''}}</p>
                        <form method="POST" name="userupdate" id="userupdate" action="{{route('updateSensorunit')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="sensorunit_location" class="col-md-4 col-form-label">{{ __('Navn') }}</label>
                                <div class="input-group col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sensorunit_location" name="sensorunit_location" placeholder="Gi sensorenheten et navn" value="{{$unit->sensorunit_location ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sensorunit_position" class="col-md-4 col-form-label">{{ __('Plassering') }}</label>
                                <div class="input-group col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sensorunit_position" name="sensorunit_position" placeholder="Detaljert plassering av sensor" value="{{$unit->sensorunit_position ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pba" class="col-md-4 col-form-label">{{ __('PCB Assembly') }}</label>
                                <div class="input-group col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="pba" name="pba" placeholder="PCB Assembly Number" value="{{$unit->sensorunit_pba ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer_id_ref" class="col-md-4 col-form-label">Kunde</label>
                                <div class="input-group col-md-8"> 
                                    <div class="input-group">
                                        <select class="custom-select form-control" id="customer_id_ref" name="customer_id_ref">
                                            @foreach($table['customer'] as $customer)
                                            <option value="{{$customer->customer_id}}" @if($customer->customer_id == $unit->customer_id_ref) selected @endif> {{$customer->customer_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="sensorunit_id" name="sensorunit_id" value="{{$unit->sensorunit_id ?? ''}}">
                            <div class="form-row justify-content-center">
                                <div class="col-2">
                                    <button type="submit" id="userform" class="btn-primary-filled"> Lagre </button>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-danger btn justify-content-right" onclick="deleteSensorunit({{trim($unit->sensorunit_id)}})">Slett</button>
                                </div>
                            </div>
                          
                        </form>
                    </div>
                </div>
                @include('admin.sensorunit.variable')
                {{-- @include('admin.user.units') --}}
            </div>
        </div>
    </div>

    
    {{-- @if(isset($user))
    <div class="col-md-12 mt-4 mb-4">
        <hr>
        <div class="row mb-4 justify-content-center">
            <a class="nav-pills btn-secondary-rounded-outline m-1" href="#">@lang('admin.customer')</a>
            <a class="nav-pills btn-secondary-rounded-outline m-1" href="#">@lang('admin.delete')</a>
        </div>
    </div>
 
    @endif --}}
</section>

<section class="container-fluid">
        <div class="col-12 card card-rounded">
            <h5 class="mt-2 mb-2">Brukertilganger</h5>
            <div class="row">
                <div class="col-4">
                    <strong>Navn</strong>
                </div>
                <div class="col-4">
                    <strong>Brukernavn</strong>
                </div>
                <div class="col-2">

                </div>
                <div class="col-2 text-center">
                    <strong>Slett tilgang</strong>
                </div>

            </div>
            <hr class="mt-0">
            @if(isset($unit->access) && count($unit->access) > 0)

                @foreach ($unit->access as $user)
                <div id="access_{{$user->sensoraccess_id}}">
                    <div class="row">
                        <div class="col-4">{{$user->user_name}}</div>
                        <div class="col-4">{{$user->user_email}}</div>
                        <div class="col-2"></div>
                        <div class="col-2 my-auto text-center">
                            <i onclick="deleteRow('{{$user->sensoraccess_id}}','{{$user->user_id}}', '{{$unit->serialnumber}}')" class="fa fa-times" style="color:red" aria-hidden="true"></i>
                        </div>
                    </div>
                    <hr>
                </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-12 mb-2">
                        Ingen bruker har tilgang til denne sensorenheten.
                    </div>
                </div>
            @endif

        </div>
</section>
<section>
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="col-12">
                <div class="card card-rounded">
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <div class="col">
                                <div class="col-xs-4">
                                <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/temperature.png" height="40" alt="" >
                                <img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/humidity.png" height="40" alt="">
                                </div>
                            </div>
                            <div class="col">
                                Tidsperiode(dager)
                                {{-- @lang('graph.header.periodlabel') --}}
                                <select id="days" onchange="refreshgraph()">
                                    <option value="10">10 dager</option>  
                                    <option selected="selected" value="31">1 mnd</option>                           
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
                    </div>
                    <div class="card-body">        
                        <div id="container" style="height: 500px; min-width: 310px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$( document ).ready(function() {
    $('#customer_id_ref').select2({
        theme: 'classic'
    });
});

function deleteRow(id, userid, serialnumber) {
    var confirmed = confirm('Ønsker du å fjerne denne tilgangen?');

    if(confirmed) {
        $.ajax({
            url: "/admin/connect/delete",
            type: 'POST',
            data: { 
                "serialnumber": serialnumber,
                "userid": userid,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
                $('#access_'+id).remove();
            },   
            error: function(msg) {
                alert("Failed - Please try again")
            }
        });
    }
}

getSensorunits();
    
function getSensorunits() {
    $.ajax({
    url: "/admin/sensorunitall",
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        var code2 = '';
        var code = '<div id="choose-sensors">';
            code += '<select class="custom-select col-md-12 form-control theme-mult" id="sensorunits" name="sensorunits" onchange="goTo(this.value);">';
            code += '<option value="" disabled selected hidden> Søk etter sensorenhet </option>'
                for (var i in data) {
                    code2 += '<option value="'+data[i][1].trim()+'">'+data[i][0].trim()+','+ data[i][2] + data[i][3] +'</option>';
                }
            code += code2;
            code += '</select></div>';
        $('#sensor-choose').after(code);
        $('#sensorunits').select2();
    },   
    error: function(msg) {
        alert("Failed - Please try again")
    }
    });
}

function goTo(id) {
    if (id) {
        window.location = "/admin/sensorunit/"+id;
    }
}

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
    let valgtnumbdays=31;
    let countnames=0;
    let nodata_count=0;
    
    // Lang Rangeselector
    /*var columtxt_1 = @json( __('graph.graphrange.colum1') );
    var columtxt_2 = @json( __('graph.graphrange.colum2') );
    var columtxt_3 = @json( __('graph.graphrange.colum3') );
    var columtxt_4 = @json( __('graph.graphrange.colum4') );
    var columtxt_5 = @json( __('graph.graphrange.colum5') );*/
    
    var columtxt_1 = '12t';
    var columtxt_2 = 'Dag';
    var columtxt_3 = 'Uke';
    var columtxt_4 = 'Måned';
    var columtxt_5 = 'Alt';
    var shortlabel = '°C';
    
    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    $(document).ready(function () {
        getProbes('{{$unit->serialnumber ?? ''}}');
    });
    
    
    function createChart() {
        Highcharts.stockChart('container', {
        rangeSelector: {
            selected: 4
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
        getProbes('{{$unit->serialnumber ?? ''}}');
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
            if (!Array.isArray(data)) return;  // if -1 and not array skip code
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
    
            if (seriesCounter > 0) {
            createChart();
            }
        },
        error: function( data ) {
            nodata_count++;
        }
        });
    }
    // New delete sensor unit
    function deleteSensorunit(id) {
      var confirmed = confirm('Vil du fjerne denne sensorenheten?');
      if(confirmed) {
          $.ajax({
              url: "/admin/sensorunit/delete",
              type: 'POST',
              data: { 
                  "id": id,
                  "_token": token,
              },
              success: function(msg) {
                  console.log(msg);
                  window.location = "/admin/sensorunit";
              },   
              error: function(msg) {
                  alert("Failed - Please try again")
              }
          });
      }
    }
</script>
@endsection