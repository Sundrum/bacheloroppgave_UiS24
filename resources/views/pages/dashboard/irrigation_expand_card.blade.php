<div class="collapse card-rounded mb-3" id="collapse{{trim($irrUnit['serialnumber'])}}">
    <hr class="my-0 mx-5">
    <div class="px-3">
        <div class="row mt-3">

            @if (isset($irrUnit['latest']['tilt_relative']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="40" height="40" title="Tilt / Angle" rel="tooltip" style="transform: rotate({{ $irrUnit['latest']['tilt_relative'] ?? '' }}deg);">
                    <div class="row">
                        <div class="col">
                            <span>{{ round($irrUnit['latest']['tilt_relative']) ?? '0' }} &deg;</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Tilt / Angle</strong></span>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($irrUnit['eta']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="40" height="40" title="Estimated Time of Arrival" rel="tooltip" alt="">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['eta'] ?? 'NaN'}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>ETA</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['irrigation_meters']) && trim($irrUnit['irrigation_meters']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="40" height="40" title="Meters from target" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>@if(Auth::user()->measurement == 2) {{ round($irrUnit['irrigation_meters']*3.28084,0) ?? 'NaN'}} ft @else {{ $irrUnit['irrigation_meters'] ?? 'NaN'}} m @endif</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Distance to travel</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['total_meters']) && trim($irrUnit['total_meters']))
            <div class="col text-center">
                <img src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="40" height="40" title="Meters from target" rel="tooltip">
                <div class="row">
                    <div class="col">
                        <span>@if(Auth::user()->measurement == 2) {{ round($irrUnit['total_meters']*3.28084,0) ?? 'NaN'}} ft @else {{ round($irrUnit['total_meters'],0) ?? 'NaN'}} m @endif</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span><strong>Total distance</strong></span>
                    </div>
                </div>
            </div>
        @endif

            @if (isset($irrUnit['latest']['flowrate']) && $irrUnit['latest']['flowrate'] !== '0')
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/moisture.png" width="40" height="40" title="Flowrate" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['latest']['flowrate'] ?? 'NaN'}}m<sup>3</sup>/h</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Flowrate</strong></span>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($irrUnit['latest']['pressure']) && $irrUnit['latest']['pressure'] !== '0')
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Pressure" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>@if(Auth::user()->measurement == 2) {{ round($irrUnit['latest']['pressure']*14.503773773,0) ?? 'NaN'}} PSI @else {{ round($irrUnit['latest']['pressure'],1) ?? 'NaN'}} Bar @endif</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Pressure</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['latest']['vibration']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['latest']['vibration'] ?? 'NaN'}}%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Vibration</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['latest']['flow_velocity']) && $irrUnit['latest']['flow_velocity'] !== '0')
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Flow Velocity" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['latest']['flow_velocity'] ?? 'NaN'}}m/s</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Flow Velocity</strong></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row mt-3 pb-3">
            <div class="col-6 text-center">
                <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'><button class="btn-7s">@lang('dashboard.openmap')</button></a>
            </div>
            
            @if(isset($irrUnit['latest']['state']) &&  $irrUnit['timestampDifference'] < 5400 && ($irrUnit['latest']['state'] == '4' || $irrUnit['latest']['state'] == '5' || $irrUnit['latest']['state'] == '6'))
                <div class="col-6 text-center my-auto">
                    @if(isset($irrUnit['variable']['irrigation_portalstart']) && $irrUnit['variable']['irrigation_portalstart'] == '1')
                        <button  onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn-7r" value="stop" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">Stop</button>
                    @else
                        <button  onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn-7g" value="start" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">Start</button>
                    @endif
                </div>
            @endif

            @if (Auth::user()->roletype_id_ref > 80)
            <div class="col-6 text-center">
               <a href='/admin/irrigationstatus/{{$irrUnit['serialnumber']}}'><button class="btn-7r my-auto p-auto">ADMIN</button></a>
            </div>
            @endif
            <div class="col text-center">
                @if(isset($irrUnit['water_lost']) && $irrUnit['water_lost']) <span class="text-r"><strong>Waterlost</strong></span> @endif
            </div>
            <div class="col text-center">
                @if(isset($irrUnit['tilt_alert']) && $irrUnit['tilt_alert']) <span class="text-r"><strong>Tilt alarm</strong></span> @endif
            </div>
        </div>
    </div>
</div>