@extends('layouts.app')

@section('content')
<section class="">
    <div class="row mt-3 mb-3">
        <div class="col-12">
            <a onclick="window.location='newsensorunit'" class="btn-7g float-end" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 card-rounded bg-white p-3">
            <table id="unittable" class="display" width="100%"></table>
        </div>
    </div>
</section>

<script>
    setTitle('Sensor units');
$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    var table = $('#unittable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
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