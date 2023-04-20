@extends('layouts.app')

@section('content')
<section class="row mb-3">
    <div class="col-12">
        <div class="col-12 card card-rounded p-3">
            <div class="row mt-1 mb-2">
                
                <div class="col">
                    <p class="mb-0">To select multiple rows use shift and click.</p>
                    <p class="mt-0">Select all - will select all entries. Notice that you might not see every entrie.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col text-center">
                    <button class="btn-7s mr-2" data-toggle="modal" data-target="#uploadModal">Upload Firmware</button>
                    <button id="change" class="btn-7g ml-2 mr-2">Toggle Released</button>
                    <button id="delete" class="btn-7r ml-2">Delete</button>

                </div>
            </div>

            <table id="table" class="display" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Productnumber</th>
                        <th>Firmware name</th>
                        <th>Version</th>
                        <th>Released</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($firmware['result'])
                        @foreach ($firmware['result'] as $row)
                            <tr>
                                <td></td>
                                <td>{{$row['firmware_id'] ?? ''}}</td>
                                <td>{{$row['productnumber'] ?? ''}}</td>
                                <td>{{$row['firmwarename'] ?? ''}}</td>
                                <td>{{$row['major'] ?? 'NaN'}}.{{$row['minor'] ?? 'NaN'}}.{{$row['patch'] ?? 'NaN'}}-{{$row['build'] ?? 'unknown'}}</td>
                                @if($row['released'])
                                    <td><i class="ml-3 fa fa-lg fa-check text-g"></i></td>
                                @else
                                    <td><i class="ml-3 fas fa-lg fa-times text-r"></i></td>
                                @endif
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Productnumber</th>
                        <th>Firmware name</th>
                        <th>Version</th>
                        <th>Released</th>
                    </tr>
                </tfoot>

            </table>
            <div class="row  justify-content-center mb-3">
                <div class="col text-center">
                    <button class="mr-3 btn-7s select-all">Select All</button>
                    <button class="ml-3 btn-7s deselect-all">Deselect All</button>
                </div>
            </div>
        </div>
    </div>
</section>
@include('admin.firmware.upload')

<script>

setTitle('Firmware @ Proxy');

$(document).ready(function () {
    var table = $('#table').DataTable({
        pageLength: 25, // Number of entries
        responsive: true, // For mobile devices
        deferRender: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        }],
        sorting: [ [1,'ASC']],
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
                    console.log( table.rows('.selected').data()[i][1]);
                    array[i] = table.rows('.selected').data()[i][1];
                }
                $.ajax({
                    url: "/admin/firmware/delete",
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                        "array": array,
                        "_token": token,
                    },
                    success: function(data) {
                        console.log(data);
                        const infoMessage = document.createElement('div');
                        infoMessage.className = "message-g";
                        infoMessage.appendChild(document.createTextNode(data));
                        document.getElementById("content-main").appendChild(infoMessage);
                        $(".message-g").fadeTo(4000, 0.8).slideUp(500, function() {
                            $(".message-g").remove();
                        });
                        table.rows('.selected').remove().draw();
                    },   
                    error: function(data) {
                        console.log('ERROR ' + data);
                        const infoMessage = document.createElement('div');
                        infoMessage.className = "message-r";
                        infoMessage.appendChild(document.createTextNode("Something went wrong, please try again"));
                        document.getElementById("content-main").appendChild(infoMessage);
                        $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                            $(".message-r").remove();
                        });

                    }
                });
            }

        } else {
            const infoMessage = document.createElement('div');
            infoMessage.className = "message-r";
            infoMessage.appendChild(document.createTextNode("Marker rader du ønsker å slette"));
            document.getElementById("content-main").appendChild(infoMessage);
            $(".message-r").fadeTo(4000, 0.8).slideUp(500, function() {
                $(".message-r").remove();
            });

        }
    });

    $('#change').click( function () {
        const infoMessage = document.createElement('div');
        infoMessage.className = "message-g";
        infoMessage.appendChild(document.createTextNode("This function is not yet supported"));
        document.getElementById("content-main").appendChild(infoMessage);
        $(".message-g").fadeTo(4000, 0.8).slideUp(500, function() {
            $(".message-g").remove();
        });
    });
});
</script>
@endsection