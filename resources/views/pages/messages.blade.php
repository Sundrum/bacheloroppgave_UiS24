@extends('layouts.app')

@section('content')
<script>setTitle(@json(__('general.messages')))</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

<div class="row">
    <div class="col-12 card-rounded bg-white px-3 py-2">
        <table id="messagetable" class="display" width="100%"></table>
    </div>
</div>

<!-- Datatables script  -->
<script>
let dataSet = <?php echo $messages; ?>;
$(document).ready(function () {
//         $.ajax({ 
//             url: '/messages/customer',
//             dataType: 'json',      
//             success: function( data ) {
//                 dataSet = data;
//                 document.getElementById("site").remove();
//             },
//             error: function( data ) {
//                 errorMessage(@json(__('general.somethingwentwrong')));
//                 document.getElementById("site").remove();
//                 console.log(data);
//             }
//         });
        var table = $('#messagetable').DataTable({
            data: dataSet,
            order: 0,
            pageLength: 25, // amount of items
            columnDefs : [
            { 
                responsivePriority: 2, targets: 3 }
            ],
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