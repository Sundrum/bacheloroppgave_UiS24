@extends('layouts.app')

@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<div class="bg-white card-rounded p-2">
    <div class="m-4">
        <div class="row mt-3 mb-3">
            <div class="col-sm-12 text-center" id="actionBar" style="display: none;">
                <button id="button" class="btn-7g card-rounded mt-2">Update w/release</button>
                <button id="markall" class="btn-7s card-rounded mt-2">Mark all</button>
                <button id="delete" class="btn-7r card-rounded mt-2">Remove from Queue</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <input type="text" class="col-8" name="search-multiple" id="search-multiple" placeholder="Search Multiple E.g. 21-9030-AA-00001|21-9030-AA-00002">
                <button type="button" id="search-multiple-btn" class="btn-7g"><strong>Search</strong></button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <label class="switch">
                    <input type="checkbox" id="enableDetails" name="enableDetails"> 
                    <span class="slider round"></span>
                </label>
                <span>Enable details</span>
            </div>
        </div>

        {{-- @include('admin.apismartsensor.variablemodal') --}}

        <div class="modal" id="variableModal" aria-labelledby="variableModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title"></h4>
                        <button type="button" class="close btn-7r" onclick="$('#variableModal').hide();" data-dismiss="modal"><strong>&times;</strong></button>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="modal-body mt-2" id="modal-body">
                    
                    </div>
                </div>
            </div>
        </div>
        <table id="proxy" class="display" width="100%"></table>
    </div>
</div>

<script>
let type = @json(request()->product);

init();

function init() {
    if(type == "21-9030") {
        setTitle("Tek-Zence @ FOTA");
        document.getElementById("actionBar").style = "display: block;";
    } else {
        if(type == "21-1065") {
            document.getElementById("actionBar").style = "display: block;";
        }

        if(type == "21-1020") {
            document.getElementById("actionBar").style = "display: block;";
        }
        setTitle("Proxy Server");
    }
}

$(document).ready(function () {

    var dataSet = @php echo $data; @endphp;
    var table = $('#proxy').DataTable({
        stateSave: false,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        data: dataSet,
        'search': {
            "regex": true
        },
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
            { title: "SERIALNUMBER",
                data:"serialnumber",
                defaultContent: "<i>NaN</i>" },
            { title: "RSSI",
                data:"rssi",
                defaultContent: "" },
            { title: "FW",
                data: "swversion",
                defaultContent: "" },
            { title: "LAST CONNECT",
                data: "lastconnect",
                defaultContent: "",
                render: function(data) {
                    if(data) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                }
            },  
            { 
                title: 'FOTA',
                data: "fota_in_queue",
                defaultContent: "",
                render: function(data) {
                    if(data == 1) {
                        return "In Queue";
                    } else if (data == 3) {
                        return "Delivered";
                    } else {
                        return data;
                    }
                }

            },
            // { title: "COUNT Q",
            //     data: "fota_in_queue_count" },
            { title: "Axxe",
                data: "axxe_fix",
                defaultContent: "" },  
            { title: "IMEI",
                data: "imei",
                defaultContent: "" },
            // { title: "IMSI",
            //     data: "imsi",
            //     defaultContent: "" },            
            // { title: "ICCID",
            //     data: "iccid",
            //     defaultContent: "" },
            { title: "MCCMNC",
                data: "mccmnc",
                defaultContent: "",
                render: function(data) {
                    if(data == 24201) {
                        return "Telenor";
                    } else if (data == 24202) {
                        return "Telia";
                    } else {
                        return data;
                    }
                }
            },    
            { title: "Connect Mode",
                data: "connectmode",
                defaultContent: "",
                render: function(data) {
                    if(data == 7) {
                        return "LTE-M";
                    } else if (data == 9) {
                        return "NB-IoT";
                    } else {
                        return data;
                    }
                }
            },
            { 
                title: "SEQ",
                data: "sequencenumber",
                defaultContent: "" 
            },
            { 
                title: "REBOOT",
                data: "rebootcounter",
                defaultContent: "" 
            },
            { 
                title: "REBOOT AT",
                data: "reboot_at",
                defaultContent: "",
                render: function(data) {
                    if(data) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                }
            },
            { 
                title: "RESETCODE",
                data: "resetcode",
                defaultContent: ""
            },
        ],
    });

    $('#proxy tbody').on( 'click', 'tr', function () {
        var row = table.row( this ).data();
        getVariables(row.serialnumber);
        if(document.getElementById("enableDetails").checked) {
            $('#variableModal').toggle();
        }

    } );

    $('#button').click( function () {
        counter = table.rows('.selected').data().length;
        addLoadingSpinner();
        for (i = 0; i < counter; i++) {
            $.ajax({
                url: "/admin/proxy/fota",
                type: 'POST',
                dataType: 'json',
                data: { 
                    "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                    "swversion": table.rows('.selected').data()[i]['swversion'],
                    "_token": token,
                },
                success: function(data) {
                    console.log(data);
                    
                },   
                error: function(data) {
                    console.log('Error');
                    console.log(data);
                }
            });
            if(i == counter-1) removeLoadingSpinner();
        }
    });

    $('#search-multiple-btn').on('click', function () {
        var dateselected = document.getElementById("search-multiple").value;
        $('#proxy').DataTable().column(0).search("^" + dateselected + "$", true, false).draw();
        
    });
    
    $('#markall').click(function() {
        table.rows({search:'applied'}).every( function ( rowIdx, tableLoop, rowLoop ) {
            var rowNode = this.node();
            $(rowNode).toggleClass('selected');
            // console.log(rowNode);
            $(rowNode).find("tr td:visible").each(function (){
                // var cellRow = this;
                // console.log(rowNode);
                // // $(cellRow).toggleClass('selected');
                console.log(rowNode);
                $(this).toggleClass('selected');

                var cellData = $(this).text();
                console.log(cellData);
            });
        });
    });

    $('#delete').click( function () {
        counter = table.rows('.selected').data().length;
        alert( counter );
        for (i = 0; i < counter; i++) {
            $.ajax({
                url: "/admin/proxy/queue/delete",
                type: 'POST',
                dataType: 'json',
                data: { 
                    "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                    "_token": token,
                },
                success: function(data) {
                    console.log(data);
                },   
                error: function(data) {
                    console.log(data);
                    alert('Something went wrong')

                }
            });
        }
    });
});

