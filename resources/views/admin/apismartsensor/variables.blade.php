@extends('layouts.app')

@section('content')
<div class="bg-white card-rounded p-2" id="serialinput">
    <div class="input-group">
        <span class="mr-3">Serialnumber</span>
        <input type="text" name="serialnumber" id="serialnumber" placeholder="Serialnumber">
    </div>
    <button class="btn-7g" onclick="getVariables()">Submit</button>
    <div class="m-3">
        <div id="table-section">
            <table id="variables" class="display" width="100%"></table>
        </div>
    </div>
    
</div>

<script>
setTitle('Proxy Variables');
var table;
function getVariables() {
    var serial = document.getElementById("serialnumber").value
    $.ajax({
        url: "/admin/proxy/getvariables",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "_token": token,
        },
        success: function(data) {
            console.log(data);
            if($.fn.DataTable.isDataTable( '#variables' )) {
                // $('#variables').DataTable().clear().destroy();
                data.forEach((element) => table.row.add(element).draw());
                
            } else {
                table = $('#variables').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    stateSave: true,
                    data: data,
                    pageLength: 25, // Number of entries
                    responsive: true, // For mobile devices
                    sorting: [ [0,'ASC'],[3,'ASC']],
                    columnDefs : [{ 
                        responsivePriority: 1, targets: 3,
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
                            title: "Serialnumber",
                            data: "serialnumber"
                        },
                        { 
                            title: "Variable",
                            data: "variable",
                            defaultContent: " "
                        },
                        { 
                            title: "Value",
                            data: "value",
                            defaultContent: " " 
                        },
                        { 
                            title: "Last updated",
                            data: "dateupdated",
                            render: function(data) {
                                return moment(data).format("YYYY-MM-DD HH:mm");
                            }
                        }
                    ],
                });
            }   

            
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong')

        }
    });
}

</script>
@endsection