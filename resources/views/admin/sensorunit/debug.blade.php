@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Waterlost Debug</h1>
    <div class="row">
        <a class="btn-primary-outline mt-2 mb-2" href="/admin/irrigationstatus/{{$serial}}" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til {{$serial}}</strong></a>
    </div>
    <table id="irrtable" class="display" width="100%"></table>
</div>


    <!-- DataTables Extension for mobile support -->
    {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css"> --}}

    <!-- Datatables script  -->
    <script>
    $(document).ready(function () {
        var dataSet = @php echo $data; @endphp;
        table = $('#irrtable').DataTable({
            data: dataSet,
            pageLength: 100, // Number of entries
            responsive: true, // For mobile devices
            sorting: [ [0,'ASC']],
            columnDefs : [{ 
                responsivePriority: 1, targets: 9,
                'targets': 0,
                'checboxes': {
                    'selectRow': true
                },
            }],
            'select': {
                style: 'multi'
            },
            columns: [
                { title: "Time" },
                { title: "State" },
                { title: "Run ID" },
                { title: "Lat" },
                { title: "Lng" },
                { title: "RSSI" },
                { title: "Tilt" },
                { title: "Vib" },
                { title: "Vbat" },
                { title: "Pressure" },
                { title: "Flowrate" },
                { title: "Sequence" },
            ],
        });
    });
    </script>

    
@endsection