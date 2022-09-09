<div class="modal" id="changeSerialModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-a-grey">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-center">Change Serialnumber</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div id="message-feeback"></div>
                </div>
            </div>
            <form method="POST" id="serialchange" action="/admin/irrigation/fota">
                @csrf
                <div class="form-group row justify-content-center">
                    <label for="newserial" class="col-md-4 col-form-label">{{ __('New Serialnumber') }}</label>
                    <div class="input-group col-md-6">
                        <div class="input-group">
                            <input type="number" class="form-control" id="newserial" name="newserial" placeholder="E.g 1" required>
                        </div>
                        <span class="text-muted">Last digits from serial 21-1020-AA-00001, fill in 1</span>
                    </div>
                </div>            
                <div class="form-group row justify-content-center">
                    <label for="version" class="col-md-4 col-form-label">{{ __('IMEI') }}</label>
                    <div class="input-group col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="imei" name="imei" placeholder="IMEI" required>
                        </div>
                        <span class="text-muted">Read from Settings:  {{$variable['imei']['value'] ?? 'Ukjent'}}</span>
                        <span class="text-muted">0 will override without knowing the IMEI</span>
                    </div>
                </div>
                <input type="hidden" name="cmd" value="changeserial">
                <input type="hidden" name="serialnumber" value="{{$variable['unit']['serialnumber']}}">
            
                <div class="row justify-content-center">
                    <div class="col-3">
                        <button type="submit" id="userform" class="btn-primary-filled"><strong>Change Serialnumber</strong></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$( "#serialchange" ).on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize() 
    $.ajax({
        type: "POST",
        url: "/admin/irrigation/fota",
        data: dataString,
        success: function (msg) {
            console.log(msg);
            if (msg == 1) {
                $('#message-feeback').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Oppdatert</p></div>');
                $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-alert").slideUp(500);
                });
            } else {
                $('#message-feeback').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                    $("#danger-alert").slideUp(500);
                });
            }
        }
    });
});
</script>