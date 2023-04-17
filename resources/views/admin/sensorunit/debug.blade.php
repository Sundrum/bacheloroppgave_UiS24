@extends('layouts.app')

@section('content')
<div class="row">
    <a class="mb-2" href="/admin/irrigationstatus/{{$serial}}" style="color: black; text-decoration: none;"><button class="btn-outline-7r"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Tilbake til {{$serial}}</strong></button></a>
</div>
<div class="card-rounded bg-white px-3 py-2">
    <table id="irrtable" class="display" width="100%"></table>
</div>
<script>
setTitle('Waterlost Debug');
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
            { 
                title: "Time",
                data: "timestamp",
                defaultContent: "<i>NaN</i>"
            },
            { 
                title: "State",
                data: "img" ,
                defaultContent: "<i>NaN</i>"
            },
            { 
                title: "State",
                data: "0",
                defaultContent: "<i>NaN</i>" 
            },
            { 
                title: "Vib", 
                data: "1",
                defaultContent: "<i>NaN</i>" 
            },
            { 
                title: "Waterlost",
                data: "2",
                defaultContent: "<i>NaN</i>"  
            },
            { 
                title: "Tilt Alert",
                data: "3",
                defaultContent: "<i>NaN</i>" 
            },
            { 
                title: "Tilt Abs",
                data: "4",
                defaultContent: "<i>NaN</i>"  
            },
            {   
                title: "Tilt Rel",
                data: "5",
                defaultContent: "<i>NaN</i>"  
            },            
            { 
                title: "Btn",
                data: "9",
                defaultContent: "<i>NaN</i>"  
            },
            { 
                title: "Temp",
                data: "10",
                defaultContent: "<i>NaN</i>"  
            },
            { 
                title: "RH",
                data: "11",
                defaultContent: "<i>NaN</i>"  
            },
            { 
                title: "Lat",
                data: "13",
                defaultContent: "<i>NaN</i>" 
            },            
            { 
                title: "Lng",
                data: "14",
                defaultContent: "<i>NaN</i>" 
            },
            { 
                title: "Vbat",
                data: "15",
                defaultContent: "<i>NaN</i>" 
            },
            { 
                title: "RSSI",
                data: "16",
                defaultContent: "<i>NaN</i>"  
            },
        ],
    });
});
</script>

    
@endsection