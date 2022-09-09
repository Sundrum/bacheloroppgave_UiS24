<div class="modal fade" id="addGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('dashboard.addnewgroup')</h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="input-group">
                        <label for="groupName" class="col-md-4 col-form-label text-md-right">@lang('dashboard.groupname')</label>

                        <div class="input-group-prepend"><span name="prefixproduct" class="input-group-text"><i class="fa fa-signature"></i></span></div>
                        <input type="text" class="col-md-4 form-control" id="groupName" name="groupName" placeholder="Enter the name of the new group">
                    </div>
                </div>
                <p> @lang('dashboard.addgroupinfo') </p>
                {{-- <p>To add a new group, please select your desired sensors and fill in a group name. Press "Add Group" to save.</p> --}}
                    {{-- @foreach (Session::get('customerunits') as $unit)
                        @if(is_array($unit))
                            <div style="display: flex;">
                                <div style="margin-top: 5px">
                                    <p>{{ $unit['serialnumber'] }}</p>
                                </div>
                                <div style="margin-left: 1em;">
                                    <label class="switch">
                                        <input type="checkbox" onclick="addSensorToNewGroup('{{trim($unit['serialnumber'])}}')" class="btn btn-primary">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach --}}
                    <br>
                    <input type="hidden" id="customerNumber" value="{{ Session::get('customernumber') }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('dashboard.close')</button>
                <button class="btn btn-primary float-right" onclick="submitForm()">@lang('dashboard.addgroup')</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var newSensorGroup = [];

    function addSensorToNewGroup(serial) {
        if (newSensorGroup.includes(serial)) {
            newSensorGroup = newSensorGroup.filter(function(item) {
                return item !== serial
            })
            console.log("Sensor: " + serial + " removed from array");
        } else {
            newSensorGroup.push(serial);
            console.log("Sensor: " + serial + " added to array");

        }
        console.log(newSensorGroup);
    }

    function getNewSensorGroup() {
        return newSensorGroup;
    }

    function submitForm() {
        var customerNumber = document.getElementById("customerNumber").value;
        var groupName = document.getElementById("groupName").value;
        if (groupName === '') {
            alert("You must fill out group name!");
        } else { 
            var groupArray = getNewSensorGroup();
            var token = "{{ csrf_token() }}";
            $.ajax({
                url: "/addgroup",
                type: 'POST',
                data: { 
                    "customerNumber": customerNumber,
                    "groupName": groupName,
                    "groupArray": groupArray,
                    "_token": token,
                },
                success: function(msg) {
                    console.log(msg);
                    window.location = "/dashboard";
                },   
                error:function(msg) {
                    console.log(msg);
                    alert("Problem")
                }
            });
        }
    }
</script>