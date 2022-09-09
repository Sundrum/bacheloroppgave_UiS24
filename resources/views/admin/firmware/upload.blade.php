<div class="modal" id="uploadModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-a-grey">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-center">Upload Firmware</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body mt-3 mb-3">
            <form method="POST" name="upload" id="upload" action="{{route('uploadFirmware')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row justify-content-center">
                    <label for="user_name" class="col-md-4 col-form-label">{{ __('Firmware name') }}</label>
                    <div class="input-group col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="fw_name" name="fw_name" placeholder="Eg. Irrigation-AA" value="" required>
                        </div>
                    </div>
                </div>            
                <div class="form-group row justify-content-center">
                    <label for="version" class="col-md-4 col-form-label">{{ __('Version') }}</label>
                    <div class="input-group col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="version" name="version" placeholder="Eg. 4.0.0" value="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <label for="productnumber" class="col-md-4 col-form-label">{{ __('Productnumber') }}</label>
                    <div class="input-group col-md-6">
                        <select name="productnumber" id="productnumber" class="form-control">
                            <option value="21-1020-AA" selected>21-1020-AA</option>
                            <option value="21-1020-AB">21-1020-AB</option>
                            <option value="21-9020-AA">21-9020-AA</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <label for="released" class="col-md-4 col-form-label">{{ __('Released') }}</label>
                    <div class="input-group col-md-6">
                        <select name="released" id="released" class="form-control">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row justify-content-center">
                    <label for="firmware" class="col-md-4 col-form-label">{{ __('ZIP fil') }}</label>
                    <div class="col-md-6">
                        <input type="file" accept=".zip" name="firmware" id="firmware" required>
                    </div>
                </div>
            
                <div class="row justify-content-center">
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary-filled"> Upload </button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>

{{-- <script>
$( "#upload" ).on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize() 
    $.ajax({
        type: "POST",
        url: "/admin/upload/firmware",
        data: dataString,
        success: function (msg) {
            console.log(msg);
            if(msg == 1) {
                console.log("Updated");
            } else {
                console.log("Error");
            }
        }
    });
});
</script> --}}