<div class="card card-rounded">
    <h5 class="m-4 text-center">Prober</h5>
    <div class="row justify-content-center mt-3 mb-3">
        <div class="col-12">
            <div id="probestable">
                <div class="row">
                    <div class="col-sm-2 mt-1 text-center">
                        Probenumber
                    </div>
                    <div class="col-sm-4 mt-1 text-center">
                        Description
                    </div>

                    <div class="col-sm-2 mt-1 text-center">
                        Alert off
                    </div>
                    <div class="col-sm-2 mt-1 text-center">
                        Hidden
                    </div>
                    <div class="col-sm-2 mt-2 text-center">
                        Delete
                    </div>
                </div>
                @if(isset($probes) && count($probes) > 0)
                <?php $counter = 1 ?>
                    @foreach($probes as $probe)
                        <div class="object" id="{{$counter}}">
                            <div class="row m-2">
                                <div class="col-sm-2 mt-1 text-center">
                                    <input type="text" class="form-control" value="{{$probe->sensorprobes_number}}" disabled>
                                </div>
                                <div class="col-sm-4 mt-1 text-center">
                                    <input type="text" class="form-control" value="{{$probe->unittype_description}}" disabled>
                                </div>

                                <div class="col-sm-2 mt-1 text-center">
                                    <label class="switch">
                                        <input type="checkbox" @if($probe->sensorprobes_alert_hidden && $probe->sensorprobes_alert_hidden == '1') checked @endif class="btn btn-primary" onclick="alertHidden('{{$probe->sensorprobes_id}}', '{{$probe->sensorprobes_alert_hidden}}')" id="[{{$probe->sensorprobes_number}}][alert_hidden]">
                                            <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-sm-2 mt-1 text-center">
                                    <label class="switch">
                                        <input type="checkbox" @if($probe->hidden && $probe->hidden == '1') checked @endif class="btn btn-primary" onclick="probeHidden('{{$probe->sensorprobes_id}}', '{{$probe->hidden}}')" id="[{{$probe->sensorprobes_number}}][hidden]">
                                            <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-sm-2 mt-2 text-center">
                                    <i onclick="deleteRow('{{$probe->sensorprobes_id}}', '{{$counter}}')" class="fa fa-times" style="color:red" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <?php $counter++ ?>
                    @endforeach
                @else
                    <div id="noprobes" class="col-md-12 text-center">
                        Ingen prober knyttet til dette produktet
                    </div>
                @endif
                <div class="text-center mt-3">
                    <button class="btn" onclick="addField();"><i class="fa fa-plus" style="color:green" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var token = "{{ csrf_token() }}";
var counter = $('#probestable').find('.object').length;
var code2 = '<option value="">Velg enhet</option>';
var code3 = '';
getData();

function probeHidden(id, value) {
    $.ajax({
        url: "/admin/sensorprobe/hidden",
        type: 'POST',
        data: { 
            "id": id,
            "value": value,
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
        },   
        error: function(msg) {
            errorMessage("Failed - Please try again");
        }
    });
    console.log('Hidden ' + id + ' - '  + value)
}

function alertHidden(id, value) {
    $.ajax({
        url: "/admin/sensorprobe/alert",
        type: 'POST',
        data: { 
            "id": id,
            "value": value,
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
        },   
        error: function(msg) {
            alert("Failed - Please try again")
        }
    });
    console.log('Alert Hidden '+ id + ' - '  + value)
}

function addField() {
    counter++;
    console.log(counter);
    var code = '<div class="object" id="'+counter+'" style="background: #d2f8d2";>';
        code += '<div class="row m-2">';
        code += '<div class="col-sm-2 text-center"><input id="sensorprobe_number" type="text" class="form-control" required></div>';
        code += '<div class="col-sm-8 mt-1 mb-1"">';
        code += '<select class="seriallist form-control" onchange="addNew('+counter+');" id="['+counter+'][unittype_id]" name="['+counter+'][unittype_id]">';
        code += code2;
        code += '</select></div>';
        code += '<div class="col-sm-2 mt-2 text-center"><i onclick="removeRow('+counter+')" class="fa fa-minus" style="color:red" aria-hidden="true"></i></div></div></div>';
    if (counter == 1) {
        $('#noprobes').hide();
        $('#noprobes').after(code);
    } else {
        $('#probestable .object:last').after(code);
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
        url: '/admin/unittypeslist',
        dataType: 'json',      
        success: function(data) {
            for (var i in data) {
                code2 += '<option value="'+data[i][0].trim()+'">'+data[i][1].trim()+', '+ data[i][2].trim(); +'</option>';
            }
        }
    });
}

function addNew(id){
    var unittype_id = $('#'+id).find('select').val();
    var sensorprobe = $('#'+id).find('#sensorprobe_number').val();
    var product_id = $('#product_id').val();
    console.log("Unittype_id = " + unittype_id + " Sensorprobe_number = " + sensorprobe + ' Product ID = ' + product_id);
    $.ajax({
        url: "/admin/sensorprobe/add",
        type: 'POST',
        data: { 
            "unittype_id": unittype_id,
            "sensorprobe_number": sensorprobe,
            "product_id": product_id,
            "_token": token,
        },
        success: function(data) {
            console.log('Data readback = '+ data);
            var code3 = '<div class="row m-2"><div class="col-sm-2 mt-1 text-center"><input type="text" class="form-control" value="';
                code3 += data.sensorprobes_number;
                code3 += '" disabled></div><div class="col-sm-4 text-center"><input type="text" class="form-control" value="';
                code3 += data.unittype_description;
                code3 += '" disabled></div><div class="col-sm-2 mt-1 text-center"><label class="switch"><input type="checkbox" checked class="btn btn-primary" id="[';
                code3 += data.sensorprobes_id;
                code3 += '][alert_hidden]"><span class="slider round"></span></label></div><div class="col-sm-2 mt-1 text-center"><label class="switch"><input type="checkbox" checked class="btn btn-primary" id="[';
                code3 += data.sensorprobes_id;
                code3 += '][hidden]"><span class="slider round"></span></label></div><div class="col-sm-2 mt-2 text-center"><i onclick="deleteRow('+data.sensorprobes_id+', '+id+')" class="fa fa-times" style="color:red" aria-hidden="true"></i></div></div>';

            $('#'+id).empty();
            $('#'+id).html(code3);
        },   
        error: function(msg) {
            console.log(msg);
            alert("Failed - Please try again")
        }
    });
}

function deleteRow(id, counter) {
    var confirmed = confirm('Ønsker du å slette denne proben?');
    if(confirmed) {
        $.ajax({
            url: "/admin/sensorprobe/delete",
            type: 'POST',
            data: { 
                "id": id,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
                $('#'+counter).remove();
            },   
            error: function(msg) {
                alert("Failed - Please try again")
            }
        });
    }
}
</script>