@if (count($irrigationunits))  
    @foreach ($irrigationunits as $irrUnit)
        <div class="card bg-light mb-1 mt-2">
            <div class="card-header">
                <a  class="collapse-toggle"
                    data-toggle="collapse"
                    data-target="#collapse{{trim($irrUnit['serialnumber'])}}"
                    href="#collapse{{trim($irrUnit['serialnumber'])}}"
                    aria-hidden="true"
                    >
                </a>
                {{-- Checks if sensorname exists --}}
                @if(trim($irrUnit['sensorname']) ?? '')
                    <h5>{{ $irrUnit['sensorname'] }}</h5>
                @else
                    <h5>{{ $irrUnit['serialnumber'] }}</h5>
                @endif
                @if (isset($irrUnit['irrigation_state']))
                    {{-- 90 min = 5400 sec --}}
                    @if ($irrUnit['timestampDifference'] < 5400)
                        {{-- Idle --}}
                        @if ($irrUnit['irrigation_state'] === '0')
                            <p>{{ $irrUnit['timestampComment'] }}</p>
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img src="../img/irr_idle_green.png" class="float-left">
                            </a>
                        @endif
                        {{-- Settling --}}
                        @if ($irrUnit['irrigation_state'] === '1')
                            <p>{{ $irrUnit['timestampComment'] }}</p>
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img src="../img/irr_settling_green.png" class="float-left">
                            </a>
                        @endif
                        {{-- Settling --}}
                        @if ($irrUnit['irrigation_state'] === '3')
                            <p>{{ $irrUnit['timestampComment'] }}</p>
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img src="../img/irr_settling_green.png" class="float-left">
                            </a>
                        @endif
                        {{-- Irrigating --}}
                        @if ($irrUnit['irrigation_state'] === '2')
                            <p>{{ $irrUnit['timestampComment'] }}</p>
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img src="../img/irr_irrigation_green.png" class="float-left">
                            </a>
                        @endif
                        {{-- Irrigating --}}
                        @if ($irrUnit['irrigation_state'] === '4')
                            <p>{{ $irrUnit['timestampComment'] }}</p>
                            <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                                <img src="../img/irr_irrigation_green.png" class="float-left">
                            </a>
                        @endif
                    {{-- If sensor has NOT reported within the last 90 minutes --}}
                    @else
                        <p>{{ $irrUnit['timestampComment'] }}</p>
                        <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'>
                            <img src="../img/irr_idle_yellow.png" class="float-left">
                        </a>
                    @endif
                @else
                    <p>{{ $irrUnit['timestampComment'] }}</p>    
                    <img src="../img/irr_idle_yellow.png" class="float-left">
                @endif
                {{-- Collapse button --}}
                <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse"
                    onclick="caretRotation(this)" id="caret{{trim($irrUnit['serialnumber'])}}"
                    data-target="#collapse{{trim($irrUnit['serialnumber'])}}"
                    style="color: #009A63;"
                    aria-hidden="true">
                </i>
                
                {{-- Irrigation Slider --}}
                @if (isset($irrUnit['irrigation_state']))
                    @if ($irrUnit['timestampDifference'] < 5400)
                        @if ($irrUnit['irrigation_state'] === '0')
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
                        @if ($irrUnit['irrigation_state'] === '2' || $irrUnit['irrigation_state'] === '4')
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
                                            @if (isset($irrUnit['eta']))
                                                <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                            @endif
                                            @if (isset($irrUnit['speed']))
                                                <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Speed" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                            @endif
                                            @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-0.1')
                                            <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Pressure" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if (isset($irrUnit['eta']))
                                                <th><p><strong>{{ $irrUnit['eta'] }}</strong></p></th>
                                            @endif
                                            @if (isset($irrUnit['speed']))
                                                <th><p><strong>{{ round($irrUnit['speed'],0) }} </strong>m/h</p></th>
                                            @endif
                                            @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-0.1')
                                                <th><p><strong>{{ round($irrUnit['pressure'],1) }} </strong>Bar</p></th>
                                            @endif
                                        </tr>
                                    </table>
                            @endif
                        @endif
                        @if ($irrUnit['irrigation_state'] === '1' || $irrUnit['irrigation_state'] === '3')
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
            </div> 
            {{-- dashboard unit --}}
            <div class="collapse bg-white" id="collapse{{trim($irrUnit['serialnumber'])}}">
                <div class="card-body">
                    @if(isset($irrUnit['irrigation_state']) && ($irrUnit['irrigation_state'] === '2' || $irrUnit['irrigation_state'] === '1' || $irrUnit['irrigation_state'] === '3' || $irrUnit['irrigation_state'] === '4'))
                    <table align="center" style="position: static; text-align:center; width:100%;">
                        <tr>
                            @if (isset($irrUnit['tilt']))
                                <th><p><strong>Tilt</strong></p></th>
                            @endif
                            @if (isset($irrUnit['eta']))
                                <th><p><strong>ETA</strong></p></th>
                            @endif
                            @if (isset($irrUnit['irrigation_meters']) && trim($irrUnit['irrigation_meters']))
                                <th><p><strong>Meters left</strong></p></th>
                            @endif
                            @if (isset($irrUnit['flowrate']) && $irrUnit['flowrate'] !== '0')
                                <th><p><strong>Flowrate</strong> </p></th>
                            @endif
                            @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-1')
                                <th><p><strong>Pressure</strong></p></th>
                            @endif
                            @if (isset($irrUnit['vibration']))
                                <th><p><strong>Vibration</strong></p></th>
                            @endif
                            @if (isset($irrUnit['flow_velocity']))
                                <th><p><strong>Flow Velocity</strong></p></th>
                        @endif
                        </tr>
                        <tr>
                                @if (isset($irrUnit['tilt']))
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="transform: rotate({{ $irrUnit['tilt'] }}deg);"></th>
                                @endif
                        
                                @if (isset($irrUnit['eta']))
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                @if (isset($irrUnit['irrigation_meters']) && trim($irrUnit['irrigation_meters']))
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="40" height="40" title="Meters from target" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                @if (isset($irrUnit['flowrate']) && $irrUnit['flowrate'] !== '0')
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/moisture.png" width="40" height="40" title="Flowrate" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-1')
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Pressure" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                @if (isset($irrUnit['vibration']))
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                @if (isset($irrUnit['flow_velocity']))
                                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Speed" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                                @endif
                                
                            </tr>
                            
                            <tr>
                                {{-- tilt value --}}
                                @if (isset($irrUnit['tilt']))
                                    <th><p><strong>{{ round($irrUnit['tilt']) }}</strong> &deg;</p></th>
                                @endif
                                @if (isset($irrUnit['eta']))
                                    <th><p><strong>{{ $irrUnit['eta'] }}</strong></p></th>
                                @endif
                                @if (isset($irrUnit['irrigation_meters']) && trim($irrUnit['irrigation_meters']))
                                    <th><p><strong>{{ round($irrUnit['irrigation_meters'],0) }}</strong> m</p></th>
                                @endif
                                @if (isset($irrUnit['flowrate']) && $irrUnit['flowrate'] !== '0')
                                    <th><p><strong>{{ round($irrUnit['flowrate'],0) }} </strong> m<sup>3</sup>/h</p></th>
                                @endif
                                @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-1')
                                    <th><p><strong>{{ round($irrUnit['pressure'],1) }} </strong> Bar</p></th>
                                @endif
                                @if (isset($irrUnit['vibration']))
                                    <th><p><strong>{{ $irrUnit['vibration'] }} </strong> %</p></th>
                                @endif
                                @if (isset($irrUnit['flow_velocity']))
                                    <th><p><strong>{{ $irrUnit['flow_velocity'] }} </strong> m/s</p></th>
                                @endif
                            </tr>
                        </table>
                        @endif
                        <table align="left" style="position: static; text-align:center; width:40%;">
                            <br>
                            <tr>
                                @if(isset($irrUnit['irrigation_state']) && ($irrUnit['irrigation_state'] === '2' || $irrUnit['irrigation_state'] === '4' ))
                                        @if(isset($irrUnit['irrigation_portalstop']) && $irrUnit['irrigation_portalstop'] == '1')
                                        <th>
                                            <label class="switch">
                                                <input type="checkbox" onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label></th>
                                        @else
                                            <th><label class="switch">
                                                <input type="checkbox" checked onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                                    <span class="slider round"></span>
                                                </label></th>
                                        @endif

                                @endif
                                <th><form action="/unit/{{trim($irrUnit['serialnumber'])}}"><input type="submit" class="btn btn-primary float-right" value="@lang('dashboard.openmap')"></form></th>
                                {{-- @if(Auth::user()->roletype_id_ref > 80)
                                    <th><form action="/run/{{trim($irrUnit['serialnumber'])}}"><input type="submit" class="btn btn-primary float-right" value="@lang('dashboard.oldruns')"></form></th>
                                @endif --}}
                            </tr>
                            <tr>
                                @if(isset($irrUnit['irrigation_state']) && ($irrUnit['irrigation_state'] === '2' || $irrUnit['irrigation_state'] === '4'))
                                    <th><p><strong>OFF / ON</strong></p></th>
                                @endif
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    @endforeach
@endif

