@extends('layouts.admin')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><i class="fa fa-tag" aria-hidden="true"></i> <b>Users</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <a href="{{route('newuser')}}" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <table id="usertable" class="display" width="100%"></table>
</section>

<script>
$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    var table = $('#usertable').DataTable({
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
            { title: "Name" },
            { title: "Email" },
            { title: "Phone" },
        ],
    });
    
    $('#usertable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var userid = datarow[0];
        window.location='account/'+userid;
    });
});

</script>
@endsection