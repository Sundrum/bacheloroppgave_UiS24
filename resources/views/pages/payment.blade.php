@extends('layouts.app')

@section('content')

<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Creating payment for {{ $customer->customer_name ?? ' '}}</h4>
        </div>
    </div>
    <div class="row pt-2">
        <div class="col-10 offset-1">
            <h5>The payment will be generated and a paymentlink will be generated.</h5>
        </div>
    </div>
    <div class="row mt-2 pb-3">
        <div class="col-12">
            <form class="activate-subscription" action="{{route('payment.activate')}}" method="POST">
                @csrf
                <div class="row py-2">
                    <div class="col-10 offset-1">
                        <label class="text-left" for="description">Payment description</label>
                        <input class="form-control" type="text" name="description" maxlength="1500" placeholder="Information about the payment" required>
                    </div>
                    <div class="col-5 offset-1">
                        <label class="text-left" for="description">Amout</label>
                        <input class="form-control col-6" type="number" name="amount" placeholder="E.g: 1000" required>
                    </div>
                    <div class="col-5">
                        <label class="text-left" for="description">Currency</label>
                        <select class="form-control col-6"name="currencyCode">
                            <option value="NOK" selected>NOK</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                        </select>
                    </div>
                    <div class="col-5 offset-1">
                        <label class="text-left" for="language">Terminal Language</label>
                        <select class="form-control col-6"name="language">
                            <option value="no_NO" selected>Norwegian</option>
                            <option value="en_GB">English</option>
                            <option value="fr_FR">French</option>
                        </select>
                    </div>
                    <input type="hidden" name="customerId" value="{{ $customer->customer_id ?? '' }}">
                    <input type="hidden" name="id_ref" value="{{Auth::user()->user_id}}">
                </div>

                <div class="row text-center">
                    <div class="col-12">
                        <button type="submit" class="btn-7s text-center">
                            Click to pay
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
$(".activate-subscription").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var actionUrl = form.attr('action');
    $('.activate-subscription :button[type="submit"]').prop('disabled', true);
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize()
    })
    .always(function(data) {
        console.log(data);
        if (data.status == "success" && data.result.paymentLink != "") {
            window.location = data.result.paymentLink;
            //successMessage(data.result.paymentLink)
        } else if (data.status == "already_paid") {
            alert("Subscription is already paid.");
            window.location.reload();
        } else {
            alert("Something went wrong! please try again after sometime.");
            console.log(data);
        }
        setTimeout(function() {
            $('.activate-subscription :button[type="submit"]').prop('disabled', false)
        }, 2000);
    });
});
</script>

@endsection