document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.checkout-button');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {

            var productId = button.getAttribute('data-product-id');
            var subOrder = button.getAttribute('data-subscription-order');
            var newOrder = button.getAttribute('data-new-order');
            var serialnumber= button.getAttribute('data-serialnumber');
            var items =[];

            items.push({subOrder:subOrder});
            items.push({newOrder:newOrder});

            if (productId) {
                items.push({
                    productId: productId
                });
            }
            // Check if subscriptionPrice exists and add subscription-related data
            if (serialnumber) {
                items.push({
                    serialnumber: serialnumber,
                });
            }

            console.log(items);

            var request = new XMLHttpRequest();
            var itemsString = encodeURIComponent(JSON.stringify(items));
            var url = '/api/create-payment?items=' + itemsString;  
            request.open('GET', url , true);
            request.onload = function () {
                const data = JSON.parse(this.response);        // If parse error, check output 
                if (!data.paymentId) {                         // from create-payment.php, Handles insufficient user data
                    console.error('Error: Check output from create-payment.php');
                    console.log(this.response)
                    window.location = updateUserDataRoute; //from subscriptions.blade.php
                    return;
                }
                // Construct the URL with both paymentId and productId
                var redirectUrl = checkoutRoute + "?paymentId=" + data.paymentId;
                if (productId && productId!== null){
                    redirectUrl += "&productId=" + productId;
                }
                if (serialnumber && serialnumber !== null){
                    redirectUrl += "&serialNumber=" + serialnumber;
                }
                // Redirect to the constructed URL
                window.location = redirectUrl;    
            }
            request.onerror = function () { console.error('connection error'); }
            request.send();


        });
    });
});