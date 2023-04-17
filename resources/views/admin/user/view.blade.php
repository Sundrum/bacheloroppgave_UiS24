@extends('layouts.app')

@section('content')
<div class="card card-rounded">
    <div class="row p-3">
        <div class="col-sm-12">
            <a href="{{route('newuser')}}" class="btn-7g float-end" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
        <div class="row pt-2">
            <div class="col-12">
                <table id="usertable" class="display" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script>
setTitle(@json( __('admin.users')));

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