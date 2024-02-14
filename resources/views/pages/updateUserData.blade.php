@extends('layouts.app')

@section('content')
{{-- <section class="bg-white card-rounded"> --}}
<section>
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
   <div id="checkout-container-div">
     <!-- checkout iframe will be embedded here -->
     {{-- <form action="/submit-form" method="post" class="user-form"> --}}
        <form class="user-form">
        <!-- Customer Email -->
        <input type="email" id="customer_email" name="customer_email" placeholder="Enter email"><br>

        <!-- Customer Visit Postcode -->
        <input type="text" id="customer_visitpostcode" name="customer_visitpostcode" placeholder="Enter postcode"><br>

        <!-- Customer Phone -->
        <input type="tel" id="customer_phone" name="customer_phone" placeholder="Enter phone number"><br>

        <!-- User Name -->
        <input type="text" id="user_name" name="user_name" placeholder="Enter name"><br>

        <!-- User Surname -->
        <input type="text" id="user_surname" name="user_surname" placeholder="Enter surname"><br>

        <!-- Customer Visit Address -->
        <input type="text" id="customer_visitaddr1" name="customer_visitaddr1" placeholder="Enter address"><br>

        <!-- Customer Name -->
        <input type="text" id="customer_name" name="customer_name" placeholder="Enter customer name"><br>

        <!-- Customer Visit Country -->
        <input type="text" id="customer_visitcountry" name="customer_visitcountry" placeholder="Enter country"><br>

        <!-- Customer Visit City -->
        <input type="text" id="customer_visitcity" name="customer_visitcity" placeholder="Enter city"><br>

        <!-- Submit Button -->
        <button id="checkout-button" class="btn btn-primary">Proceed to Checkout</button>
    </form>
   </div>
   <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
   {{-- Blade Syntax --}}
    <script>
        var userData = @json($userData);
    </script>
      {{-- Scripts --}}
    <script type="text/javascript" src="{{asset('js/updateUserData.js')}}"></script>              
    <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>
</section>

<!-- Inline CSS Styles -->
<link rel="stylesheet" href="{{ asset('css/updateUserData.css') }}">
@endsection
