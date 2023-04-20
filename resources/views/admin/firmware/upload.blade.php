<div class="modal" id="uploadModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-grey">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="m-auto">Upload Firmware</h4>
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
                            @foreach ($variable['products'] as $product)
                                <option value="{{$product->productnumber}}" selected>{{$product->productnumber}}, {{$product->product_name}}</option>
                            @endforeach
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
                    <label for="firmware" class="col-md-4 col-form-label">{{ __('File') }}</label>
                    <div class="col-md-6">
                        <input type="file" accept=".bin, .cbor" name="firmware" id="firmware" required>
                        <span class="text-muted">Format accepted (.bin / .cbor)</span>
                    </div>
                </div>
            
                <div class="row justify-content-center">
                    <div class="col text-center">
                        <button type="submit" class="btn-7s"> Upload </button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>