@if (Session::get('customernumber') == "10-1233-AA" || trim(Auth::user()->customernumber) == "10-1233-AA") 
    <div class="card bg-light mb-1 mt-2">
        <div class="card-header">
            <a class="collapse-toggle" data-toggle="collapse" data-target="#collapse21-1020-AA-88888" href="#collapse21-1020-AA-88888" aria-hidden="true"></a>
            <h5>Demo unit</h5>
            @php
                $datetime = time();
                $demotime = date('H:i', $datetime);
            @endphp
            <p> Today at: {{ $demotime }}</p>
            <a href='/map'> <img src="../img/irr_irrigation_green.png" class="float-left"> </a>
            <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse" onclick="caretRotation(this)" id="caret21-1020-AA-88888" data-target="#collapse21-1020-AA-88888" style="color: #009A63;" aria-hidden="true"></i>
            <table align="center" style="position: static; text-align:center; width:60%;">
                <tr>
                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                    <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Speed" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                </tr>
                <tr>
                    <th><p><strong>19:38</strong></p></th>
                    <th><p><strong>12 </strong>m/h</p></th>
                </tr>
            </table>
        </div>
        <div class="collapse bg-white" id="collapse21-1020-AA-88888">
            <div class="card-body">
                <table align="center" style="position: static; text-align:center; width:100%;">
                    <tr>
                        <th><p><strong>Tilt</strong></p></th>
                        <th><p><strong>ETA</strong></p></th>
                        <th><p><strong>Meters left</strong></p></th>
                        <th><p><strong>Flowrate</strong> </p></th>
                        <th><p><strong>Vibration</strong></p></th>
                    </tr>
                    <tr>
                        <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="transform: rotate(1deg);"></th>
                        <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                        <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="40" height="40" title="Meters from target" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                        <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/moisture.png" width="40" height="40" title="Flowrate" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                        <th><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                    </tr>
                    <tr>
                        <th><p><strong>0 </strong> &deg;</p></th>
                        <th><p><strong>19:38 </strong></p></th>
                        <th><p><strong>210 </strong> m</p></th>
                        <th><p><strong>77 </strong> m<sup>3</sup>/h</p></th>
                        <th><p><strong>43 </strong> %</p></th>
                    </tr>
                    </table>
                    <table align="left" style="position: static; text-align:center; width:40%;">
                        <br>
                        <tr>
                            <th><label class="switch"><input type="checkbox" checked class="btn btn-primary"> <span class="slider round"></span></label></th>
                            <th><form action="/map"><input type="submit" class="btn btn-primary float-right" value="OPEN MAP"></form></th>
                        </tr>
                        <tr>
                            <th><p><strong>OFF / ON</strong></p></th>
                        </tr>
                    </table>
            </div>
        </div>
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