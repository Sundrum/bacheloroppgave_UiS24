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
                        <button class="checkout-button btn-7g" 
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

<script>
    var updateUserDataRoute = "{{ route('updateUserData') }}";
    var checkoutRoute = "{{ route('checkout') }}";
</script>
<script src="{{asset('js/shop.js')}}"></script>