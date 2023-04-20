@extends('layouts.app')

@section('content')

<section class="bg-white card-rounded">
    <div class="m-3">
        <div class="col-12 pt-3">
            @include('admin.sensorunit.irrigationmap')
        </div>
        <div class="col-12 mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_7.png')}}" onclick="setSearch('state7');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="off_season">
                        <div class="col text-center">
                            {{$variable['off_season'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_6.png')}}" onclick="setSearch('state6');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="post_settling">
                        <div class="col text-center">
                            {{$variable['post_settling'] ?? '0'}}
                        </div>
                    </div>
                </div>

                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_5.png')}}" onclick="setSearch('state5');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="irrigation">
                        <div class="col">
                            {{$variable['irrigation'] ?? '0'}}
                        </div>
                    </div>
                </div>

                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_4.png')}}" onclick="setSearch('state4');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="settling">
                        <div class="col">
                            {{$variable['settling'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_3.png')}}" onclick="setSearch('state3');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_activity">
                        <div class="col">
                            {{$variable['idle_activity'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_2.png')}}" onclick="setSearch('state2');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_clock_wait">
                        <div class="col">
                            {{$variable['idle_clock_wait'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_1.png')}}" onclick="setSearch('state1');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_green">
                        <div class="col">
                            {{$variable['idle_green'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_0.png')}}" onclick="setSearch('state0');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle">
                        <div class="col">
                            {{$variable['idle'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state.png')}}" onclick="setSearch('state-1');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="notused">
                        <div class="col">
                            {{$variable['notused'] ?? 0}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-3">
        <div id="table-section">
            <table id="irrtable" class="display" width="100%"></table>
        </div>
    </div>
</section>

<!-- Datatables script  -->
<script>
   setTitle('Irrigation Overview');
   
    var table;
    $(document).ready(function () {
        var dataSet = @php echo $data; @endphp;
        table = $('#irrtable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            data: dataSet,
            pageLength: 25, // Number of entries
            responsive: true, // For mobile devices
            sorting: [ [0,'ASC'],[5,'ASC']],
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
                    title: "Status",
                    width: "5%"
                },
                { title: "Serienummer" },
                { title: "Navn" },
                { title: "Versjon" },
                { title: "Kunde" },
                { title: "Siste levert" },
                { title: "" },
            ],
        });
        $('#irrtable tbody').on( 'click', 'tr', function () {
            var datarow = table.row(this).data();
            var id = datarow[1];
            console.log(id);
            window.location='/admin/irrigationstatus/'+id;
        });
    });

    function setSearch(text){
        $('html, body').animate({
            scrollTop: $("#table-section").offset().top
        }, 1000);
        var search = table.search();
        search += ' ' + text;
        table.search(search).draw();
    }

    // updateStatus();
    function updateStatus() {
        $.ajax({
            url: '/admin/irrigationstatus/update',
            dataType: 'json',      
            data: {
                "_token": token,
            }, 
            success: function( data ) {
                $('#notused').html(data.notused);
                $('#idle').html(data.idle);
                $('#idle_green').html(data.idle_green);
                $('#settling').html(data.settling);
                $('#irrigation').html(data.irrigation);
                setTimeout(function(){updateStatus();}, 50000);
            },
            error: function( data ) {
                updateStatus();
            }
        });
    }
</script>

@endsection