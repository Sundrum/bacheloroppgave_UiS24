@extends('layouts.admin')

@section('content')
<section class="container">
    <div class="row justify-content-center mt-3">
            <h2><b>Irrigation Sensor</b> {{$variable->unit->sensorunit_name ?? ''}} </h2>
    </div>
    <div class="row">
        <a class="btn-primary-outline mt-3 " href="/admin/irrigationstatus" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til Irrigation Status</strong></a>
        <a class="btn-primary-outline ml-auto mr-0 mt-3" href="/admin/irrigationdebug/{{$variable['unit']['serialnumber'] }}" style="color: black; text-decoration: none;"><strong>Waterlost Debug</strong></a>
    </div>
{{-- FROM irrigationController --}}
    <div class="row mt-3 mb-3">
        <div class="col-md-6 card card-rounded">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mt-2 mb-2">Innstillinger</h5>
                    {{-- @dd($variable) --}}
                    <div class="row">
                        <div class="col-6">
                            Serialnumber
                        </div>
                        <div class="col-6">
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
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Firmware
                        </div>
                        <div class="col-6">
                            {{$variable['swversion']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Bootloader
                        </div>
                        <div class="col-6">
                            {{$variable['bootloader_version']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            IMEI
                        </div>
                        <div class="col-6">
                            {{$variable['imei']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Idle Sleep Time
                        </div>
                        <div class="col-6">
                            {{$variable['idle_sleep_time']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="mt-0">
                    <h5 class="mb-2">Settling Innstillinger</h5>
                    <div class="row">
                        <div class="col-6">
                            Time to Irrigation
                        </div>
                        <div class="col-6">
                            {{$variable['settling_time_to_active']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Time to Idle
                        </div>
                        <div class="col-6">
                            {{$variable['settling_time_to_idle']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Vibration threshold
                        </div>
                        <div class="col-6">
                            {{$variable['vibration_settling_threshold_low']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Tilt threshold
                        </div>
                        <div class="col-6">
                            {{$variable['tilt_settling_threshold_low']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-6">
                            Pressure threshold
                        </div>
                        <div class="col-6">
                            {{$variable['pressure_settling_threshold_low']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @php ksort($variable) @endphp
        {{dd($variable)}} --}}
        <div class="col-md-6  card card-rounded">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mt-2 mb-2">Irrigation Innstillinger</h5>
                    <div class="row">
                        <div class="col-4">
                            Måling
                        </div>
                        <div class="col-3">
                            Grense
                        </div>
                        <div class="col-3">
                            Intervall
                        </div>
                        <div class="col-2">
                            
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-4">
                            Vibration
                        </div>
                        <div class="col-3">
                            {{-- @dd($variable) --}}
                            {{$variable['vibration_threshold_low']['value'] ?? 'Ukjent'}}
                        </div>
                        <div class="col-3">
                            {{$variable['vibration_interval']['value'] ?? 'Ukjent'}}
                        </div>
                        <div class="col-2">
                            {{$variable['vibration_trigger_sending']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-4">
                            Tilt Δ
                        </div>
                        {{-- @dd($variable['tilt_threshold_low']['value']) --}}
                        <div class="col-3">
                            {{-- {{$variable['tilt_threshold_low']['value'] ?? 'Ukjent'}} --}}
                            {{($variable['tilt_threshold_high']['value']) ?? 'Ukjent' }} °
                        </div>
                        <div class="col-3">
                            {{$variable['tilt_interval']['value'] ?? 'Ukjent'}}
                        </div>
                        <div class="col-2">
                            {{$variable['tilt_trigger_sending']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-4">
                            Pressure
                        </div>
                        <div class="col-3">
                            {{$variable['pressure_threshold_low']['value'] ?? 'N/A'}}
                        </div>
                        <div class="col-3">
                            {{$variable['pressure_interval']['value'] ?? '-'}}
                        </div>
                        <div class="col-2">
                            {{$variable['pressure_trigger_sending']['value'] ?? '-'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-7">
                            GNSS
                        </div>
                        <div class="col-3">
                            {{$variable['gnss_interval']['value'] ?? 'Ukjent'}}
                        </div>
                        <div class="col-2">
                            {{$variable['gnss_trigger_sending']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                        <div class="col-7">
                            Battery
                        </div>
                        <div class="col-3">
                            {{$variable['battery_interval']['value'] ?? 'Ukjent'}}
                        </div>
                        <div class="col-2">
                            {{$variable['battery_trigger_sending']['value'] ?? 'Ukjent'}}
                        </div>
                    </div>
                    <hr class="mt-0">

                    <h5 class="mb-2">Kommandoer</h5>
                    <div class="row justify-content-center mb-2">
                        <div class="col-4 center-block">
                            <button class="btn-primary-filled" data-toggle="collapse" href="#collapseVariable" aria-expanded="false" aria-controls="collapseVariable"><strong>Set Variables</strong></button>
                        </div>
                        <div class="col-4 center-block">
                            <button class="btn-primary-filled" onclick="variables('{{$variable['unit']['serialnumber']}}')"><strong>Get Variables</strong></button>
                        </div>
                        <div class="col-4">
                            <div class="btn-group">
                                <button type="button" class="btn-primary-filled dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <strong>FOTA</strong>
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item disabled" href="#">Released</a>
                                  @if(isset($variable['firmware']['released']))
                                    @foreach ($variable['firmware']['released'] as $row)
                                    <a class="dropdown-item" onclick="FOTA('{{$variable['unit']['serialnumber']}}',{{$row['firmware_id']}});">{{$row['productnumber']}} {{$row['version']}}</a>
                                    @endforeach
                                  @endif
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item disabled" href="#">Not Released</a>
                                  @if(isset($variable['firmware']['notreleased']))
                                    @foreach ($variable['firmware']['notreleased'] as $row)
                                        <a class="dropdown-item" onclick="FOTA('{{$variable['unit']['serialnumber']}}',{{$row['firmware_id']}});">{{$row['productnumber']}} {{$row['version']}}</a>
                                    @endforeach
                                  @endif
                                {{-- <div class="dropdown-divider"></div>
                                    <a class="dropdown-item disabled" href="#">New Firmware</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#uploadModal">Upload Firmware</a> --}}
                                </div>

                              </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mb-2">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.sensorunit.setvariable')
    <div class="row">
        <div class="col-12 card card-rounded">
            <div class="row mt-4 mb-2">
                <div class="col-8">
                    <h3>Queue for Unit</h3>
                </div>
                <div class="col-4 my-auto">
                    <button id="delete" class="btn btn-danger card-rounded float-right">Delete</button>
                </div>
                <div class="col">
                    <p class="mb-0">To select multiple rows use shift and click.</p>
                    <p class="mt-0">Select all - will select all entries. Notice that you might not see every entrie.</p>
                </div>
            </div>
            <table id="table" class="display" width="100%"></table>
            <div class="row  justify-content-center mb-3">
                <button type="button" class="mr-3 btn btn-primary card-rounded select-all">Select All</button>
                <button type="button" class="ml-3 btn btn-primary card-rounded deselect-all">Deselect All</button>
            </div>
        </div>
    </div>
    @include('admin.sensorunit.changeserialnumber')
    @include('admin.firmware.upload')

</section>
<script>
var token = "{{ csrf_token() }}";

$('#changeserial').click( function () {
        $('#changeSerialModal').modal('show');
} );

function FOTA(serial, fw){
    $.ajax({
        url: "/admin/irrigation/fota",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "cmd": 'fota',
            "fw": fw,
            "_token": token,
        },
        success: function(msg) {
            if (msg == 1) {
                $('#message').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Lagt til i kø til sensorenhet</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                });

                setTimeout(function(){ 
                    location.reload();
                }, 3000);

            } else if (msg == 2){
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>En av kommandoene feilet</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            } else {
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            }
        },   
        error: function(data) {
            console.log(data);

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
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
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
                $('#message').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
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
    });
    
    
    </script>
@endsection