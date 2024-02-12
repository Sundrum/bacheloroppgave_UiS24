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
</section>
   <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
      const urlParams = new URLSearchParams(window.location.search);
      const paymentId = urlParams.get('paymentId');
      if (paymentId) {
        const checkoutOptions = {
          checkoutKey: 'test-checkout-key-6ead172d804948e5af1e46507d13d3b5',
          paymentId: paymentId,
          containerId: "checkout-container-div",
        };
        const checkout = new Dibs.Checkout(checkoutOptions);
        checkout.on('payment-completed', function (response) {
          window.location = 'completed.html';
        });
      } else {
        console.log("Expected a paymentId");   // No paymentId provided, 
        window.location = 'cart.html';         // go back to cart.html
      }
    });
   </script>
@endsection