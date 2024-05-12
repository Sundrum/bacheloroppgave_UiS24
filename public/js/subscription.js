


function checkoutButtonClickHandler() {
    var request = new XMLHttpRequest();
    
    var items = [
        {   
            reference : "irrigation-sensor",
            name : "Irrigation Sensor",
            quantity : 1,
            unit : "pcs",
            unitPrice : 1500000,
            grossTotalAmount : 1500000,
            netTotalAmount : 1500000 
        },
        {   
            reference : "irrigation-sensor-subscription",
            name : "Irrigation Sensor Subscription",
            quantity : 1,
            unit : "year",
            unitPrice : 150000,
            grossTotalAmount : 150000,
            netTotalAmount : 150000 
        },
        {
            reference : "portal-subscription",
            name : "Portal Subscription",
            quantity : 1,
            unit : "year",
            unitPrice : 89000,
            grossTotalAmount : 89000,
            netTotalAmount : 89000
        }
        //{ product_price: 1500000, subscription_price: 150000 }
    ]; // Create the array of items
    
    var itemsString = encodeURIComponent(JSON.stringify(items)); // Serialize the array into a JSON string
    var url = '/api/create-payment?items=' + itemsString;  // Construct the URL with the items as query parameters   
    
    request.open('GET', url , true);
    request.onload = function () {
        const data = JSON.parse(this.response);        // If parse error, check output 
        if (!data.paymentId) {                         // from create-payment.php, Handles insufficient user data
            console.error('Error: Check output from create-payment.php');
            console.log(this.response)
            window.location = updateUserDataRoute; //from subscriptions.blade.php
            return;
        }
        console.log(this.response);

        window.location = checkoutRoute + "?paymentId=" + data.paymentId; //from subscriptions.blade.php
    }
    request.onerror = function () { console.error('connection error'); }
    request.send();
}

var button = document.getElementById('checkout-button');
if (button){
    button.addEventListener('click', checkoutButtonClickHandler);
} else {
    console.log('No element found with id "checkout-button"');
}

var button = document.getElementById('Submit');
if (button){
    button.addEventListener('click', function () {
        checkoutButtonClickHandler
    });
} else {
}

var button = document.getElementById('retrievePayment');
if (button){
    button.addEventListener('click', function () {
        window.location = retrievePaymentRoute
    });
} else {
}
