{{-- {{ dd($sensorunits['sharedgroups']) }} --}}
<h1>@lang('general.sharedfrom')</h1>
<br>
@foreach ($sensorunits['sharedgroups'] as $customer)
    <div class="card bg-light mb-3">
        <div class="card-header">
            {{-- Collapse button --}}
            <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse"
                onclick="caretRotation(this)" id="caret{{$customer['customernumber']}}"
                data-target="#collapse{{$customer['customernumber']}}"
                aria-hidden="true">
            </i>
            
            @if(isset($customer['customer_site_title']))
                <h3>{{ $customer['customer_site_title'] }}</h3>
            @endif
        </div>

        <div class="card-body collapse collapse-local" id="collapse{{$customer['customernumber']}}">
            @foreach ($customer as $group)
                @if(is_array($group))
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            {{-- {{ dd($group) }} --}}
                            {{-- Collapse button --}}
                            <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse"
                                onclick="caretRotation(this)" id="caret{{$group['customernumber']}}{{$group['viewgroup_id']}}"
                                data-target="#collapse{{$group['customernumber']}}{{$group['viewgroup_id']}}"
                                aria-hidden="true">
                            </i>
                            <h3><i class="fas fa-layer-group"></i> {{ $group['viewgroup_name'] }}</h3>
                        </div>
                        <div class="card-body collapse show collapse-local" id="collapse{{$group['customernumber']}}{{$group['viewgroup_id']}}">
                            <div class="card bg-light">
                                {{-- sort order of sensor by name --}}
                            <?php
                                usort($group, function($a,$b) {
                                    if (is_array($a) && is_array($b)) {
                                        return strnatcmp($a['serialnumber'], $b['serialnumber']);
                                    }

                                    return 0;
                                });
                            ?>
                            @foreach ($group as $sensor)
                                @if (is_array($sensor))
                                    <div class="card-header">
                                        @if ($sensor['sensorunit_location'])
                                            <strong>{{ $sensor['sensorunit_location'] }}</strong>
                                        @else
                                            <strong>{{ $sensor['serialnumber'] }}</strong>   
                                        @endif
                                        {{-- Collapse button --}}
                                        <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse"
                                            onclick="caretRotation(this)" id="caret{{$sensor['serialnumber']}}"
                                            data-target="#collapse{{$sensor['serialnumber']}}"
                                            aria-hidden="true">
                                        </i>
                                        @isset($sensor['probe'])
                                            @foreach ($sensor['probe'] as $probe)
                                                @isset ($probe['timestampComment'])
                                                    <p>{{ $probe['timestampComment'] }}</p>
                                                @endisset
                                                @isset ($probe['timestampDifference'])
                                                    @if ($probe['timestampDifference'] < 5400)
                                                        <span class="dot" style="background-color: #159864;"></span>
                                                        @break
                                                    @endif
                                                    @if ($probe['timestampDifference'] > 5400)
                                                        @if ($probe['timestampDifference'] < 10800)
                                                            <span class="dot" style="background-color: #DFC04F;"></span>
                                                            @break
                                                        @else
                                                            <span class="dot" style="background-color: #D10D0D;"></span>
                                                            @break
                                                        @endif
                                                    @endif
                                                @endisset
                                            @endforeach
                                        @endisset
                                        
                                        @isset($sensor['probe'])
                                            <table align="center" style="position: static; text-align:center; width:60%;">
                                                <tr>
                                                    @foreach ($sensor['probe'] as $probe)
                                                        @isset($probe['header'])
                                                            @isset($probe['unittype_icon'])
                                                                @if ($probe['timestampDifference'] < 10800)
                                                                    <th>
                                                                        <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="40" height="40" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" style="margin-top: -20px;">
                                                                    </th>
                                                                @endif
                                                            @endisset
                                                        @endisset
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach ($sensor['probe'] as $probe)
                                                        @isset($probe['header'])
                                                            @if ($probe['timestampDifference'] < 10800)
                                                                <td>{{ $probe['header'] }}</td>
                                                            @endif
                                                        @endisset
                                                    @endforeach
                                                </tr>
                                            </table>
                                        @endisset
                                    <br>
                                    <div class="collapse" id="collapse{{$sensor['serialnumber']}}">
                                        <div class="card-body bg-light">
                                            <br>
                                            <table align="center" style="position: static; text-align:center; width:100%;">
                                                <tr>
                                                    @foreach ($sensor['probe'] as $probe)
                                                        @isset($probe['body'])
                                                            <th> <p>{{ $probe['unittype_label'] }}</p></th>
                                                        @endisset
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach ($sensor['probe'] as $probe)
                                                        @isset($probe['body'])
                                                            @isset($probe['unittype_icon'])
                                                                <th>
                                                                    <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="40" height="40" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" style="margin-top: -20px;">
                                                                </th>
                                                            @endisset
                                                        @endisset
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach ($sensor['probe'] as $probe)
                                                        @isset($probe['body'])
                                                            <td>{{ $probe['body'] }}</td>
                                                        @endisset
                                                    @endforeach
                                                </tr>
                                            </table>
                                            <br>
                                            <p><a class="btn btn-primary" href='/unit/{{$sensor['serialnumber']}}'>Detailed Graph</a></p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endforeach