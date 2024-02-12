@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
   <div id="checkout-container-div">
     <!-- checkout iframe will be embedded here -->
   </div>

   <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
   
   {{-- Blade Routes --}}
   <script>
    var checkoutSuccessRoute = "{{ route('checkoutSuccess') }}";
  </script>

   <script  type="text/javascript" src="{{asset('js/checkout.js')}}"></script>
@endsection