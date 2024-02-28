@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Shop Products</h4>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->product_description }}</td>
                    <td>NOK {{ $product->product_price }} + NOK {{ $product->subscription_price }} yearly fee</td>
                    <td><img src="{{ $product->product_image_url }}" alt="{{ $product->product_name }}" style="width: 100px; height: 80px;"></td>
                    <td>
                        <button class="checkout-button" 
                        data-product-id="{{ $product->product_id }}"
                        data-product-name="{{ $product->product_name }}"
                        data-product-price="{{ $product->product_price }}"
                        data-subscription-price="{{ $product->subscription_price }}"> 
                        Buy
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

<script type="text/javascript">
    var updateUserDataRoute = "{{ route('updateUserData') }}";
    var checkoutRoute = "{{ route('checkout') }}";
    document.addEventListener('DOMContentLoaded', function () {
        var buttons = document.querySelectorAll('.checkout-button');
        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var productId = button.getAttribute('data-product-id');
                var productName = button.getAttribute('data-product-name');
                var productPrice = parseFloat(button.getAttribute('data-product-price'));
                var subscriptionPrice = parseFloat(button.getAttribute('data-subscription-price'));
                var VAT = 0.25;

                var unitPrice = productPrice / (1 + VAT);
                var grossTotalAmount = productPrice;
                var netTotalAmount = productPrice / (1 + VAT);

                var items = [
                    {
                        reference: productName,
                        name: productName,
                        quantity: 1,
                        unit: "pcs",
                        unitPrice: unitPrice*100,
                        grossTotalAmount: grossTotalAmount*100,
                        netTotalAmount: netTotalAmount*100
                    },
                    {
                        reference: productName + " subscription",
                        name: productName + " Subscription",
                        quantity: 1,
                        unit: "year",
                        unitPrice: (subscriptionPrice / (1 + VAT))*100,
                        grossTotalAmount: (subscriptionPrice)*100,
                        netTotalAmount: (subscriptionPrice / (1 + VAT))*100
                    }
                ];
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

                    window.location = checkoutRoute + "?paymentId=" + data.paymentId; //from subscriptions.blade.php
                }
                request.onerror = function () { console.error('connection error'); }
                request.send();


            });
        });
    });
</script>