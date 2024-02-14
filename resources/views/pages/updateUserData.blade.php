@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
   <div id="checkout-container-div">
     <!-- checkout iframe will be embedded here -->
     <h1>User Data Input</h1>
    <form action="/submit-form" method="post">
        <!-- Customer Email -->
        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email"><br>

        <!-- Customer Visit Postcode -->
        <label for="customer_visitpostcode">Customer Visit Postcode:</label>
        <input type="text" id="customer_visitpostcode" name="customer_visitpostcode"><br>

        <!-- Customer Phone -->
        <label for="customer_phone">Customer Phone:</label>
        <input type="tel" id="customer_phone" name="customer_phone"><br>

        <!-- User Name -->
        <label for="user_name">User Name:</label>
        <input type="text" id="user_name" name="user_name"><br>

        <!-- User Surname -->
        <label for="user_surname">User Surname:</label>
        <input type="text" id="user_surname" name="user_surname"><br>

        <!-- Customer Visit Address -->
        <label for="customer_visitaddr1">Customer Visit Address:</label>
        <input type="text" id="customer_visitaddr1" name="customer_visitaddr1"><br>

        <!-- Customer Name -->
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name"><br>

        <!-- Customer Visit Country -->
        <label for="customer_visitcountry">Customer Visit Country:</label>
        <input type="text" id="customer_visitcountry" name="customer_visitcountry"><br>

        <!-- Customer Visit City -->
        <label for="customer_visitcity">Customer Visit City:</label>
        <input type="text" id="customer_visitcity" name="customer_visitcity"><br>

        <!-- Submit Button -->
        <button type="submit">Submit</button>
    </form>
   </div>
   <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
   {{-- Blade Routes --}}
   <script>
  </script>
@endsection