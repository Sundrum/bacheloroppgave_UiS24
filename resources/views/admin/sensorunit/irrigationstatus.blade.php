@extends('layouts.app')

@section('content')

<section class="row mb-3">
    <div class="col-6">
        <a class="btn-7r" href="/admin/irrigationstatus" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til Irrigation Status</strong></a>
    </div>
    <div class="col-6 text-end">
            <a class="btn-7g" href="/admin/irrigationdebug/{{$variable['unit']['serialnumber'] }}" style="color: black; text-decoration: none;"><strong>Waterlost Debug</strong></a>
    </div>
    <div class="col-md-6 mt-2">
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
        <div class="col-md-12 card card-rounded p-3">
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
                <div class="col-4 center-block">
                    <button class="btn-7s" data-toggle="collapse" href="#collapseVariable" aria-expanded="false" aria-controls="collapseVariable"><strong>Set Variables</strong></button>
                </div>
                <div class="col-4 center-block">
                    <button class="btn-7s" onclick="variables('{{$variable['unit']['serialnumber']}}')"><strong>Get Variables</strong></button>
                </div>
                <div class="col-4">
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

</div>
    @include('admin.sensorunit.changeserialnumber')
    {{-- @include('admin.firmware.upload') --}}

</section>
<script>
var token = "{{ csrf_token() }}";

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
    </script>
@endsection