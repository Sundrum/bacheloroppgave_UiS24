@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
    <div class="col-12">
        <h4> @lang('dashboard.paymentcompleted') </h4>
    </div>

    <button id="receiptbutton" style="border: none; background-color: transparent;">
    <i class="fa fa-2x fa-receipt"></i>
    </button>
    <br>
    <div id="receipt" style="display: none;">
        <h4>Receipt</h4>
        <p>Thank you for your purchase</p>
        <p>Order number: 123456</p>
        <p>Amount: $100</p>
        <p>Payment method: Credit card</p>
    </div>
    <br>
    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}" class="btn btn-primary">
    Back to subscriptions page
    </a>


</section>
<script>
    let text = @json(__('dashboard.paymentcompleted'));
    console.log(text);
    document.getElementById('receiptbutton').addEventListener('click', function() {
        var receipt = document.getElementById('receipt');
        receipt.style.display = ""
    })
</script>
@endsection