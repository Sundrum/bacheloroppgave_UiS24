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
                            <h5>{{ $irrUnit['sensorunit_location'] ?? $irrUnit['serialnumber'] ?? '' }}</h5>
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
                                <img width="60" height="60" src="{{$irrUnit['img'] ?? '/img/irrigation/state.png'}}" class="float-left">
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
                        @if(isset($irrUnit['latest']['state']) &&  $irrUnit['timestampDifference'] < 5400 && ($irrUnit['latest']['state'] < '4' || $irrUnit['latest']['state'] == '7'))
                            <div class="col text-center">
                                <label class="switch">
                                    <input type="checkbox" @if(isset($irrUnit['variable']['irrigation_portalstart']) && $irrUnit['variable']['irrigation_portalstart'] == '1') checked @endif onclick="startIrrigation('{{trim($irrUnit['serialnumber'])}}')" class="btn btn-primary" id="startIrrigationButton{{trim($irrUnit['serialnumber'])}}">
                                    <span class="slider round"></span>
                                </label>
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
        // If slider is 'checked'
        if (checkBox.checked) {
            console.log('Button checked');
            $.ajax({
                url: "/startirrigation",
                type: 'POST',
                data: { 
                    "serial": serial,
                    "variable": 1,
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
                $.ajax({
                    url: "/startirrigation",
                    type: 'POST',
                    data: { 
                        "serial": serial,
                        "variable": 0,
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