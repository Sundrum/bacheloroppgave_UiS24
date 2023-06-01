@if(isset($user))
<div class="col-md-6">
    <div class="card card-rounded">
        <h5 class="m-4 text-center">@lang('admin.devices') (@if(isset($user['sensorunits']) && count($user['sensorunits']) > 0) {{count($user['sensorunits']) ?? '0'}} @endif)</h5>
        <div id="sensorTable">
            @if(isset($user['sensorunits']) && count($user['sensorunits']) > 0)
                    <?php $counter = 1;
                    ?>
                    @foreach($user['sensorunits'] as $unit)
                        <div class="object" id="{{$counter}}">
                            <div class="row m-2">

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$unit->serialnumber}}" disabled>
                                </div>

                                <div class="col-sm-2 mt-1 text-center">
                                    <label class="switch">
                                        <input type="checkbox" @if($unit->changeallowed && $unit->changeallowed == '1') checked @endif class="btn btn-primary" id="[others][access][{{$unit->serialnumber}}]">
                                            <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-sm-2 mt-2 text-center">
                                    <i onclick="deleteRow('{{$counter}}', '{{$user->user_id}}', '{{$unit->serialnumber}}')" class="fa fa-times" style="color:red" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <?php $counter++ ?>
                    @endforeach
            @else
                <div id="nounits" class="col-md-12 text-center">
                    @lang('nounits')
                </div>
            @endif
        </div>
        <div class="text-center mt-3">
            <button class="btn" onclick="addField();"><i class="fa fa-plus" style="color:green" aria-hidden="true"></i></button>
        </div>
    </div>
</div>

<script>
var counter = $('#sensorTable').find('.object').length;
var code2 = '<option value="">Velg serienummer</option>';
var code3 = '';
getData();

function addField() {
    counter++;
    console.log(counter);
    var code = '<div class="object" id="'+counter+'" style="background: #d2f8d2";>';
        code += '<div class="row m-2">';
        code += '<div class="input-group col-sm-8 mt-1"">';
        code += '<select class="seriallist form-control" onchange="addNew('+counter+');" id="['+counter+'][serialnumber][new]" name="['+counter+'][serialnumber][new]">';
        code += code2;
        code += '</select></div>';
        code += '<div class="col-sm-2 mt-1 text-center"><label class="switch"><input type="checkbox" checked class="btn btn-primary" id="access" name="access"><span class="slider round"></span></label></div>';
        code += '<div class="col-sm-2 mt-2 text-center"><i onclick="removeRow('+counter+')" class="fa fa-minus" style="color:red" aria-hidden="true"></i></div></div></div>';
    if (counter == 1) {
        $('#nounits').hide();
        $('#nounits').after(code);
    } else {
        console.log('Add Seconds');
        $('#sensorTable .object:last').after(code);
    }
    makeSearchable();
}

function makeSearchable(){
    $('.seriallist').select2();
}

function removeRow(index) {
    $('#'+index).remove();
}

function getData(){
    $.ajax({
        url: '/admin/sensorunitall',
        dataType: 'json',      
        success: function(data) {
            for (var i in data) {
                code2 += '<option value="'+data[i][0].trim()+'">'+data[i][0].trim()+'</option>';
            }
        }
    });
}

function addNew(id){
    var serialnumber = $('#'+id).find('select').val();
    var access = $('#'+id).find('#access').is(":checked");
    var userid = $('#user_id').val();
    console.log("Serialnumber = " + serialnumber + "Access = " + access + " UserID = " + userid);
    $.ajax({
        url: "/admin/connect/add",
        type: 'POST',
        data: { 
            "serialnumber": serialnumber,
            "userid": userid,
            "access": access,
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
            var code3 = '<div class="row"> <div class="input-group mt-1 col-sm-8"> <input type="text" class="form-control" value="'+serialnumber+'" disabled> </div>';
            code3 += '<div class="col-sm-2 mt-1 text-center"> <label class="switch"> <input type="checkbox"';
            console.log(access);
            if(access == true) {
                code3 += ' checked ';
            }
            code3 += 'class="btn btn-primary" id="[others][access]['+serialnumber+']"><span class="slider round"></span></label></div>';
            code3 += '<div class="col-sm-2 mt-2 text-center"><i onclick="deleteRow('+id+', '+userid+', '+serialnumber+')" class="fa fa-times" style="color:red" aria-hidden="true"></i></div></div>';

            $('#'+id).empty();
            $('#'+id).html(code3);

        },   
        error: function(msg) {
            console.log(msg);
            alert("Failed - Please try again")
        }
    });
}

function deleteRow(id, userid, serialnumber) {
    var confirmed = confirm(@json( __('admin.removeunit')));

    if(confirmed) {
        $.ajax({
            url: "/admin/connect/delete",
            type: 'POST',
            data: { 
                "serialnumber": serialnumber,
                "userid": userid,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
                $('#'+id).remove();
            },   
            error: function(msg) {
                alert("Failed - Please try again")
            }
        });
    }
}
</script>

@endif