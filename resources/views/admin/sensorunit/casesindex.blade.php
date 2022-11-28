@extends('layouts.admin')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><b>Cases</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <a onclick="window.location='cases/new'" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a>
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
            { title: "Customer" },
            { title: "Case Manager" },
            { title: "Service ID" },
            { title: "Status" },
            { title: "Slett" , orderable: false, searchable: false },

        ],
    });
    
    $('#unittable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var id = datarow[0];
        window.location='/admin/sensorunit/cases/'+id;
    });
});

    function deleteCase(id) {
        console.log(id);
        var confirmed = confirm('Ønsker du å slette denne saken?');
        if(confirmed) {
            $.ajax({
                url: "/admin/sensorunit/casesdelete",
                type: 'POST',
                data: { 
                    "case_id": id,
                    "_token": token,
                },
                success: function(msg) {
                    alert("saken ble slettet.")
                    console.log(msg);
                    location.reload();
                    // $('#'+counter).remove();
                },   
                error: function(msg) {
                    alert("Failed - Please try again")
                    console.log(msg);
                }
            });
        }
    }

</script>
@endsection