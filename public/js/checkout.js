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

function setTheme(bgColor, panelColor){
  var theme = {
    "backgroundColor": bgColor,
    "panelcolor": panelColor,
  }
  return theme;
}

//On Load
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const paymentId = urlParams.get('paymentId');
    if (paymentId) {
      const checkoutOptions = {
        checkoutKey: checkoutKey,
        paymentId: paymentId,
        containerId: "checkout-container-div",
        language: GetLang(),
      };
      const checkout = new Dibs.Checkout(checkoutOptions);
      checkout.setTheme(setTheme("#ffffff", "#ffffff"));
      checkout.on('payment-completed', function (response) {
        window.location = checkoutSuccessRoute;
      });
    } else {
      console.log("Expected a paymentId");   // No paymentId provided, 
      window.location = 'cart.html';         // go back to cart.html
    }
});