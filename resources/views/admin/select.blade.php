@extends('layouts.app')

@section('content')
<div class="bg-white card-rounded px-3 py-2">    
    <div class="row py-2">
        <div class="col-6">
            <span>Click user ID to expand user on mobile</span>
        </div>
        <div class="col-6">
            <button class="btn-7y float-end" onclick="window.location.href='/admin/irrigationstatus'">Show All Irrigation Units</button>
        </div>
    </div>
   
    {{-- <!-- DataTables Extension for mobile support -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css"> --}}

    <table id="selectusertable" class="display" width="100%"></table>
</div>
<!-- Datatables script  -->
<script>
setTitle(@json( __('navbar.selectuser')));
$(document).ready(function () {
    var dataSet = @php echo $data; @endphp;
    $('#selectusertable').DataTable({
        data: dataSet,
        pageLength: 25, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [
            { 
                responsivePriority: 1, targets: 5 }
            ],
        columns: [
            { title: "User ID" },
            { title: "Name" },
            { title: "Username" },
            { title: "Customer Name" },
            { title: "Customer Number" },
            { title: "Action", orderable: false, searchable: false },
        ],
    });
});

function changeUser(userid, customernumber) {
    var token = "{{ csrf_token() }}";
    $.ajax({
        url: "/selectuser",
        type: 'POST',
        data: { 
            "userid": userid,
            "customernumber": customernumber,
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
            window.location = "/dashboard";
        },   
        error:function(msg) {
            alert("Problem selecting user, please try again later or contact support.")
        }
    });
}
</script>
@endsection