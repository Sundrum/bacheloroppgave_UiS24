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
        window.location = checkoutSuccessRoute;
      });
    } else {
      console.log("Expected a paymentId");   // No paymentId provided, 
      window.location = 'cart.html';         // go back to cart.html
    }
});