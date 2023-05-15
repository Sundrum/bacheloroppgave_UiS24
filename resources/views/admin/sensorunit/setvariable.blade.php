<div class="collapse row mb-2" id="collapseVariable">
    <div class="col-12 card card-rounded">
        <div class="row justify-content-center mt-3 mb-2">
            <div class="col-md-6">
                <div id="message-feeback2"></div>
            </div>
        </div>
        <h5>Write Command</h5>
        <hr>
        <div class="row justify-content-center mb-3">
            <div class="col-12">
                <div class="row pl-2 pr-2">
                    <div class="col-2 text-center">
                        <button class="btn-7g" id="vibration"><strong>Vibration</strong></button>
                    </div>
                    <div class="col-2 text-center">
                        <button class="btn-7g" id="reset_tilt"><strong>Reset Tilt</strong></button>
                    </div>
                    <div class="col-2 text-center">
                        <button class="btn-7g" id="state_idle"><strong>Set State IDLE</strong></button>
                    </div>
                    <div class="col-2 text-center">
                        <button class="btn-7g" id="state_presettling"><strong>Set State Pre-Settling</strong></button>
                    </div>
                    <div class="col-2 text-center">
                        <button class="btn-7g" id="send_packet"><strong>Send packet</strong></button>
                    </div>
                            {{-- <div class="col-4 text-center">
                                <button class="btn-secondary-filled" id="pressure_settling"><strong>Pressure</strong></button>
                            </div>
                            <div class="col-6 text-center mt-2">
                                <button class="btn-secondary-filled" id="settling_idle"><strong>Time to Idle,Irrigation</strong></button>

                    {{-- <div class="col-6 card card-rounded bg-grey">
                        <div class="row justify-content-center mt-2 mb-2">
                            <div class="col-12 text-center">
                                <h5>Irrigation</h5>
                                <hr>
                            </div>
                            <div class="col-4 text-center">
                                <button class="btn-secondary-filled" id="vibration_irrigation"><strong>Vibration</strong></button>
                            </div>
                            <div class="col-4 text-center">
                                <button class="btn-secondary-filled" id="pressure_irrigation"><strong>Pressure</strong></button>
                            </div>
                            <div class="col-4 text-center">
                                <button class="btn-secondary-filled" id="tilt_irrigation"><strong>Tilt</strong></button>
                            </div>
                            <div class="col-6 text-center mt-2">
                                <button class="btn-secondary-filled" id="gnss_irrigation"><strong>GNSS</strong></button>
                            </div>
                            <div class="col-6 text-center mt-2">
                                <button class="btn-secondary-filled" id="battery_irrigation"><strong>Battery</strong></button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <form method="POST" id="variablesform" class="mb-3" action="/admin/irrigation/fota">
            @csrf
            <div class="form-group row">
                <div class="input-group col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="commandline" name="commandline" placeholder="Eg. 8,1,0" required>
                    </div>
                    <span class="text-muted">Multiple Commands use ; to seperate</span>
                </div>
            </div>
            <input type="hidden" name="cmd" value="setsettings">
            <input type="hidden" name="serialnumber" value="{{$variable['unit']['serialnumber']}}">
        
            <div class="row justify-content-center">
                <div class="col-12 text-center" >
                    <button type="submit" class="btn-7s px-5"><strong>Send Command</strong></button>
                </div>
            </div>
        </form>
        
    </div>
</div>

<script>
$('#vibration').click( function () {
    var cmd = $('#commandline').val()
    if(cmd) {
        $('#commandline').val(cmd + ';10,19,0.15;10,25,0.1');
    } else {
        $('#commandline').val('10,19,0.15;10,25,0.1');
    }
});

$('#reset_tilt').click( function () {
    var cmd = $('#commandline').val()
    if(cmd) {
        $('#commandline').val(cmd + ';3');
    } else {
        $('#commandline').val('3');
    }
});

$('#state_idle').click( function () {
    var cmd = $('#commandline').val()
    if(cmd) {
        $('#commandline').val(cmd + ';state_id,1');
    } else {
        $('#commandline').val('state_id,1');
    }
});

$('#state_presettling').click( function () {
    var cmd = $('#commandline').val()
    if(cmd) {
        $('#commandline').val(cmd + ';state_id,4');
    } else {
        $('#commandline').val('state_id,4');
    }
});

$('#send_packet').click( function () {
    var cmd = $('#commandline').val()
    if(cmd) {
        $('#commandline').val(cmd + ';send_status');
    } else {
        $('#commandline').val('send_status');
    }
});

$('#tilt_irrigation').click( function () {
    var cmd = $('#commandline').val()
    //editited
    if(cmd) {
        $('#commandline').val(cmd + ';8,2,0,nan,-0.4');
    } else {
        $('#commandline').val('8,2,0,nan,-0.4');
    }
});


$( "#variablesform" ).on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize() 
    $.ajax({
        type: "POST",
        url: "/admin/irrigation/fota",
        data: dataString,
        success: function (msg) {
            console.log(msg);
            if (msg == 1) {
                $('#message-feeback2').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Oppdatert</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                });
                setTimeout(function(){ 
                    location.reload();
                }, 3000);
            } else {
                $('#message-feeback2').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            }
        }
    });
});
</script>