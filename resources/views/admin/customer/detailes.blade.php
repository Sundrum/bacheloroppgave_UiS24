@extends('layouts.app')

@section('content')
<script>
setTitle(@json( __('admin.customer')));
</script>
<div class="">
    <div class="row justify-content-center">
      <div class="col-md-12">
         @if (request()->message)
               <div class="alert alert-success">{{ request()->message }}</div>
         @endif
         @if (request()->errormessage)
               <div class="alert alert-danger">{{ request()->errormessage }}</div>
         @endif
         <form  method="post" action="{{ route('detailedcustomer') }}">
            @csrf
            <div class="bg-white card-rounded p-2">
               <div class="m-2">
                  <h5>General information</h5>

                  <div class="row">
                     <div class="col">
                        <label for="customernumber">Customernumber</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="customernumber" value="" class="input-group-text"><i class="fa fa-list-ol fa-fw"></i></span></div>
                           @if(isset($newcustomernumber))
                              <input type="text" class="form-control" id="customernumber_1" name="customernumber_1" value="{{$newcustomernumber}}" required disabled>
                              <input type="hidden" class="form-control" id="customernumber" name="customernumber" value="{{$newcustomernumber}}">
                           @else
                              <input type="text" class="form-control" id="customernumber_1" name="customernumber_1" value="{{$customer->customernumber}}" required disabled>
                              <input type="hidden" class="form-control" id="customer_id" name="customer_id" value="{{$customer->customer_id}}">
                           @endif
                        </div>
                     </div>
   
                     <div class="col">
                        <label for="name">Customer Name</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fa fa-industry fa-fw"></i></span></div>
                           <input type="text" class="form-control" id="name" name="name" value="{{$customer->customer_name ?? ''}}" maxlength="30" required autocomplete="name" autofocus>
                           @error('name')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
   
                  <div class="row">
                     <div class="col">
                        <label for="vatnumber">Vatnumber</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fa fa-dollar-sign fa-fw"></i></span></div>
                           <input type="text" class="form-control" id="vatnumber" maxlength="18" name="vatnumber" value="{{$customer->customer_vatnumber ?? ''}}" autocomplete="vatnumber">
                           @error('vatnumber')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col">
                        <label for="phone">Phone</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                           <input type="tel" class="form-control" id="phone" name="phone" value="{{$customer->customer_phone ?? ''}}" required autocomplete="phone">
                           @error('phone')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
   
                  <div class="row">
                     <div class="col">
                        <label for="maincontact">Main contact</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="maincontact" value="name" class="input-group-text"><i class="fa fa-user fa-fw"></i></span></div>
                           <input type="text" class="form-control" id="maincontact" name="maincontact" value="{{$customer->customer_maincontact ?? ''}}" required autocomplete="maincontact" autofocus>
                           @error('maincontact')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
   
                     <div class="col">
                        <label for="email">E-mail</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fa fa-at fa-fw"></i></span></div>
                           <input type="email" class="form-control" id="email" name="email" value="{{$customer->customer_email ?? ''}}" required autocomplete="email" autofocus>
                           @error('email')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <label for="typecustomer">Type</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <label class="input-group-text" for="typecustomer"><i class="fas fa-tape"></i></label>
                           </div>
                           <select class="custom-select" id="typecustomer" name="typecustomer">
                           @foreach($customertypes as $type)
                              @if(isset($newcustomernumber))
                                 <option value="{{$type['customertype_id'] }}" @if($type['customertype_id']==0) selected="selected" @endif> {{ $type['customertype'] }} </option>
                              @else
                                 <option value="{{$type['customertype_id'] }}" @if($type['customertype_id']==$customer->customertype_id_ref) selected="selected" @endif>{{ $type['customertype'] }} </option>
                              @endif
                           @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col"> 
                        <label for="sitetitle">Site title</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="sitetitle" value="name" class="input-group-text"><i class="fa fa-sitemap fa-fw"></i></span></div>
                           <input type="text" class="form-control" id="sitetitle" name="sitetitle" value="{{$customer->customer_site_title ?? ''}}" autocomplete="sitetitle" autofocus>
                           @error('maincontact')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="bg-white card-rounded p-2 mt-2">
               <div class="m-2">
                  <h5>Adress</h5>
                  <div class="row">
                     <div class="col">
                        <label for="adress1visitt">Adress 1</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="adress1visitt" value="adress1" class="input-group-text"><i class="fa fa-address-book fa-fw"></i></i></span></div>
                           <input type="text" class="form-control" id="adress1visitt" name="adress1visitt" value="{{$customer->customer_visitaddr1 ?? ''}}" autocomplete="adress1visitt" autofocus>
                           @error('adress1visitt')
                              <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col">
                        <label for="adress2visitt">Adress 2</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="adress2visitt" value="adress1" class="input-group-text"><i class="fa fa-address-book fa-fw"></i></i></span></div>
                           <input type="text" class="form-control" id="adress2visitt" name="adress2visitt" value="{{$customer->customer_visitaddr2 ?? ''}}" autocomplete="adress2visitt">
                           @error('adress2visitt')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
   
                  <div class="row">
                     <div class="col">
                        <label for="postcodevisitt">Postcode</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="postcodevisitt" value="adress1" class="input-group-text"><i class="fa fa-map-marker-alt fa-fw"></i></span></div>
                           <input type="text" class="form-control" id="postcodevisitt" name="postcodevisitt" value="{{$customer->customer_visitpostcode ?? ''}}" autocomplete="postcodevisitt" autofocus>
                           @error('postcodevisitt')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col">
                        <label for="cityvisitt">City</label>
                        <div class="input-group">
                           <div class="input-group-prepend"><span name="cityvisitt" value="adress1" class="input-group-text"><i class="fa fa-city fa-fw"></i></i></span></div>
                           <input type="text" class="form-control" id="cityvisitt" name="cityvisitt" value="{{$customer->customer_visitcity ?? ''}}" autocomplete="city" autofocus>
                           @error('cityvisitt')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                     </div>
                  </div>
   
                  <div class="row">
                     <div class="col">
                        <label for="countryvisitt">Country</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <label class="input-group-text" for="countryvisitt"><i class="fa fa-globe fa-fw"></i></label>
                           </div>
                           <select class="custom-select" id="countryvisitt" name="countryvisitt">
                           @foreach($countries as $country)
                              @if(isset($newcustomernumber))
                                 <option value="{{$country['country_id'] }}" @if($country['country_id']==168) selected="selected" @endif> {{ $country['name'] }} </option>
                              @else
                                 <option value="{{$country['country_id'] }}" @if($country['country_id']==$customer->customer_visitcountry) selected="selected" @endif> {{ $country['name'] }} </option>
                              @endif
                           @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col"> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="bg-white card-rounded p-2 mt-2">
               <div class="m-2">
                  <h5>Alerts</h5>
                  <div class="row">
                     <div class="col">
                        <label for="customer_variables_email">Email</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="customer_variables_email" name="customer_variables_email" value="{{$customer->customer_variables_email ?? ''}}" maxlength="255" autofocus>
                           @error('customer_variables_email')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <label for="customer_variables_sms">Alerts SMS</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="customer_variables_sms" name="customer_variables_sms" value="{{$customer->customer_variables_sms ?? ''}}" maxlength="255" autofocus>
                           @error('customer_variables_sms')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col">
                        <label for="customer_variables_sms_enable">SMS Enable (Paid Service)</label>
                        <div class="input-group">
                           <label class="switch">
                              <input type="checkbox" id="customer_variables_sms_enable" name="customer_variables_sms_enable" @if(isset($customer->customer_variables_sms_enable) && $customer->customer_variables_sms_enable) checked @endif>
                              <span class="slider round"></span>
                           </label>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <label for="customer_variables_irrigation_email">Irrigation Email</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="customer_variables_irrigation_email" name="customer_variables_irrigation_email" value="{{$customer->customer_variables_irrigation_email ?? ''}}" maxlength="255" autofocus>
                           @error('customer_variables_irrigation_email')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <label for="customer_variables_irrigation_sms">Irrigation SMS</label>
                        <div class="input-group">
                           <input type="text" class="form-control" id="customer_variables_irrigation_sms" name="customer_variables_irrigation_sms" value="{{$customer->customer_variables_irrigation_sms ?? ''}}" maxlength="255" autofocus>
                           @error('customer_variables_irrigation_sms')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                     <div class="col">
                        <label for="customer_variables_irrigation_sms_enable">SMS Irrigation Enable</label>
                        <div class="input-group">
                           <label class="switch">
                              <input type="checkbox" id="customer_variables_irrigation_sms_enable" name="customer_variables_irrigation_sms_enable" @if(isset($customer->customer_variables_irrigation_sms_enable)) @if($customer->customer_variables_irrigation_sms_enable) checked @endif @else checked @endif>
                              <span class="slider round"></span>
                           </label>
                           @error('customer_variables_irrigation_sms_enable')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                           @enderror
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row justify-content-center">
               <div class="col text-center">
                  <button type="submit" class="mt-2 btn-7s">Save information</button>
               </div>
            </div>

         </form>
    </div>
</div>

<script>
    function copyadress1() {
        // Get adress data
        var adresse1  = $('#adress1visitt').val();
        var adresse2 = $('#adress2visitt').val();
        var postcode = $('#postcodevisitt').val();
        var city     = $('#cityvisitt').val();
        var country  = $('#countryvisitt').val();

        // Copy data 
        $("#adress1invoice").val(adresse1);
        $("#adress2invoice").val(adresse2);
        $("#postcodeinvoice").val(postcode);
        $("#cityinvoice").val(city);
        $("#countryinvoice").val(country);
      
    }

    function copyadress2() {
        // Get adress data
        var adresse1  = $('#adress1invoice').val();
        var adresse2 = $('#adress2invoice').val();
        var postcode = $('#postcodeinvoice').val();
        var city     = $('#cityinvoice').val();
        var country  = $('#countryinvoice').val();

        // Copy data 
        $("#adress1delivery").val(adresse1);
        $("#adress2delivery").val(adresse2);
        $("#postcodedelivery").val(postcode);
        $("#citydelivery").val(city);
        $("#countrydelivery").val(country);
    }

</script>  

@endsection