
// var button = document.getElementById('checkout-button');
// if (button){
//     button.addEventListener('click', function () {
//         var request = new XMLHttpRequest();

//         // create-payment.php is implemented in Step 2
//         request.open('GET', '/api/create-payment', true); 
//         request.onload = function () {
//             const data = JSON.parse(this.response);        // If parse error, check output 
//             if (!data.paymentId) {                         // from create-payment.php, Handles insufficient user data
//             console.error('Error: Check output from create-payment.php');
//             console.log(this.response)
//             window.location = updateUserDataRoute; //from subscriptions.blade.php
//             return;
//             }
//             console.log(this.response);

//             window.location = checkoutRoute + "?paymentId=" + data.paymentId; //from subscriptions.blade.php
//         }
//         request.onerror = function () { console.error('connection error'); }
//         request.send();
//     });
// }  else {
//     console.log('No element found with id "checkout-button"');
// }

function checkoutButtonClickHandler() {
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
    console.log('No element found with id "checkout-button"');
}

var button = document.getElementById('retrievePayment');
if (button){
    button.addEventListener('click', function () {
        window.location = retrievePaymentRoute
    });
} else {
    console.log('No element found with id "checkout-button"');
}
