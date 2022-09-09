@foreach ($sensorunits['groups'] as $group)
    <div class="card bg-light mb-3 numberOfGroups">
        <div class="card-header">
            <table align="left" style="position: static; text-align:left; width:70%;">
                <tr>
                    <td><h3><i class="fas fa-layer-group"></i> {{ $group['viewgroup_name'] }}</h3></td>
                </tr>
            </table>
            @if(!$group['viewgroup_id'] == 0)
            <table align="right" style="position: static; text-align:center; width:30%;">
                <tr>
                    <td><button class="btn btn-danger deleteGroup float-right" style="display: none;" onclick="deleteGroup('{{$group['viewgroup_id']}}')">@lang('dashboard.deletegroup')</button></td>
                </tr>
            </table>
            @endif
        </div>
        @php
            $groups[] = $group['viewgroup_id'];
        @endphp
        <div class="card-body">
            <h4>@lang('dashboard.description') {{ $group['viewgroup_description'] }}</h4>
            <ul class="card bg-light" id="{{ $group['viewgroup_id'] }}" style="list-style-type: none; padding-left: 0;">
            @foreach ($group as $sensor)
                @if (is_array($sensor))
                    <li class="card-header" data-id="{{ $sensor['serialnumber'] }}">
                        @if ($sensor['sensorunit_location'])
                            <span><strong>{{ $sensor['sensorunit_location'] }}</strong></span>
                        @else
                            <span><strong>{{ $sensor['serialnumber'] }}</strong></span>
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
                                                    <td>
                                                        <img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="40" height="40" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt="" style="margin-top: -20px;">
                                                    </td>
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
                            <p><a class="btn btn-primary" href='/unit/{{$sensor['serialnumber']}}'>@lang('dashboard.detailedgraph')</a></p>
                        </div>
                    </div>
                </li>
                @endif
            @endforeach
            </ul>
        </div>
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