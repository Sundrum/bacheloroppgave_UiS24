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
  {
    const urlParams = new URLSearchParams(window.location.search);
    const paymentId = urlParams.get('paymentId');
    console.log(paymentId + ' ' + checkoutKey);
    if (paymentId) {
      const checkoutOptions = {
        checkoutKey: checkoutKey,
        paymentId: paymentId,
        containerId: "checkout-container-div",
        language: GetLang(),
      };
      const checkout = new Dibs.Checkout(checkoutOptions);
      var theme = {
        "backgroundColor": "#white",
        "panelColor": "#white",
        "buttonbackgroundColor":"#00265a",
        "primaryColor":"#00265a",
        "linkColor":"#00265a",
        "primaryOutlineColor":"#00265a",
        "outlineColor":"#00265a",
      };
      checkout.setTheme(theme);
      checkout.on('payment-completed', function (response) {
        if (!managebool){
          window.location = "https://student.portal.7sense.no/checkoutsuccess?payment_id=" + paymentId;
        }else{
          window.location = "https://student.portal.7sense.no/subscriptions?subscription_id=" + subscriptionId;
        }
      });
    } else {
      console.log("Expected a paymentId");   // No paymentId provided, 
      window.location = 'cart.html';         // go back to cart.html
    }
  }
});