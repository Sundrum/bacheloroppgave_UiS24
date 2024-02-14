
var button = document.getElementById('checkout-button');
button.addEventListener('click', function () {
    var request = new XMLHttpRequest();

    // create-payment.php is implemented in Step 2
    request.open('GET', '/api/create-payment', true); 
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
});

