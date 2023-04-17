@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><b>Queue to Irrigation</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <button id="button" class="btn btn-success card-rounded">Count</button>
            <button id="markall" class="btn btn-primary card-rounded">Mark all</button>
            <button id="delete" class="btn btn-danger card-rounded">Delete</button>

            {{-- <a href="{{route('newuser')}}" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a> --}}
        </div>
    </div>
    <table id="table" class="display" width="100%"></table>
</section>

<script>
$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    var table = $('#table').DataTable({
        data: dataSet,
        pageLength: 25, // Number of entries
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
            { title: "#" },
            { title: "Serialnumber" },
            { title: "Status" },
            { title: "Command" },
            { title: "Updated at" },
            { title: "Created at" },
        ],
    });
    
    $('#table tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );

    $('#button').click( function () {
        counter = table.rows('.selected').data().length;
        alert( counter );
        for (i = 0; i < counter; i++) {
            console.log( table.rows('.selected').data()[i][0] );
            console.log( table.rows() )
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
        if (counter > 0) {
            var confirmed = confirm( 'Er du sikker på å at ønsker å slette ' + counter + ' rad(er)?' );
            if (confirmed) {
                for (i = 0; i < counter; i++) {
                    console.log( table.rows('.selected').data()[i][0] );
                    table.rows('.selected').data()[i][0].remove().draw();
                    console.log( table.row() )
                }
            }
        } else {
            alert('Marker rader du ønsker å slette.');
        }
    });
});


</script>
@endsection