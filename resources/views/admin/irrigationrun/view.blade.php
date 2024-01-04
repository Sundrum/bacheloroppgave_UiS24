@extends('layouts.app')

@section('content')
<div class="card card-rounded p-3 mt-3">
    <div class="row mb-3">
        <div class="col-12">
            <table id="logtable" class="display" width="100%"></table>
        </div>
    </div>
</div>

<div class="modal" id="editRun">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-grey">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="m-auto">Edit Run</h4>
          <button type="button" class="btn-7r" onclick="$('#editRun').hide();">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body mt-3 mb-3">
            <form method="POST" id="formEditRun">
                @csrf
                <div class="row">
                    <h5 id="title_edit"></h5>
                </div>
                <div class="row">
                    <input type="hidden" name="log_id" id="log_id">
                    <div class="col-md-6">
                        <label class="col-md-4 col-form-label">Start</label>
                        <div class="input-group col-md-6">
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" id="starttime" name="starttime" required>
                            </div>
                        </div>
                        <div class="input-group col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="startpoint" name="startpoint" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="user_name" class="col-md-4 col-form-label">End</label>
                        <div class="input-group col-md-6">
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" id="endtime" name="endtime" required>
                            </div>
                        </div>
                        <div class="input-group col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="endpoint" name="endpoint" required>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row justify-content-center mt-3">
                    <div class="col text-center">
                        <button type="submit" class="btn-7s"> Save </button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>

<script>
setTitle('Irrigation Run');

var table; 
    $(document).ready(function () {
        let dataSet = @json($runs);
        table = $('#logtable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            data: dataSet,
            pageLength: 25, // Number of entries
            responsive: true, // For mobile devices
            sorting: [ [0,'ASC'],[4,'ASC']],
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
                { 
                    title: "ID",
                    width: "5%",
                    data: "log_id",
                },
                { 
                    title: "Serienummer",
                    data: "serialnumber",
                },
                { 
                    title: "Start",
                    data: "irrigation_startpoint",
                    defaultContent: "-",
                    width: "8%",
                },
                { 
                    title: "Finish",
                    data: "irrigation_endpoint",
                    defaultContent: "-",
                    width: "8%",
                },
                { 
                    title: "Starttime",
                    data: "irrigation_starttime",
                    defaultContent: "-",
                    width: "8%",
                },
                {                     
                    title: "Finishtime",
                    data: "irrigation_endtime",
                    defaultContent: "-",
                    width: "8%",
                },
            ],
        });
        $('#logtable tbody').on( 'click', 'tr', function () {
            var datarow = table.row(this).data();
            var id = datarow['log_id'];
            console.log(id);

            $.ajax({
            url: "/admin/irrigationrun/get/" + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function (data) {
                document.getElementById("log_id").value = data.log_id;
                document.getElementById("title_edit").innerHTML = "Edit on " + data.log_id + " for serialnumber " + data.serialnumber;
                document.getElementById("starttime").value = datetimeLocal(data.irrigation_starttime, 0);
                document.getElementById("startpoint").value = data.irrigation_startpoint;
                document.getElementById("endtime").value = datetimeLocal(data.irrigation_endtime, 1);
                document.getElementById("endpoint").value = data.irrigation_endpoint;
                $('#editRun').show();
                console.log(data)
            },
        })

        });
    });

    document.getElementById("formEditRun").onsubmit = function () {
        event.preventDefault();
        console.log(document.getElementById("starttime").value)
        $.ajax({
            url: "/admin/irrigationrun/edit",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: $('#formEditRun').serialize(),
            success: function(msg) {
                console.log(msg);
                if (msg == 1) {
                    $('#editRun').hide();
                    successMessage('Succsessfully changed');
                } else if (msg == 2){
                    errorMessage('Error...');
                } else {
                    errorMessage('E2 - Something went wrong. Please try again later.');
                }
            },   
            error: function(data) {
                console.log(data);
                errorMessage('Something went wrong. Please try again later.');
            }
        });
    };
    function datetimeLocal(datetime, add) {
        let dt = new Date(datetime);
        if(dt.getSeconds() > 0 && add == 1) {
            console.log(dt.getSeconds());
            dt.setMinutes(dt.getMinutes() + 1);
        } else {
            dt.setMinutes(dt.getMinutes());
        }

        return dt.toISOString().slice(0, 16);
    }
</script>
@endsection