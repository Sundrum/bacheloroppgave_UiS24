@extends('layouts.admin')

@section('content')
@extends('layouts.app')

@section('content')

<div class="container mt-3"> 
   <h2>Sensorunit</h2>
      <form>
         <div class="row">
            <div class="col">  
            <label for="serialnumber">Serialnumber</label>
               <div class="input-group">
                  <div class="input-group-prepend">
                     <select style="height: 35px;width: 100%;" class="form-control" id="statustype" name="statustype">
                        <option value="">Velg product</option>
                        @foreach($products['result'] as $indexKey => $row)
                        <option value="{{$row['product_id'] }}" @if($indexKey==0) selected="selected" @endif> {{ $row['productnumber'] }} </option>
                        @endforeach
                     </select>
         
                  </div>
                  <input type="text" class="form-control" id="serialnumber" name="serialnumber" value="" required autocomplete="serialnumber" autofocus>

                  @error('serialnumber')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>
            <div class="col"> 
               <label for="location">Location</label>
               <div class="input-group">
                  <input type="text" class="form-control" id="location" name="location" value="" required autocomplete="location" autofocus>

                  @error('location')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col"> 
               <label for="status">Status</label>
               <div class="input-group">
                  <input type="text" class="form-control" id="status" name="status" value="" required autocomplete="status" autofocus>

                  @error('status')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>

            <div class="col"> 
               <label for="database">Database</label>
               <div class="input-group">
                  <input type="text" class="form-control" id="database" name="database" value="" required autocomplete="database" autofocus>

                  @error('database')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>
         </div>



         <div class="row">

            <div class="col"> 
               <label for="position">Position</label>
               <div class="input-group">
                  <input type="text" class="form-control" id="position" name="position" value="" required autocomplete="position" autofocus>

                  @error('position')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>

            <div class="col"> 
               <label for="customer">Customer</label>
                  <select name="probe3" class="custom-select" id="probe3">
                  @foreach($products['result'] as $indexKey => $row)
                  <option value="{{$row['product_id'] }}" @if($row['product_id']==2) selected="selected" @endif> {{ $row['productnumber'] }} </option>
                  @endforeach
                  </select>
            </div>
         </div>


         <div class="row">
            <div class="col"> 
               <label for="notes">Notes</label>
               <div class="input-group">
                  <input type="textarea" class="form-control" id="notes" name="notes" value="" required autocomplete="notes" autofocus>

                  @error('notes')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>

            <div class="col"> 
               <label for="helpdesk">Helpdesk</label>
               
               <select name="helpdesk" class="custom-select" id="helpdesk">
               @foreach($products['result'] as $indexKey => $row)
               <option value="{{$row['product_id'] }}" @if($row['product_id']==2) selected="selected" @endif> {{ $row['productnumber'] }} </option>
               @endforeach
               </select>
            </div>
         </div>
         @error('status')
         <span class="invalid-feedback" role="alert">
         <strong>{{ $message }}</strong>
         </span>
         @enderror
      </div>
   </div>
</div>

 <div class="form-group row mb-0">
      <div class="col-md-12 offset-md-4">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;{{ __('Registrer') }}
        </button>
        <button id="cancel_dommer" onclick="window.location.href = '/products';" data-toggle="tooltip" title="cancel" type="button" class="btn btn-success" style="padding-button : 15px;"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;@lang('buttons.cancel')
        </button>
      </div>
    </div>
</div>  

</form>

</div>
@endsection