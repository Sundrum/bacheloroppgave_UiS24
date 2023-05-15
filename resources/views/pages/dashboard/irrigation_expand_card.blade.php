<div class="collapse card-rounded mb-3" id="collapse{{trim($irrUnit['serialnumber'])}}">
    <hr class="my-0 mx-5">
    <div class="px-3">
        <div class="row mt-3">

            @if (isset($irrUnit['tilt']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="40" height="40" title="Tilt / Angle" rel="tooltip" style="transform: rotate({{ $irrUnit['tilt'] }}deg);">
                    <div class="row">
                        <div class="col">
                            <span>{{ round($irrUnit['tilt']) }} &deg;</span>
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
                            <span>{{ $irrUnit['irrigation_meters'] ?? 'NaN'}}m</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Meters left</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['flowrate']) && $irrUnit['flowrate'] !== '0')
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/moisture.png" width="40" height="40" title="Flowrate" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['flowrate'] ?? 'NaN'}}m<sup>3</sup>/h</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Flowrate</strong></span>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($irrUnit['pressure']) && $irrUnit['pressure'] > '-1')
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="40" height="40" title="Pressure" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['pressure'] ?? 'NaN'}}Bar</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Pressure</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['vibration']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/vibration.png" width="40" height="40" title="Vibration" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['vibration'] ?? 'NaN'}}%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span><strong>Vibration</strong></span>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($irrUnit['flow_velocity']))
                <div class="col text-center">
                    <img src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="40" height="40" title="Flow Velocity" rel="tooltip">
                    <div class="row">
                        <div class="col">
                            <span>{{ $irrUnit['flow_velocity'] ?? 'NaN'}}m/s</span>
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
        <div class="row mt-3">
            @if(isset($irrUnit['irrigation_state']) && ($irrUnit['irrigation_state'] === '5'))
                <div class="col text-center">
                    <label class="switch">
                        <input type="checkbox" @if(isset($irrUnit['irrigation_portalstop']) && $irrUnit['irrigation_portalstop'] !== '1') checked @endif onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                        <span class="slider round"></span>
                    </label>
                    <div class="row">
                        <span>OFF/ON</span>
                    </div>
                </div>
            @endif
            <div class="col text-center">
                <a href='/include/view_irrigation.php?unit={{$irrUnit['serialnumber']}}'><button class="btn-7s float-right">@lang('dashboard.openmap')</button></a>
            </div>
            <div class="col text-center">
                @if(isset($irrUnit['water_lost']) && $irrUnit['water_lost']) <span class="text-r"><strong>Waterlost</strong></span> @endif
            </div>
            <div class="col text-center">
                @if(isset($irrUnit['tilt_alert']) && $irrUnit['tilt_alert']) <span class="text-r"><strong>Tilt alarm</strong></span> @endif
            </div>
        </div>
    </div>
</div>