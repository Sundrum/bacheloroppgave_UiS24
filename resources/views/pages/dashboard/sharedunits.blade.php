<h3>@lang('general.sharedfrom')</h3>
@foreach ($sensorunits['sharedgroups'] as $customer)
    <div class="card-rounded bg-light mb-2">
        <div class="row px-3 justify-content-center"  id="caret{{$customer['customernumber']}}" data-target="#collapse{{$customer['customernumber']}}" data-toggle="collapse" onclick="rotateImg('customer_{{$customer['customernumber']}}')" aria-hidden="true">
            <div class="col">
                @if(isset($customer['customer_site_title']))
                    <h3 class="mt-2">{{ $customer['customer_site_title'] }}</h3>
                @endif
            </div>
            <div class="col text-end mt-2">
                <img id="customer_{{$customer['customernumber']}}" src="{{ asset('img/expand.svg') }}" style="transform: rotate(-90deg);">
            </div>
        </div>
        <div class="collapse collapse-local" id="collapse{{$customer['customernumber']}}">
            @foreach ($customer as $group)
                @if(is_array($group))
                    <div class="card card-rounded bg-white pt-2 pb-3 px-2">
                        <div class="row">
                            <div class="col-8">
                                <h3><i class="fas fa-layer-group"></i> {{ $group['viewgroup_name'] }}</h3>
                            </div>
                            <div class="col-4 text-end">
                            {{-- Collapse button --}}
                            <div class="px-3">
                                <img id="caret{{$group['customernumber']}}{{$group['viewgroup_id']}}" data-toggle="collapse" onclick="rotateImg('caret{{$group['customernumber']}}{{$group['viewgroup_id']}}')" id="caret{{$group['customernumber']}}{{$group['viewgroup_id']}}" data-target="#collapse{{$group['customernumber']}}{{$group['viewgroup_id']}}" aria-hidden="true" src="{{ asset('img/expand.svg') }}">
                            </div>


                                {{-- <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse"
                                    onclick="caretRotation(this)" id="caret{{$group['customernumber']}}{{$group['viewgroup_id']}}"
                                    data-target="#collapse{{$group['customernumber']}}{{$group['viewgroup_id']}}"
                                    aria-hidden="true">
                                </i> --}}
                            </div>

                        </div>
                        <div class="collapse show collapse-local" id="collapse{{$group['customernumber']}}{{$group['viewgroup_id']}}">
                            <div>

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
                                    <hr class="my-0 mx-5">
                                    <div class="bg-white row mx-1" data-toggle="collapse" data-target="#collapse{{$sensor['serialnumber']}}">
                                        <div class="col-5">
                                            <div class="row">
                                                <span class="circle-@if($sensor['timestampDifference'] < 10800){{1}}@else{{0}}@endif"></span>
                                                <div class="col">
                                                    @if ($sensor['sensorunit_location'])
                                                        <h5 class="mb-0">{{ $sensor['sensorunit_location'] }}</h5>
                                                    @else
                                                        <h5 class="mb-0">{{ $sensor['serialnumber'] }}</h5>   
                                                    @endif
                                                </div>
                    
                                            </div>
                    
                                        </div>
                                        <div class="col-7 text-end">
                                            @foreach ($sensor['probe'] as $probe)
                                                @isset($probe['header'])
                                                    @if($probe['value'] == '0' && $probe['unittype_id'] == 47)
                                                        @continue
                                                    @else
                                                        @isset($probe['unittype_icon'])
                                                            <span class="px-1">
                                                                <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="20" height="20" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" >
                                                                <span class="sensor-icon-front px-1">
                                                                    {{ $probe['header'] }}
                                                                </span>
                                                            </span>
                                                        @endisset
                                                    @endif
                                                @endisset
                                            @endforeach
                                        </div>
                                        <div class="collapse" id="collapse{{$sensor['serialnumber']}}">
                                            <div class="row">
                                                <div class="col-6">
                                                    <span class="sensor-subtitle">{{ $sensor['serialnumber'] }}</span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="sensor-subtitle">{{ $sensor['timestampComment'] ?? ''}}</span>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                @foreach ($sensor['probe'] as $probe)
                                                    @isset($probe['body'])
                                                    @if($probe['value'] == 0 && $probe['unittype_id'] == 47)
                                                        @continue
                                                    @else
                                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">
                                                            <div class="row justify-content-center">
                                                                <div class="col">
                                                                    <span>{{ $probe['unittype_label'] }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-center">
                                                                <div class="col">
                                                                    <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="30" height="30" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" >
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-center">
                                                                <div class="col">
                                                                    <span>{{ $probe['body'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif                                        
                                                    @endisset
                                                @endforeach
                                                <div class="col-12 text-center">
                                                    <a href='/unit/{{$sensor['serialnumber']}}'><button class="btn-7s">@lang('dashboard.detailedgraph')</button></a>
                                                </div>
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