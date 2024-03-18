document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.cancel-button');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {

            var subscriptionId = button.getAttribute('data-subscription-id');
            console.log("Subscription ID:",subscriptionId);


        });
    });
});

    document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.manage-button');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {

            var subOrder = button.getAttribute('data-subscription-order');
            var newOrder = button.getAttribute('data-new-order');
            var serialnumber= button.getAttribute('data-serialnumber');
            var subscriptionId = button.getAttribute('data-subscription-id');
            var items =[];

            items.push({subOrder:subOrder});
            items.push({newOrder:newOrder});
            items.push({serialnumber:serialnumber});

            var request = new XMLHttpRequest();
            var itemsString = encodeURIComponent(JSON.stringify(items));
            var url = '/api/create-payment?items=' + itemsString + '&subscriptionId=' + subscriptionId;
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
                var redirectUrl = manageRoute + "?subscriptionId=" + subscriptionId + '&paymentId=' + data.paymentId;
                // Redirect to the constructed URL
                window.location = redirectUrl;    
            }
            request.onerror = function () { console.error('connection error'); }
            request.send();


        });
    });
});
