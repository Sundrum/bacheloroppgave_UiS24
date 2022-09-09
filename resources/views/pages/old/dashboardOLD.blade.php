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
    {{-- Adds the title "7Sense Products" --}}
    @if (Session::get('customer_site_title'))
        <h1>{{ Session::get('customer_site_title') }}</h1>
    @endif
    @if(count($irrigationunits))
        <i class="fa fa-3x fa-info-circle fa-fw float-right" style="margin-right: 10px;" data-toggle="modal" data-target="#myInfowindow"></i>
        <br><br>
    @endif
    @include('pages.dashboard.irrigationunits')
    <br><br>
    @include('pages.dashboard.sensorunits')
    <br><br>

    <div class="modal fade" id="myInfowindow" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Irrigation modes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <table>
                            <tr class="spaceUnder">
                                <td> <img src="../img/irr_idle_green.png" class="float-left"> </td>
                                <td class="tdspace"> Sleep mode</td>
                            </tr>
                            <tr class="spaceUnder">
                                <td> <img src="../img/irr_settling_green.png" class="float-left"> </td>
                                <td class="tdspace"> Setteling mode </td>
                            </tr>
                            <tr class="spaceUnder">
                                <td> <img src="../img/irr_irrigation_green.png" class="float-left"> </td>
                                <td class="tdspace"> Irrigation mode</td>
                            </tr>
                            <tr class="spaceUnder">
                                <td><img class="image-responsive" src="http://storage.portal.7sense.no/images/dashboardicons/angle.png" width="43"></td>
                                <td class="tdspace"> Title Angle</td>
                            </tr>
                            <tr class="spaceUnder">
                                <td><img class="image-responsive" src="http://storage.portal.7sense.no/images/ETA_30x30.png" width="43"></td>
                                <td class="tdspace"> ETA</td>
                            </tr>
                            <tr class="spaceUnder">
                                <td><img class="image-responsive" src="http://storage.portal.7sense.no/images/hastighet_spreder_30x30.png" width="43"></td>
                                <td class="tdspace"> Irrigation Speed</td>
                            </tr>
                            <tr class="spaceUnder">
                                <td><img class="image-responsive" src="http://storage.portal.7sense.no/images/dashboardicons/distance.png" width="43"></td>
                                <td class="tdspace"> Remaining Meters</td>
                            </tr>
                        </table>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
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

        Sortable.create(sensors, {
            group: 'sensors',
            animation: 150,
            sort: true,
            handle: '.btn-drag', // draggable button
            onEnd: function(evt) {
                saveToDB(evt);
            }
        });

        function saveToDB(evt) {
            console.log({
                'oldIndex': evt.oldIndex,
                'newIndex': evt.newIndex
            });
            console.log(evt);
        }

        function showInfo() {
            alert("TODO: fill inn info");
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

        function addGroup() {
            const div = document.createElement('div');
            var groupName = '--- New Group ---'
            if (document.getElementById('groupName').value) {
                var groupName = document.getElementById('groupName').value;
            }               
            div.className= 'card bg-light';
            div.id = 'divider';
            div.innerHTML = `
                <p class="card-header">` + groupName + `
                    <button class="btn float-right" onclick="removeGroup()">Delete</button>
                </p>
            `;

            document.getElementById('sensors').appendChild(div);
            // TODO: increase length in allowEditing() function
        }

        function removeGroup() {
            console.log(document.getElementById('divider'));
            var divider = document.getElementById('divider');
            divider.remove();
            // document.getElementById('divider').removeChild(input.parentNode);
        }
        

            


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