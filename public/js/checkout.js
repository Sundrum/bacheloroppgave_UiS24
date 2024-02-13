

function GetLang(){
  if(language == 1)
  {
    var lang = "nb-NO";
  } else if(language == 2)
  {
    var lang = "en-GB";
  } else
  {
    var lang = "fr-FR";
  }
  return lang
};

//On Load
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const paymentId = urlParams.get('paymentId');
    if (paymentId) {
      const checkoutOptions = {
        checkoutKey: 'test-checkout-key-6ead172d804948e5af1e46507d13d3b5',
        paymentId: paymentId,
        containerId: "checkout-container-div",
        language: GetLang()
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