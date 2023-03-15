@foreach ($sensorunits['groups'] as $group)
    <div class="card-rounded bg-white mb-2 numberOfGroups">
        <div class="row">
            <div class="col-12 col-md-8">
                <h3 class="pt-3 px-3"><i class="fas fa-layer-group"></i> {{ $group['viewgroup_name'] }}</h3>
            </div>
            <div class="col-12 col-md-4">
                @if(!$group['viewgroup_id'] == 0)
                    <button class="btn-7r deleteGroup float-right" style="display: none;" onclick="deleteGroup('{{$group['viewgroup_id']}}')">@lang('dashboard.deletegroup')</button>
                @endif
            </div>
        </div>
        @php
            $groups[] = $group['viewgroup_id'];
        @endphp
        @if($group['viewgroup_description'])
            <h5>@lang('dashboard.description') {{ $group['viewgroup_description'] }}</h5>
        @endif
        <ul class="bg-white px-2" id="{{ $group['viewgroup_id'] }}" style="list-style-type: none;">
        <?php ksort($group) ?>
        {{-- @dd($group) --}}
        @foreach ($group as $sensor)
            @if (is_array($sensor))
                <li class="card-header" data-id="{{ $sensor['serialnumber'] }}">
                    <div class="row">
                        <div class="col-7">
                    
                            @if ($sensor['sensorunit_location'])
                                <strong>{{ $sensor['sensorunit_location'] }}</strong>
                            @else
                                <strong>{{ $sensor['serialnumber'] }}</strong>   
                            @endif
                        </div>
                        <div class="col-5 text-right">
                            {{ $sensor['timestampComment'] }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 mt-2">
                            @if ($sensor['timestampDifference'] < 10800)
                                <a href="/unit/{{$sensor['serialnumber']}}"><span class="dot" style="background-color: #159864;"></span></a>
                            @else
                                <a href="/unit/{{$sensor['serialnumber']}}"><span class="dot" style="background-color: #D10D0D;"></span></a>
                            @endif  
                        </div>
                        <div class="col">
                            <div class="row">
                            @foreach ($sensor['probe'] as $probe)
                                @isset($probe['header'])
                                    @if($probe['value'] == '0' && $probe['unittype_id'] == 47)
                                        @continue
                                    @else
                                        @isset($probe['unittype_icon'])
                                            <div class="col mt-4">
                                                <div class="row">
                                                    <img class="img-responsive" src="{{ $probe['unittype_icon'] }}" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" style="width: 60px; margin-top: -20px;">    
                                                </div>
                                                <div class="row text-center">
                                                    {{ $probe['header'] }}
                                                </div>
                                            </div>
                                        @endisset
                                    @endif
                                @endisset
                            @endforeach
                            </div>
                        </div>
                        <div class="col-3">
                            @if ($sensor['timestampDifference'] < 10800)
                                <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse" onclick="caretRotation(this)" id="caret{{$sensor['serialnumber']}}" data-target="#collapse{{$sensor['serialnumber']}}" style="color: #159864;" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-3x fa-caret-down fa-fw float-right" data-toggle="collapse" onclick="caretRotation(this)" id="caret{{$sensor['serialnumber']}}" data-target="#collapse{{$sensor['serialnumber']}}" style="color: #D10D0D;" aria-hidden="true"></i>
                            @endif  
                        </div>
                    </div>
                    <div class="collapse" id="collapse{{$sensor['serialnumber']}}">
                        <hr>
                        <div class="row justify-content-center">
                                @foreach ($sensor['probe'] as $probe)
                                {{-- @dd($sensor) --}}
                                    @isset($probe['body'])
                                    @if($probe['value'] == 0 && $probe['unittype_id'] == 47)
                                        @continue
                                    @else
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mt-2">
                                            <div class="row justify-content-center">
                                                <p>{{ $probe['unittype_label'] }}</p>
                                            </div>
                                            <div class="row justify-content-center">
                                                <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="40" height="40" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" style="margin-top: -20px;">
                                            </div>
                                            <div class="row justify-content-center">
                                                <p>{{ $probe['body'] }}</p>
                                            </div>
                                        </div>
                                    @endif                                        
                                    @endisset
                                @endforeach
                                <div class="col-12">
                                    <p><a class="btn btn-primary" href='/unit/{{$sensor['serialnumber']}}'>@lang('dashboard.detailedgraph')</a></p>
                                </div>
                        </div>
                    </div>
                </li>
                <hr>
            @endif
        @endforeach
        {{-- @dd($group) --}}
        </ul>
    </div>
@endforeach

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