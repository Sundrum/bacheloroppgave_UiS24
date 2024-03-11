document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.cancel-button');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {

            var subscriptionId = button.getAttribute('data-subscription-id');
            console.log("Subscription ID:",subscriptionId);


        });
    });
});