@extends('layouts.admin')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><b>Sensorunit</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <a onclick="window.location='newunit'" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <table id="unittable" class="display" width="100%"></table>
</section>

<script>
$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    var table = $('#unittable').DataTable({
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
            { title: "Name" },
            { title: "Customer" },
            { title: "Product" },

        ],
    });
    
    $('#unittable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var id = datarow[0];
        window.location='sensorunit/'+id;
    });
});

</script>
@endsection