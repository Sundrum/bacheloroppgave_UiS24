document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.cancel-button');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {

            var productId = button.getAttribute('data-product-id');
            var productName = button.getAttribute('data-product-name');
            var productPrice = parseFloat(button.getAttribute('data-product-price'));
            var subscriptionPrice = parseFloat(button.getAttribute('data-subscription-price'));
            var serialnumber= button.getAttribute('data-serialnumber');
            var VAT = 0.25;
            var unitPrice = productPrice / (1 + VAT);
            var grossTotalAmount = productPrice;
            var netTotalAmount = productPrice / (1 + VAT);

            var items =[];

            if(subscriptionPrice){items.push({subOrder:true});}
            else{items.push({subOrder:false});}
            if(productPrice){items.push({newOrder:true});}
            else{items.push({newOrder:false});}

            // Check if productPrice exists and add product-related data
            if (productPrice) {
                items.push({
                    reference: productName,
                    name: productName,
                    quantity: 1,
                    unit: "pcs",
                    unitPrice: (productPrice / (1 + VAT))*100,
                    taxRate: VAT * 10000,
                    taxAmount: ((productPrice / (1 + VAT)) * 100) * VAT,
                    grossTotalAmount: (productPrice)*100,
                    netTotalAmount: (productPrice / (1 + VAT))*100,
                });
            }
            // Check if subscriptionPrice exists and add subscription-related data
            if (subscriptionPrice) {
                items.push({
                    reference: productName + " subscription",
                    name: productName + " Subscription",
                    quantity: 1,
                    unit: "year",
                    unitPrice: (subscriptionPrice / (1 + VAT)) * 100,
                    taxRate: VAT * 10000,
                    taxAmount: ((subscriptionPrice / (1 + VAT)) * 100) * VAT,
                    grossTotalAmount: subscriptionPrice * 100,
                    netTotalAmount: (subscriptionPrice / (1 + VAT)) * 100,
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
                console.log(this.response);

                // Construct the URL with both paymentId and productId
                if (serialnumber){
                    var redirectUrl = checkoutRoute + "?paymentId=" + data.paymentId + "&productId=" + productId + "&serialNumber=" + serialnumber;
                }
                else{
                    var redirectUrl = checkoutRoute + "?paymentId=" + data.paymentId + "&productId=" + productId;
                }

                // Redirect to the constructed URL
                window.location = redirectUrl;    
            }
            request.onerror = function () { console.error('connection error'); }
            request.send();


        });
    });
});