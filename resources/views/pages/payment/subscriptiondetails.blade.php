@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Details for {{$sensorUnit->product_name}}</h4>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Serial Number</th>
                <th>Description</th>
                <th>Installation Date</th>
                <th>Status</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$sensorUnit->product_name}}</td>
                <td>{{$sensorUnit->serialnumber}}</td>
                <td>{{$sensorUnit->product_description}}</td>
                <td>{{$sensorUnit->sensorunit_installdate}}</td>
                <td>{{ $isActive === 'true' ? 'Active' : ($isActive === 'false' ? 'Inactive' : 'Unknown') }}</td>
                <td>{{$sensorUnit->subscription_price}},- nok</td>
            </tr>
        </tbody>
    </table>
    <div class="center-container">
        @if ($isActive=="true")
        <a class="btn-7g">Manage payment method</a>
        <Button class="cancel-button btn-7r"
            data-product-id="{{ $sensorUnit->product_id }}"
            data-product-name="{{ $sensorUnit->product_name }}"
            data-subscription-price="{{ $sensorUnit->subscription_price }}"
            data-serialnumber="{{ $sensorUnit->serialnumber}}">
            Cancel subscription
        </Button>
        @elseif ($isActive=="false")
        <Button class="checkout-button btn-7g"
            data-product-id="{{ $sensorUnit->product_id }}"
            data-product-name="{{ $sensorUnit->product_name }}"
            data-subscription-price="{{ $sensorUnit->subscription_price }}"
            data-serialnumber="{{ $sensorUnit->serialnumber}}">
            Activate
        </Button>
        @endif
    </div>
</section>
@endsection

<style>
    .center-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #danger {
        color: #e74c3c; /* Button background color */}
    .neat-button {
        display: inline-block;
        padding: 15px 30px;
        margin: 10px;
        background-color: #00265a; /* Button background color */
        color: #fff; /* Button text color */
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease; /* Smooth transition on hover */
    }

    .neat-button:hover {
        background-color: #3498db; /* Change background color on hover */
        color: #ffff
    }
</style>
<script>
    var checkoutRoute = "{{ route('checkout') }}";
    console.log("sensorUnit:", {!! json_encode($sensorUnit) !!});
</script>
<script src="{{asset('js/shop.js')}}"></script>
<script src="{{asset('js/subscriptiondetails.js')}}"></script>