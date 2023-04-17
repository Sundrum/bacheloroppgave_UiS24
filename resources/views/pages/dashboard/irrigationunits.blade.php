@if (count($irrigationunits))
<div class="row">
    @foreach ($irrigationunits as $irrUnit)
        <div class="col-md-6">
            <div class="col-12 bg-white card-rounded mb-2">
                <div class="p-3">
                    <a  class="collapse-toggle" data-toggle="collapse" data-target="#collapse{{trim($irrUnit['serialnumber'])}}" href="#collapse{{trim($irrUnit['serialnumber'])}}" aria-hidden="true"></a>
                    {{-- Checks if sensorname exists --}}
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <h5>{{ $irrUnit['sensorname'] ?? $irrUnit['serialnumber'] ?? '' }}</h5>
                        </div>
                        <div class="col-12 col-md-4">
                            <span class="float-end">
                                {{ $irrUnit['timestampComment'] ?? '' }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img width="60" height="60" src="{{$irrUnit['img'] ?? '../img/irrigation/state.png'}}" class="float-left">
                            </a>
                        </div>
                        @if (isset($irrUnit['eta']))
                            <div class="col text-center">
                                <img src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="">
                                <div class="row">
                                    <div class="col">
                                        <span><strong>{{ $irrUnit['eta'] ?? 'NaN'}}</strong></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($irrUnit['speed']))
                            <div class="col text-center">
                                <img src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Speed" rel="tooltip" alt="">
                                <div class="row">
                                    <div class="col">
                                        <span><strong>{{ $irrUnit['speed'] ?? 'NaN'}}m/h</strong></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <i class="fa fa-3x fa-caret-down fa-fw float-end" data-toggle="collapse"
                            onclick="caretRotation(this)" id="caret{{trim($irrUnit['serialnumber'])}}"
                            data-target="#collapse{{trim($irrUnit['serialnumber'])}}"
                            style="color: #00265A;"
                            aria-hidden="true">
                        </i>
                        </div>
                    </div>

                    
                    {{-- Irrigation Slider --}}
                    @if (isset($irrUnit['irrigation_state']))
                        @if ($irrUnit['timestampDifference'] < 5400)
                            @if ($irrUnit['irrigation_state'] === '1')
                                @if(isset($irrUnit['irrigation_portalstart']) && $irrUnit['irrigation_portalstart'] == '1')
                                    <table align="center" style="position: static; text-align:center; width:60%;">
                                        <tr> 
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" checked onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <table align="center" style="position: static; text-align:center; width:60%;">
                                        <tr> 
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            @endif
                            @if ($irrUnit['irrigation_state'] === '5' || $irrUnit['irrigation_state'] === '6')
                                @if(isset($irrUnit['irrigation_portalstop']) && $irrUnit['irrigation_portalstop'] == '1')
                                    <table align="center" style="position: static; text-align:center; width:60%;">
                                        <tr> 
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                        <table align="center" style="position: absolute; top:60px; text-align:center; width:60%;">
                                            <tr>
                                                @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-0.1')
                                                <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Pressure" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                                @endif
                                            </tr>
                                            <tr>
                                                @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-0.1')
                                                    <th><p><strong>{{ round($irrUnit['pressure'],1) }} </strong>Bar</p></th>
                                                @endif
                                            </tr>
                                        </table>
                                @endif
                            @endif
                            @if ($irrUnit['irrigation_state'] === '4')
                                @if(isset($irrUnit['irrigation_portalstop']) && $irrUnit['irrigation_portalstop'] == '1')
                                    <table align="center" style="position: static; text-align:center; width:60%;">
                                        <tr> 
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <table align="center" style="position: static; text-align:center; width:60%;">
                                        <tr> 
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" checked onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            @endif
                        @else
                            @if(isset($irrUnit['irrigation_portalstart']) && $irrUnit['irrigation_portalstart'] == '1')
                                <table align="center" style="position: static; text-align:center; width:60%;">
                                    <tr> 
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox" checked onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <table align="center" style="position: static; text-align:center; width:60%;">
                                    <tr> 
                                        <td>
                                            <label class="switch">
                                            <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        @endif
                    @else
                        @if(isset($irrUnit['irrigation_portalstart']) && $irrUnit['irrigation_portalstart'] == '1')
                            <table align="center" style="position: static; text-align:center; width:60%;">
                                <tr> 
                                    <td>
                                        <label class="switch">
                                        <input type="checkbox" checked onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <table align="center" style="position: static; text-align:center; width:60%;">
                                <tr> 
                                    <td>
                                        <label class="switch">
                                        <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    @endif
                    @if(isset($irrUnit['percent_done']))
                        <div class="row mt-2">
                            <div class="col-12 mb-1">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>{{$irrUnit['starttime'] ?? 'NaN'}}</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="float-end">{{$irrUnit['eta'] ?? 'NaN'}}</h6>
                                    </div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-line" style="width: {{$irrUnit['percent_done'] ?? '100'}}%;"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- dashboard unit --}}
                @include('pages.dashboard.irrigation_expand_card')
            </div> 

        </div>
    @endforeach

</div>
@endif

<script>
    function startIrrigation(serial) {
        var checkBox = document.getElementById("startIrrigationButton" + serial);
        var token = "{{ csrf_token() }}";
        // If slider is 'checked'
        if (checkBox.checked) {
            console.log('Button checked');
            var variable = '1';
            $.ajax({
                url: "/startirrigation",
                type: 'POST',
                data: { 
                    "serial": serial,
                    "variable": variable,
                    "_token": token,
                },
                success: function(msg) {
                    alert('Remote start of Irrigation sensor');
                },   
                error:function(msg) {
                    alert("Failed - Please try again")
                }
            });
        } else {
            var confirmed = confirm('Do you want to stop the unit?');
            if(confirmed) {
                var variable = '0';
                $.ajax({
                    url: "/startirrigation",
                    type: 'POST',
                    data: { 
                        "serial": serial,
                        "variable": variable,
                        "_token": token,
                    },
                    success: function(msg) {
                        console.log(msg);
                    },   
                    error:function(msg) {
                        alert("Failed - Please try again")
                    }
                });
            } else {
                checkBox.checked = true;
            }
        }
    }
</script>