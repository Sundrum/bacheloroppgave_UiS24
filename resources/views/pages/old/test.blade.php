@extends('layouts.app')

<!-- Sortable CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

@section('scripts')
    <style>
        h1, p {
            margin-top: 1em;
        }
    </style>
    @auth
        <div class="container">
        @if (Session::get('customer_site_title'))
            <h2>{{ Session::get('customer_site_title') }}</h2>
        @endif

        {{-- {{ dd($result) }} --}}
        @if (count($result) > 0)
        <input type="checkbox" id="allow" value="allow" checked>
        <label for="allow">Allow editing of list</label><br>
        <div id="phpgroup">
           {{-- The following variable is used inside the foreach so that a 'show more' button
           only collapses target div and does not collapse all sensor divs. There is an echo on 
           the data-target and div id, variable i increases by one at the end of foreach --}}
           <?php $i = 0;?>
           @foreach ($result as $unit)
               {{-- {{ dd($unit) }} --}}

               <div class="card bg-light">
                   <p class="card-header">
                       {{-- {{ $unit['serialnumber'] }} --}}
                       {{ $unit['sensorname'] }}
                       {{-- {{  dd($unit) }} --}}
                       @foreach ($unit as $probe)
                           @if (is_array($probe))
                               {{ $probe['value'] }}
                               {{ $probe['unittype_shortlabel'] }} 
                           @endif
                       @endforeach
                   
                       <button class="btn btn-primary float-right" type="button" data-toggle="collapse" 
                           data-target="#collapseExample<?php echo $i; ?>" 
                           aria-expanded="false" aria-controls="collapseExample">
                           Show more
                       </button>
                   </p>
                   <div class="collapse" id="collapseExample<?php echo $i; ?>">
                       {{-- $unit--}}
                       {{ $unit['timestamp']}}
                       @isset($data['product_image_url'])
                           <img class="card-img" style="width: 4rem;" src="{{ $data['product_image_url'] }}">
                       @endisset
                       
                       <div class="card-body">
                           {{--<p>Last connected: {{ $data['timestamp'] }}</p>
                           <p>Location: {{ $data['sensorunit_location'] }}</p>--}}   
                       </div>
                   </div>
               </div>
               {{-- increase variable i --}}
               <?php $i++; ?>
           @endforeach
       </div>
   {{-- if result is 0 (aka the user has no sensors) --}}
   @else
   <h2>No sensors are registered for your user</h2>
   @endif

    

<h1>List</h1>
<p>Sortable list with all items in one group</p>
<div id="group">
    <div class="card">
        <div class="card-body">Item 1</div>
    </div>
    <div class="card">
        <div class="card-body">Item 2</div>
    </div>
    <div class="card">
        <div class="card-body">Item 3</div>
    </div>
</div>

<div id="newGroup"></div>

<div id="control">
    <h1>Create a new group</h1>
    <input type="text" placeholder="Group name" id="groupName">
    <button type="button" class="btn btn-secondary" onclick="generateGroup()">Add a group</button>
</div>

<script>
    Sortable.create(phpgroup, {
        group: "phpgroup",
        animation: 150,
        sort: true
    });

    Sortable.create(group, {
        group: "list",
        animation: 150,
        sort: true,
        store: {
            /**
            * Get the order of elements. Called once during initialization.
            * @param   {Sortable}  sortable
            * @returns {Array}
            */
            get: function (sortable) {
                var order = localStorage.getItem(sortable.options.group.name);
                console.log("The groupName of locally saved array: " + sortable.options.group.name);
                console.log("Saved order is: " + order);
                return order ? order.split('|') : [];
            },

            /**
            * Save the order of elements. Called onEnd (when the item is dropped).
            * @param {Sortable}  sortable
            */
            set: function (sortable) {
                var order = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, order.join('|'));
                console.log(order);
            }
        }
    });

function generateGroup() {
    // Get value from input field 
    var groupName = document.getElementById("groupName").value;
    var groupExists = document.getElementById(groupName);
    // Give user alert if group name is empty or already exists
    if (!groupName) {
        alert("group name cannot be empty!");
    } else if (groupExists){
        alert("group already exists!");
    } else {
        // Create header
        var header = document.createElement("h1");
        var node = document.createTextNode(groupName);
        header.appendChild(node);
        header.id = "header";

        // Add to HTML <div>
        var element = document.getElementById("newGroup");
        element.appendChild(header);

        // Create a delete button
        generateDeleteButton(groupName);

        // Make the group sortable
        Sortable.create(newGroup, {
            group: "list",
            animation: 150,
            sort: true,
            // TODO: Why on earth does the example group start from index 0 
            // but the new one from index position 1??? 
            onAdd: function (evt) {
                console.log("The elements old index was: " + evt.oldIndex);
                console.log("The elements new index is: " + evt.newIndex);
            }
        });
    }
}

function generateDeleteButton(groupName) {
    var button = document.createElement("button");
    var buttonText = document.createTextNode("Delete group '" + groupName + "'");
    button.appendChild(buttonText);
    button.className = "btn btn-warning";
    button.id = groupName;
    // Calls the deleteItem function, notice the lack of (), this is because the 
    // event groupener is created and is not supposed to be triggered. Bind is used
    // So that the button can be deleted regardless of group name
    button.addEventListener('click', deleteItem.bind(this, groupName));
    var element = document.getElementById("control");
    element.appendChild(button);

}

function deleteItem(groupName) {
    // This is static because the group header is given an id of 
    // 'header' in generateGroup() function
    var group = document.getElementById("header");
    group.parentNode.removeChild(group);
    // TODO: this one needs to be dynamic
    var button = document.getElementById(groupName);
    button.parentNode.removeChild(button)
    
}
</script>
</div>
@endauth
@endsection