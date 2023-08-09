@extends('layouts.app')

@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<section class="row mb-3">
    <div class="col-6">
        <a class="btn-7r" href="/admin/irrigationstatus" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til Irrigation Status</strong></a>
    </div>
    <div class="col-6 text-end">
            <a class="btn-7g" href="/admin/irrigationdebug/{{$variable['unit']['serialnumber'] }}" style="color: black; text-decoration: none;"><strong>Waterlost Debug</strong></a>
    </div>
    <div class="col-md-6 mt-2">
        <div class="col-md-12 bg-white card-rounded p-3 mb-2">
            <div class="row">
                <div class="col">
                    <h5>Latest</h5>
                </div>

            </div>
            <div class="row px-3 text-center">
                <div class="col text-center">
                    <img src="/img/irrigation/state_{{$variable['latest']['state'] ?? '0'}}.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <span class="text-center">State</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['state'] ?? '0'}}</span>
                    </div>
                </div>
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="40" height="40" title="Tilt / Angle" rel="tooltip" style="transform: rotate({{ $variable['latest']['tilt_relative'] ?? '0' }}deg);">
                    <div class="row">
                        <span class="text-center">Tilt relative</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['tilt_relative'] ?? 'No data'}}&deg;</span>
                    </div>
                </div>
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <span class="text-center">Vibration</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['vibration'] ?? ''}}rms</span>
                    </div>
                </div>
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Speed" rel="tooltip" alt="">
                    <div class="row">
                        <span class="text-center">Pressure</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['pressure'] ?? ''}}Bar</span>
                    </div>
                </div>

                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/humidity.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <span class="text-center">RH</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['rh'] ?? ''}}% </span>
                    </div>
                </div>
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/temperature.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <span class="text-center">Temperature</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['temperature'] ?? ''}}&deg;C</span>
                    </div>
                </div>
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <span class="text-center">Flow Velocity</span>
                    </div>
                    <div class="row">
                        <span class="text-center">{{$variable['latest']['vibration'] ?? ''}}m/s</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 bg-white card-rounded p-3">

            <div class="row">
                <div class="col">
                    <h5 class="">Innstillinger</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Serialnumber
                </div>
                <div class="col-6" id="serialnumber">
                    {{$variable['unit']['serialnumber'] ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            <div class="row">
                <div class="col-6">
                    Name
                </div>
                <div class="col-6">
                    {{$variable['unit']['sensorunit_location'] ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            <div class="row">
                <div class="col-6">
                    Last Connected
                </div>
                <div class="col-6">
                    {{$variable['unit']['last_time'] ?? ''}}
                </div>
            </div>
            <hr class="mt-0">
            <h5 class="mb-2">Status Innstillinger</h5>
            @isset($variable['unit']['status'])
                @foreach($variable['unit']['status'] as $row)
                    <div class="row">
                        <div class="col-6">
                            {{$row->variable ?? ''}}
                        </div>
                        <div class="col-6">
                            {{$row->value ?? ''}}
                        </div>
                    </div>
                    <hr class="m-0">
                @endforeach
            @endisset
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="col-md-12 bg-white card-rounded p-3 mb-2">
            <div class="row">
                <div class="col">
                    <h5>Battery Consumption</h5>
                </div>
            </div>
            <div class="row px-3 text-center">
                <div class="col">
                    <div class="row">
                        Sleep
                    </div>
                    <div class="row">
                        {{$variable['latest']['sleep'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        Boot 
                    </div>
                    <div class="row">
                        {{$variable['latest']['boot'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        Packets 
                    </div>
                    <div class="row">
                        {{$variable['latest']['packets'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        Sensors 
                    </div>
                    <div class="row">
                        {{$variable['latest']['sensors'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        GNSS 
                    </div>
                    <div class="row">
                        {{$variable['latest']['gnss'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        Led 
                    </div>
                    <div class="row">
                        {{$variable['latest']['led'] ?? ''}}mAh
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        Battery used
                    </div>
                    <div class="row">
                        {{$variable['latest']['battery_mah'] ?? ''}}mAh
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 bg-white card-rounded p-3">
            <h5 class="mt-2 mb-2">Irrigation Innstillinger</h5>
            @isset($variable['unit']['config'])
                @foreach($variable['unit']['config'] as $row)
                    <div class="row">
                        <div class="col-6">
                            {{$row->variable ?? ''}}
                        </div>
                        <div class="col-6">
                            {{$row->value ?? ''}}
                        </div>
                    </div>
                    <hr class="m-0">
                @endforeach
            @endisset

            <h5 class="mb-2">Kommandoer</h5>
            <div class="row justify-content-center mb-2">
                <div class="col-3 center-block">
                    <button class="btn-7s" data-toggle="collapse" href="#collapseVariable" aria-expanded="false" aria-controls="collapseVariable"><strong>Set Variables</strong></button>
                </div>
                <div class="col-3 center-block">
                    <button class="btn-7s" onclick="variables('{{$variable['unit']['serialnumber']}}')"><strong>Get Variables</strong></button>
                </div>
                <div class="col-3">
                    <div class="btn-group">
                        <button type="button" class="btn-7s dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <strong>FOTA</strong>
                        </button>
                        <div class="dropdown-menu bg-white">
                            <a class="dropdown-item disabled text-white bg-7s" href="#">Released</a>
                            @if(isset($variable['firmware']['released']))
                            @foreach ($variable['firmware']['released'] as $row)
                            <a class="dropdown-item" onclick="FOTA('{{$variable['unit']['serialnumber']}}',{{$row['firmware_id']}});">{{$row['major'] ?? 'NaN'}}.{{$row['minor'] ?? 'NaN'}}.{{$row['patch'] ?? 'NaN'}}-{{$row['build'] ?? 'unknown'}}</a>
                            @endforeach
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item disabled text-white bg-7r" href="#">Not Released</a>
                            @if(isset($variable['firmware']['notreleased']))
                            @foreach ($variable['firmware']['notreleased'] as $row)
                                <a class="dropdown-item" onclick="FOTA('{{$variable['unit']['serialnumber']}}',{{$row['firmware_id']}});">{{$row['major'] ?? 'NaN'}}.{{$row['minor'] ?? 'NaN'}}.{{$row['patch'] ?? 'NaN'}}-{{$row['build'] ?? 'unknown'}}</a>
                            @endforeach
                            @endif
                        {{-- <div class="dropdown-divider"></div>
                            <a class="dropdown-item disabled" href="#">New Firmware</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#uploadModal">Upload Firmware</a> --}}
                        </div>

                        </div>
                </div>
                <div class="col-3 center-block">
                    <button class="btn-7s" onclick="startNewRun('{{$variable['unit']['serialnumber']}}')"><strong>New Run</strong></button>
                </div>
            </div>
            {{-- <div class="row justify-content-center mb-2">
                <div class="col-4">
                    <button class="btn btn-danger card-rounded disabled">GNSS IDLE</button>
                </div>
                <div class="col-4">
                    <button class="btn-primary-filled" onclick="defaultSettings('{{$variable['unit']['serialnumber']}}')"><strong>Default</strong></button>
                </div>
                <div class="col-4">
                    <button class="btn-primary-filled" id="changeserial"><strong>Change S/N</strong></button>
                </div>
                <div class="col-12">
                    <div id="message" class="mt-2"></div>
                </div>
            </div> --}}
        </div>
    </div>
    @include('admin.sensorunit.setvariable')
    <div class="col-12 mt-2">
        <div class="col-12 card card-rounded p-3">
            <div class="row mt-2 mb-2 ">
                <div class="col-8">
                    <h3>Queue for Unit</h3>
                </div>
                <div class="col-4 text-end">
                    <button id="delete" class="btn-7r float-right">Delete</button>
                </div>
                <div class="col">
                    <p class="mb-0">To select multiple rows use shift and click.</p>
                    <p class="mt-0">Select all - will select all entries. Notice that you might not see every entrie.</p>
                </div>
            </div>
            <table id="table" class="display" width="100%"></table>
            <div class="row  justify-content-center mb-3">
                <div class="col text-center">
                    <button type="button" class="mr-3 btn-7s select-all">Select All</button>
                    <button type="button" class="ml-3 btn-7s deselect-all">Deselect All</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="col-12 card card-rounded p-3">
            <div class="row mt-2 mb-2">
                <div class="col-8">
                    <h3>Runtable</h3>
                </div>
                <div class="col-4 my-auto">
                </div>
                <div class="col">
                    <p class="mb-0">Click and select a run you want to edit</p>
                </div>
            </div>
            <table id="runtable" class="display" width="100%"></table>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="col-12 card card-rounded p-3">
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
            <div class="">        
                <div id="container" style="height: 500px; min-width: 310px"></div>
            </div>
        </div>
    </div>
</div>
    @include('admin.sensorunit.changeserialnumber')
    {{-- @include('admin.firmware.upload') --}}

</section>
<script>
document.getElementById("top-title").innerHTML = document.getElementById("serialnumber").innerHTML;

$('#changeserial').click( function () {
        $('#changeSerialModal').modal('show');
} );

function FOTA(serial, fw){
    console.log(token);
    $.ajax({
        url: "/admin/irrigation/fota",
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "cmd": 'fota',
            "fw": fw
        },
        success: function(msg) {
            if (msg == 1) {
                successMessage('Command added to Queue');

                setTimeout(function(){ 
                    location.reload();
                }, 3000);

            } else if (msg == 2){
                errorMessage('A command failed');
            } else {
                errorMessage('E2 - Something went wrong. Please try again later.');
            }
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong. Please try again later.');
        }
    });
}

function startNewRun(serial){
    console.log(token);
    $.ajax({
        url: "/admin/irrigation/fota",
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "cmd": 'startnewrun'
        },
        success: function(msg) {
            console.log(msg);
            if (msg == 1) {
                successMessage('New run will start at the next report interval');
            } else if(msg == 2) {
                successMessage('The unit will not restart, click again if you would like to start');
            } else if(msg == 3){
                successMessage('Variable created, New run will start at the next report interval');
            } else {
                errorMessage('Something went wrong. Please try again later.');
            }
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong. Please try again later.');
        }
    });
}

function defaultSettings(serial){
    $.ajax({
        url: "/admin/irrigation/fota",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "cmd": 'settingsdeafult',
            "_token": token,
        },
        success: function(msg) {
            if (msg == 1) {
                $('#message').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Lagt til i kø til sensorenhet</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                });
            } else if (msg == 2){
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>En av kommandoene feilet</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            } else {
                const infoMessage = document.createElement('div');
                infoMessage.className = "message-r";
                infoMessage.appendChild(document.createTextNode("Something went wrong. Please try again later."));
                document.getElementById("content-main").appendChild(infoMessage);
                $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                    $(".message-r").remove();
                });
            }        
        },   
        error: function(data) {
            console.log(data);
        }
    });
}

function variables(serial){
    
    console.log(serial);
    $.ajax({
        url: "/admin/irrigation/fota",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "cmd": 'settings',
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
            if (msg == 1) {
                const infoMessage = document.createElement('div');
                infoMessage.className = "message-g";
                infoMessage.appendChild(document.createTextNode("Command added to Queue"));
                document.getElementById("content-main").appendChild(infoMessage);
                $(".message-g").fadeTo(4000, 0.8).slideUp(500, function() {
                    $(".message-g").remove();
                });
            } else if (msg == 2){
                const infoMessage = document.createElement('div');
                infoMessage.className = "message-r";
                infoMessage.appendChild(document.createTextNode("A command failed"));
                document.getElementById("content-main").appendChild(infoMessage);
                $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                    $(".message-r").remove();
                });
            } else {
                const infoMessage = document.createElement('div');
                infoMessage.className = "message-r";
                infoMessage.appendChild(document.createTextNode("Something went wrong. Please try again later."));
                document.getElementById("content-main").appendChild(infoMessage);
                $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                    $(".message-r").remove();
                });
            }
        },   
        error: function(data) {
            console.log(data);

        }
    });
}

    $(document).ready(function () {
        var dataSet = @php echo $queue; @endphp;
        var table = $('#table').DataTable({
            data: dataSet,
            pageLength: 10, // Number of entries
            responsive: true, // For mobile devices
            deferRender: true,
            select: true,
            columnDefs : [{ 
                responsivePriority: 1, targets: 4,
                'targets': 0,
                'checboxes': {
                    'selectRow': true
                },
            }],
            sorting: [ [3,'ASC']],
            columns: [
                { title: "#" },
                { title: "Status" },
                { title: "Command" },
                { title: "Created at" },
            ],
        });

        $('button.select-all').on('click', function(evt) {
            table.rows().select();
        });
            
        $('button.deselect-all').on('click', function(evt) {
            table.rows().deselect();
        });

        $('#delete').click( function () {
            var counter = table.rows('.selected').data().length;
            if (counter > 0) {
                var confirmed = confirm( 'Er du sikker på å at ønsker å slette ' + counter + ' rad(er)?' );
                if (confirmed) {
                    array = new Array();
                    for (i = 0; i < counter; i++) {
                        console.log( table.rows('.selected').data()[i][0]);
                        array[i] = table.rows('.selected').data()[i][0];
                    }
                    $.ajax({
                        url: "/admin/queue/delete",
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                            "array": array,
                            "_token": token,
                        },
                        success: function(data) {
                            console.log(data);
                            table.rows('.selected').remove().draw();
                        },   
                        error: function(data) {
                            console.log(data);

                        }
                    });
                }

            } else {
                alert('Marker rader du ønsker å slette.');
            }
        });


        var dataSet_2 = @php echo $variable['runtable']; @endphp;
        var runtable = $('#runtable').DataTable({
            data: dataSet_2,
            pageLength: 25, // Number of entries
            responsive: true, // For mobile devices
            deferRender: true,
            select: true,
            columnDefs : [{ 
                responsivePriority: 1, targets: 4,
                'targets': 0,
                'checboxes': {
                    'selectRow': true
                },
            }],
            sorting: [ [0,'ASC']],
            columns: [
                { title: "#" },
                { title: "Run ID" },
                { title: "Start" },
                { title: "End" },
                { title: "Start Time" },
                { title: "End Time" },
            ],
        });
        $('#runtable tbody').on( 'click', 'tr', function () {
            var datarow = runtable.row(this).data();
            var id = datarow[0];
            window.location='irrigationrun/'+id;
        });
    });

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
        getProbes(@json($variable['unit']['serialnumber']));
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
    </script>
@endsection