function getVariables(serial) {
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
            let variables = `<div class="row">
                                <div class="col-12">
                                    <h5>Variables</h5>
                                </div>
                            </div>`;
            data.forEach((element) => {
                if(element) {}
                let variable = `<div class="row" id="${element.variable}">
                                    <div class="col-4 float-start">
                                        ${element.variable}
                                    </div>
                                    <div class="col-4 float-start">
                                        ${element.value}
                                    </div>
                                    <div class="col-4 float-end">
                                        ${moment(element.dateupdated).format('YYYY-MM-DD')}
                                    </div>
                                </div> 
                                <hr class="m-0">`;
                variables += variable;
                document.getElementById("modal-title").innerHTML = element.serialnumber;
            });

            variables += `
                        <div class="row mt-3">
                            <div class="col-5">
                                Variable
                            </div>

                            <div class="col-5">
                                Value
                            </div>
                        </div>
                        <div class="row my-auto">
                            <div class="col-5">
                                <input class="form-control" type="text" name="new_variable" id="new_variable" value="" maxlength="50">

                            </div>

                            <div class="col-5">
                                <input class="form-control" type="text" name="new_value" id="new_value" value="" maxlength="200">
                            </div>
                            <div class="col-2">
                                <button class="btn-7g" onclick="addVariable();">+ Add</button>
                            </div>
                        </div>`;
            document.getElementById("modal-body").innerHTML = variables;
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong')

        }
    });
}

function addVariable(){
        $.ajax({
            url: "/admin/proxy/setvariables",
            type: 'POST',
            dataType: 'json',
            data: { 
                "serialnumber": document.getElementById("modal-title").innerHTML,
                "variable": document.getElementById("new_variable").value,
                "value": document.getElementById("new_value").value,
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                successMessage("Variable added to status");
                getVariables(document.getElementById("modal-title").innerHTML);
            },   
            error: function(data) {
                console.log(data);
                errorMessage('Something went wrong')

            }
        });
    }

</script>
@endsection