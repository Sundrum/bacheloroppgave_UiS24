@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
    <div id="error-message" class="alert alert-danger" style="display: none;">
      Error: Checkout page is currently unavailable. Please contact support.
    </div>
  
   <div id="checkout-container-div">
     <!-- checkout iframe will be embedded here -->
   </div>
   <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
   
   {{-- Blade Routes --}}
   <script>
    var language = {{$language}};
    var checkoutKey = "{{ $checkoutKey }}";
    console.log("visacard: 4268270087374847")
    console.log("mastercard: 5213199803453465")
    var managebool = @json($managebool);
    var subscriptionId = "{{$subscriptionId ?? ''}}";
  </script>
  <script  type="text/javascript" src="{{asset('js/checkout.js')}}"></script>
  @endsection