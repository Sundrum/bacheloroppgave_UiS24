@extends('layouts.app')

@section('content')
<div class="bg-white card-rounded p-2" id="serialinput">
    <div class="input-group">
        <span>Serialnumber</span>
        <input type="text" name="serialnumber" id="serialnumber" placeholder="Serialnumber">
    </div>
    <button class="btn-7g" onclick="getVariables()">Submit</button>
</div>

<script>
setTitle('Proxy Variables');

function getVariables() {
    var serial = document.getElementById("serialnumber").value
    $.ajax({
        url: "/admin/proxy/getvariables",
        type: 'POST',
        dataType: 'json',
        data: { 
            "serialnumber": serial,
            "_token": token,
        },
        success: function(data) {
            console.log(data);
            // if(data[0]) {
            //     let point = data[0];
            //     const main_info = document.createElement("div");
            //     main_info.innerHTML +='<div class="row"><span class="col-3">'+point.serialnumber+',</span><span class="col-3">'+point.variable+':</span><span class="col-3">'+point.value+',</span></div>';
            //     document.getElementById('serialinput').appendChild(main_info);
            //     document.getElementById('serialnumber').value = '';
            // }
            
        },   
        error: function(data) {
            console.log(data);
            errorMessage('Something went wrong')

        }
    });
}

</script>
@endsection