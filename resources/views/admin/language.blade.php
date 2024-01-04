@extends('layouts.app')

@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<section class="bg-white card-rounded p-2">
    <div class="m-3">
        <div id="table-section p-3">
            <table id="language" class="display" width="100%"></table>
        </div>
    </div>
</section>

<!-- Datatables script  -->
<script>
    setTitle('Language');
    var table; 
    let dataSet = @php echo $response; @endphp;
    console.log(dataSet);
    $(document).ready(function () {
        initTable(dataSet);
    });
    
    function initTable(dataSet) {
        table = $('#language').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            data: dataSet,
            pageLength: 25, // Number of entries
            responsive: true, // For mobile devices
            sorting: [ [0,'ASC']],
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
                    title: "Variable",
                    data: "index",
                    defaultContent: ""
                },
                { 
                    title: "English",
                    data:"en",
                    defaultContent: ""
                },
                { 
                    title: "Norwegian",
                    data: "no",
                    defaultContent: ""
                    
                },
                { 
                    title: "French",
                    data: "fr",
                    defaultContent: ""
                },
            ],
        });        
    }
</script>
@endsection