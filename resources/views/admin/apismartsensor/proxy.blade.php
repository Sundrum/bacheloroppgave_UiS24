@extends('layouts.app')

@section('content')
<div class="bg-white card-rounded p-2">
    <div class="m-4">
        <div class="row mt-3 mb-3">
            <div class="col-sm-7">
                <button id="button" class="btn-7g card-rounded mt-2">Update w/release</button>
                <button id="markall" class="btn-7s card-rounded mt-2">Mark all</button>
                <button id="delete" class="btn-7r card-rounded mt-2">Remove from Queue</button>
    
                {{-- <a href="{{route('newuser')}}" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a> --}}
            </div>
        </div>
        <table id="proxy" class="display" width="100%"></table>
    </div>
</div>

<script>
document.getElementById("top-title").innerHTML = 'Tek-Zence @ FOTA';

$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    var table = $('#proxy').DataTable({
        data: dataSet,
        pageLength: 100, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 4,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
        'select': {
            style: 'multi'
        },
        columns: [
            { title: "SERIALNUMBER",
                data:"serialnumber" },
            { title: "RSSI",
                data:"rssi" },
            { title: "FW",
                data: "swversion" },
            { title: "LAST CONNECT",
                data: "lastconnect" },
            { title: "LAST FOTA IN QUEUE",
                data: "queue_at" },
            { title: "QUEUE LAST UPDATE",
                data: "queue_updated_at" },    
            { title: "ID Q",
                data: "fota_in_queue" },
            // { title: "COUNT Q",
            //     data: "fota_in_queue_count" },
            { title: "IMEI",
                data: "imei" },
            { title: "IMSI",
                data: "imsi" },
            { title: "MCCMNC",
                data: "mccmnc" },                
            { title: "ICCID",
                data: "iccid" },
        ],
    });

    $('#table tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );

    $('#button').click( function () {
        counter = table.rows('.selected').data().length;
        alert( counter );
        for (i = 0; i < counter; i++) {
            $.ajax({
                url: "/admin/proxy/fota",
                type: 'POST',
                dataType: 'json',
                data: { 
                    "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                    "swversion": table.rows('.selected').data()[i]['swversion'],
                    "_token": token,
                },
                success: function(data) {
                    console.log(data);
                },   
                error: function(data) {
                    console.log(data);
                    alert('Something went wrong')

                }
            });
        }
    });
    
    $('#markall').click(function() {
        table.rows({search:'applied'}).every( function ( rowIdx, tableLoop, rowLoop ) {
            var rowNode = this.node();
            $(rowNode).toggleClass('selected');
            // console.log(rowNode);
            $(rowNode).find("tr td:visible").each(function (){
                // var cellRow = this;
                // console.log(rowNode);
                // // $(cellRow).toggleClass('selected');
                console.log(rowNode);
                $(this).toggleClass('selected');

                var cellData = $(this).text();
                console.log(cellData);
            });
        });
    });

    $('#delete').click( function () {
        counter = table.rows('.selected').data().length;
        alert( counter );
        for (i = 0; i < counter; i++) {
            $.ajax({
                url: "/admin/proxy/queue/delete",
                type: 'POST',
                dataType: 'json',
                data: { 
                    "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                    "_token": token,
                },
                success: function(data) {
                    console.log(data);
                },   
                error: function(data) {
                    console.log(data);
                    alert('Something went wrong')

                }
            });
        }
    });
});

</script>
@endsection