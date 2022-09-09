@extends('layouts.admin')

<link rel="stylesheet" type="text/css" href="{{ url('/css/slider.css') }}">

@section('content')
<div class="container">
    <h1>Irrigation Overview</h1>
    <div class="card card-rounded mb-2">
        <div class="col-12 mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-12 col-md-3">
                    <div class="col-12">
                        <div class="row btn-primary-outline justify-content-center" onclick="window.location.href='/admin/map/irrigationstatus'">
                            <strong><i class="fa fa-location-arrow" aria-hidden="true"></i> Aktive sensorenheter</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="col-12">
                        <div class="row btn-primary-rounded justify-content-center" onclick="setSearch('21-1020-AA-');">
                            <strong>21-1020-AA</strong>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="col-12">
                        <div class="row btn-primary-rounded justify-content-center" onclick="setSearch('21-1020-AB-');">
                            <strong>21-1020-AB</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-2">
                    <div class="row justify-content-center">
                        <img src="{{asset('/img/irr_irrigation_green.png')}}" onclick="setSearch('state5');">
                    </div>
                    <div class="row justify-content-center" id="irrigation">
                        {{$variable['irrigation']}}
                    </div>
                </div>
                <div class="col-2">
                    <div class="row justify-content-center">
                        <img src="{{asset('/img/irr_settling_green.png')}}" onclick="setSearch('state4');">
                    </div>
                    <div class="row justify-content-center" id="settling">
                        {{$variable['settling']}}
                    </div>
                </div>
                <div class="col-2">
                    <div class="row justify-content-center">
                        <img src="{{asset('/img/irr_idle_green.png')}}" onclick="setSearch('state3');">
                    </div>
                    <div class="row justify-content-center" id="idle_green">
                        {{$variable['idle_green']}}
                    </div>
                </div>
                <div class="col-2">
                    <div class="row justify-content-center">
                        <img src="{{asset('/img/irr_idle_yellow.png')}}" onclick="setSearch('state2');">
                    </div>
                    <div class="row justify-content-center" id="idle">
                        {{$variable['idle']}}
                    </div>
                </div>
                <div class="col-2">
                    <div class="row justify-content-center">
                        <img src="{{asset('/img/irr_notused.png')}}" onclick="setSearch('state1');">
                    </div>
                    <div class="row justify-content-center" id="notused">
                        {{$variable['notused']}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="table-section">
        <table id="irrtable" class="display" width="100%"></table>
    </div>
</div>

<!-- Datatables script  -->
<script>
    var table;
    $(document).ready(function () {
        var dataSet = @php echo $data; @endphp;
        table = $('#irrtable').DataTable({
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
                { title: "Status" },
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

    {{-- <p>Hide Idle</p>
    <label class="switch">
        <input type="checkbox" name="hideidle" value="hideidle" checked onclick="checkbox_toggle(this);">
        <span class="slider round"></span>
    </label>
    
    <table align="center" style="position: static; text-align:center; width:100%;">
        <tr style="margin-bottom:10px;">
            @php
                $m = 1;
            @endphp
            @foreach ($allirrigation as $irrUnit)
            <td class="{{$irrUnit['class']}}" style="vertical-align:top; display:{{$irrUnit['display']}};">
                    <a href='/unit/{{$irrUnit['serialnumber']}}'>
                        <img src="{{ $irrUnit['img'] }}">
                    </a>
                    <p>{{ $irrUnit['serialnumber'] }}</p>
                    <p>{{ $irrUnit['swversion']->value ?? 'Ukjent' }}</p>
                    <p>{{ $irrUnit['timestampComment'] }}</p>
                </td>

                @if(!($m % $divider))
                    </tr><tr style="margin-bottom:10px;">
                @endif
                @php $m++; @endphp
            @endforeach
        </tr>
    <table> --}}


{{-- <script>
    function setDisplay(className, displayValue) {
      var items = document.getElementsByClassName(className);
      for (var i=0; i < items.length; i++) {
        items[i].style.display = displayValue;
        if (displayValue == 'none')
        {
            items[i].style.visibility='hidden';
        } else
        {
            items[i].style.visibility='visible';
        }
      }
    }
    
    function irrigation() {
      setDisplay("irrigation_units", "");
      setDisplay("all_units", "none");
    }
    
    
    function all() {
      setDisplay("irrigation_units", "");
      setDisplay("all_units", "");
    }
    
    function checkbox_toggle(cb) {
        if (cb.checked == false)
        {
            all();
        } else
        {
            irrigation();
        }
    }
</script> --}}