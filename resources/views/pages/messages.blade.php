@extends('layouts.app')

@section('content')

<h1>@lang('general.messages')</h1>

<table id="example" class="display" width="100%"></table>
<!-- DataTables CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- DataTables Extension for mobile support -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

<!-- Datatables script  -->
<script>
    $(document).ready(function () {
        var dataSet = <?php echo $messages; ?>;
        var table = $('#example').DataTable({
            data: dataSet,
            order: 0,
            pageLength: 25, // amount of items
            responsive: true, // hover
            columns: [
                { title: "Time", orderable: false, searchable: true},
                { title: "Serialnumber", orderable: false, searchable: true},
                { title: "Message", orderable: false, searchable: true},
                { title: "Delete", orderable: false, searchable: false},
            ]
        });
    });

    function deleteMessage(id) {
        var result = confirm("Do you want to delete this message?");
        if (result) {
            var token = "{{ csrf_token() }}";
            $.ajax({
                url: "/deleteMessage",
                type: 'POST',
                data: { 
                    "id": id,
                    "_token": token,
                },
                success: function(msg) {
                    console.log(msg);
                    window.location = "/messages";
                },   
                error:function(msg) {
                    alert("Problem deleting message, please try again later.")
                }
            });
        }
    }
</script>
@endsection