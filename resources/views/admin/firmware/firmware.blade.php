@extends('layouts.admin')

@section('content')
<section class="container">
    @if (request()->message)
        <div class="alert alert-success">{{ request()->message }}</div>
    @endif

    <div class="row">
        <div class="col-12 card card-rounded">
            <div class="row mt-4 mb-2">
                <div class="col-8">
                    <h3>Firmware</h3>
                </div>
                <div class="col-4 my-auto">

                </div>
                <div class="col">
                    <p class="mb-0">To select multiple rows use shift and click.</p>
                    <p class="mt-0">Select all - will select all entries. Notice that you might not see every entrie.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-success card-rounded mr-2" data-toggle="modal" data-target="#uploadModal">Upload Firmware</button>
                <button id="change" class="btn btn-primary card-rounded ml-2 mr-2">Toggle Released</button>
                <button id="delete" class="btn btn-danger card-rounded ml-2">Delete</button>
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
                    @foreach ($firmware['result'] as $row)
                        <tr>
                            <td></td>
                            <td>{{$row['firmware_id'] ?? ''}}</td>
                            <td>{{$row['productnumber'] ?? ''}}</td>
                            <td>{{$row['firmwarename'] ?? ''}}</td>
                            <td>{{$row['version'] ?? ''}}</td>
                            @if($row['released'])
                                <td><i class="ml-3 fa fa-lg fa-check text-success"></i></td>
                            @else
                                <td><i class="ml-3 fas fa-lg fa-times text-danger"></i></td>
                            @endif
                        </tr>
                    @endforeach
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
                <button type="button" class="mr-3 btn btn-primary card-rounded select-all">Select All</button>
                <button type="button" class="ml-3 btn btn-primary card-rounded deselect-all">Deselect All</button>
            </div>
        </div>
    </div>
</section>
@include('admin.firmware.upload')
<script>
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
                        table.rows('.selected').remove().draw();
                    },   
                    error: function(data) {
                        console.log(data);
                        alert('Something went wrong')

                    }
                });
            }

        } else {
            alert('Marker rader du ønsker å slette.');
        }
    });

    $('#change').click( function () {
        var counter = table.rows('.selected').data().length;
        if (counter > 0) {
            var confirmed = confirm( 'Er du sikker på å at ønsker å endre ' + counter + ' rad(er)?' );
            if (confirmed) {
                array = new Array();
                for (i = 0; i < counter; i++) {
                    console.log( table.rows('.selected').data()[i][1]);
                    array[i] = table.rows('.selected').data()[i][1];
                }
                $.ajax({
                    url: "/admin/firmware/change",
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                        "array": array,
                        "_token": token,
                    },
                    success: function(data) {
                        if(data == 1) {
                            location.reload();
                        } else {
                            console.log(data);
                            alert('Something went wrong')
                        }
                    },   
                    error: function(data) {
                        console.log(data);
                        alert('Something went wrong')
                    }
                });
            }

        } else {
            alert('Marker rader du ønsker å endre.');
        }
    });
});
</script>
@endsection