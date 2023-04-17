<div class="row px-2">
    @foreach ($sensorunits['groups'] as $group)
        <div class="col-12 col-lg-6">
            <div class="bg-white card-rounded mb-2 numberOfGroups">
                <div class="row">
                    <div class="col-9 col-md-10 col-lg-8">
                        <h3 class="pt-3 px-3"><i class="fas fa-layer-group"></i> {{ $group['viewgroup_name'] }}</h3>
                    </div>
                    <div class="col-3 col-md-2 col-lg-4 text-end pt-2 pt-xl-3">
                        <img class="px-3" id="caret{{$group['viewgroup_id']}}" data-toggle="collapse" onclick="rotateImg('caret{{$group['viewgroup_id']}}')" data-target="#collapse{{$group['viewgroup_id']}}" aria-hidden="true" src="{{ asset('img/expand.svg') }}" style="transform: rotate(-90deg);">
                    </div>
                    
                </div>
                @php
                    $groups[] = $group['viewgroup_id'];
                @endphp

                @if($group['viewgroup_description'])
                    <div class="px-3">
                        <h5>{{ $group['viewgroup_description'] }}</h5>
                    </div>
                @endif
                @if(!$group['viewgroup_id'] == 0)
                    <div class="px-3">
                        <button class=" btn-outline-7r deleteGroup" style="display: none;" onclick="deleteGroup('{{$group['viewgroup_id']}}')">@lang('dashboard.deletegroup')</button>
                    </div>
                @endif
                <div class="collapse show collapse-local" id="collapse{{$group['viewgroup_id']}}">
                    <ul class="px-2 py-2" id="{{ $group['viewgroup_id'] }}" style="list-style-type: none;">
                        <?php ksort($group) ?>
                        {{-- @dd($group) --}}
                        @foreach ($group as $sensor)
                            @if (is_array($sensor))
                                <hr class="my-0">
                                {{-- Sensor card --}}
                
                                <li class="bg-white row mx-1" data-id="{{ $sensor['serialnumber'] }}" data-toggle="collapse" data-target="#collapse{{$sensor['serialnumber']}}">
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
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>


<script type="text/javascript">
    function getNumberOfGroups() {
        var x = document.getElementsByClassName("numberOfGroups");
        console.log("x is: " + x.length);
        var numberOfGroups = x.length - 1;

        return numberOfGroups;
    }

    // Sortable
    // Used to enable/disable sorting
    // document.addEventListener('DOMContentLoaded', function() {
    //     var sortingState = false;
    //     sorting(sortingState);
    // }, false);
    function sorting(sortingState) {
        var groups = @php if(isset($groups)) echo json_encode($groups); @endphp;
        console.log(groups);
        groups.forEach(makeSortable); // (argument 1, index)
        function makeSortable(listorder, index) {
            var grouporder = document.getElementById(listorder);
            console.log(sortingState);
            Sortable.create(grouporder, {
                group: "lists",
                sort: sortingState,
                animation: 200,
                store: {
                    /**
                    * Get the order of elements. Called once during initialization.
                    * @param   {Sortable}  sortable
                    * @returns {Array}
                    */
                    get: function (sortable) {
                        var order = sortable.toArray();
                        console.log(order);
                    },

                    /**
                    * Save the order of elements. Called onEnd (when the item is dropped).
                    * @param {Sortable}  sortable
                    */
                    set: function (sortable) {
                        var token = "{{ csrf_token() }}";
                        var order = sortable.toArray();

                        // console.log("From group: " + fromList);
                        console.log("Order: " + order);
                        console.log("Listorder: " + listorder);
                        // console.log("To group: " + toList); // HER ER DEN, DIT SKAL ELEMENTET
                        $.ajax({
                            url: "/setorder",
                            type: 'POST',
                            data: { 
                                "order": order,
                                "orderlist": listorder,
                                "_token": token,
                            },
                            success: function(msg) {
                                console.log(msg);
                                console.log("Update list " + listorder);
                            },   
                            error:function(msg) {
                                alert("Problem");
                            }
                        });
                    }
                }
            });
        }
    }

    function deleteGroup(id) {
        console.log("Please delete: " + id);
        var customerNumber = document.getElementById("customerNumber").value;
        var token = "{{ csrf_token() }}";
        $.ajax({
            url: "/deletegroup",
            type: 'POST',
            data: { 
                "customerNumber": customerNumber,
                "groupId": id,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
                window.location = "/dashboard";
            },   
            error:function(msg) {
                console.log(msg);
            }
        });
    }
</script>