<h1>DETTE ER TESTVIEW-VERSJONEN AV DASHBOARD.BLADE.PHP</h1>
{{-- Session::get('sharedunits');
Session::get('customerunits'); --}}

@extends('layouts.app')

{{-- Sortable CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
{{-- CSS for irrigation slider --}}
<link rel="stylesheet" type="text/css" href="{{ url('/css/slider.css') }}">
{{-- CSS for sensorunit live dot (circle) --}}
<link rel="stylesheet" type="text/css" href="{{ url('/css/dot.css') }}">
<!-- FA icon library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@section('content')
    {{-- Adds the title "7Sense Products"
    @if (Session::get('customer_site_title'))
        <h1>{{ Session::get('customer_site_title') }}</h1>
        <input type="hidden" id="customerNumber" value="{{ Session::get('customernumber') }}">
    @endif
    @include('pages.dashboard.infobutton')
    <br><br>
    @include('pages.dashboard.irrigationunits')
    <br><br>
    @include('pages.dashboard.sensorunits')
    <br><br>
    
    <h1>Sortable lists</h1>
    <br>
    @include('pages.dashboard.sensorsettings') --}}
    <br>







    @foreach ($testunits as $group)
        <div class="card bg-light mb-3">
            <div class="card-header">
                <h3>Group: {{ $group['viewgroup_name'] }} 
                <button class="btn btn-danger deleteGroup float-right" style="display: none;" onclick="deleteGroup('{{ $group['viewgroup_id'] }}')">Delete Group</button>
                </h3>
                @isset($group['viewgroup_description']) 
                    <p>Description: {{ $group['viewgroup_description'] }}</p>
                @endisset
            </div>
            <div class="card-body">
                @php 
                    $groups[] = $group['viewgroup_id'];
                @endphp
                <ul class="card bg-light" id="{{ $group['viewgroup_id'] }}" style="list-style-type: none; padding-left: 0;">
                    @foreach ($group as $unit)
                        @if(is_array($unit))
                            <li class="card-header" data-id="{{ trim($unit['serialnumber']) }}" >{{ $unit['serialnumber'] }}, {{ $unit['viewgroup_order']}}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
    @include('pages.dashboard.addgroup')
    @include('pages.dashboard.changegroup')
    <br>
    <br>

    {{-- JavaScript --}}
    <script type="text/javascript">
    function getNumberOfGroups() {
        var numberOfGroups = '{!! count($groups) ?? '' }'
        console.log(numberOfGroups);

        return numberOfGroups;
    }

    function deleteGroup(id) {
        console.log("Please delete: " + id);
        var customerNumber = document.getElementById("customerNumber").value;
        var token = "{{ csrf_token() }}";
        $.ajax({
            url: "/test/deletegroup",
            type: 'POST',
            data: { 
                "customerNumber": customerNumber,
                "groupId": id,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
            },   
            error:function(msg) {
                console.log(msg);
                alert("Problem")
            }
        });

    }

    // Sortable
    // Used to enable/disable sorting
    document.addEventListener('DOMContentLoaded', function() {
        var sortingState = false;
        sorting(sortingState);
    }, false);
    function sorting(sortingState) {
        var groups = <?php echo json_encode($groups); ?>;
        groups.forEach(makeSortable); // (argument 1, index)
        function makeSortable(listorder, index) {
            var grouporder = document.getElementById(listorder);
            console.log(sortingState);
            Sortable.create(grouporder, {
                group: "lists",
                animation: 200,
                sort: sortingState,
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
                            url: "/test/setorder",
                            type: 'POST',
                            data: { 
                                "order": order,
                                "orderlist": listorder,
                                "_token": token,
                            },
                            success: function(msg) {
                                console.log(msg);
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
                    console.log(msg);
                },   
                error:function(msg) {
                    alert("Problem starting Irrigation sensor")
                }
            });
            alert('IRIGATION STARTED');
        } else {
            alert('IRRIGATION STOPPED');
            console.log('Button unchecked');
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
                    alert("Problem starting Irrigation sensor")
                }
            });
        }
    }

    function allowEditing() {
        var length = {{ count($sensorunits) }};
        for (i = 0; i < length; i++) {
            var x = document.getElementById("draggable" + i);
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    }

    function caretRotation(obj) {
        var id = obj.id;
        console.log(id);
        var element = document.getElementById(id);
        console.log(element.classList.contains("fa-caret-down")); 
        if (element.classList.contains("fa-caret-down")) {
            element.classList.remove("fa-caret-down");
            element.classList.add("fa-caret-left");

        } else {
            element.classList.remove("fa-caret-left");
            element.classList.add("fa-caret-down");
        }
    }

    </script>


<script>
  // --------------------------------------




    //     function createSortable(listName) {
    //         Sortable.create(listName, {
    //             group: 'sensors',
    //             animation: 150,
    //             sort: true,
    //             handle: '#draggable', // draggable button with class btn-drag
    //             onEnd: function(evt) {
    //                 saveToDB(evt);
    //             }
    //         });
    //     }

        

    //     function generateGroup() {
    //         // Get value from input field
    //         var groupName = document.getElementById("groupName").value;
    //         var groupExists = document.getElementById(groupName);
    //         // Give user alert if group name is empty or already exists
    //         if (!groupName) {
    //             alert("Group name cannot be empty!");
    //         } else if (groupExists){
    //             alert("Group already exists!");
    //         } else {
    //             // Create header
    //             var header = document.createElement("h2");
    //             var node = document.createTextNode(groupName);
    //             header.appendChild(node);
    //             header.id = "header";

    //             // Add to HTML 
    //             var element = document.getElementById("newGroup");
    //             element.appendChild(header);

    //             // Wrapping the element in a div that is to be targeted when sorting
    //             // Emulating "sensors"-list as above
    //             var wrapper = document.createElement('div');
    //             wrapper.id = groupName; 

    //             // Insert wrapper before element in the DOM tree
    //             element.parentNode.insertBefore(wrapper, element);
    //             wrapper.appendChild(element);

    //             // Create a delete button
    //             generateDeleteButton(groupName);

    //             // Make the group sortable
    //             var listName = document.getElementById(groupName);
    //             createSortable(wrapper);                   
    //         }
    //     }

    //     function generateDeleteButton(groupName) {
    //         var button = document.createElement("button");
    //         var buttonText = document.createTextNode("Delete group '" + groupName + "'");
    //         button.appendChild(buttonText);
    //         button.className = "btn btn-warning";
    //         button.id = groupName;
    //         // Calls the deleteItem function, notice the lack of (), this is because the
    //         // event groupener is created and is not supposed to be triggered. Bind is used
    //         // So that the button can be deleted regardless of group name
    //         button.addEventListener('click', deleteItem.bind(this, groupName));
    //         var element = document.getElementById("control");
    //         element.appendChild(button);
    //     }

    //     function deleteItem(groupName) {
    //         // This is static because the group header is given an id of
    //         // 'header' in generateGroup() function
    //         var group = document.getElementById("header");
    //         group.parentNode.removeChild(group);
    //         // TODO: this one needs to be dynamic
    //         var button = document.getElementById(groupName);
    //         button.parentNode.removeChild(button)
    //     }
    // 
    </script>
@endsection