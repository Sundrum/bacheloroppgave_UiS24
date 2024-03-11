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
                <td>{{$subscription->subscription_status === 0 ? 'Inactive' : ($subscription->subscription_status === 1 ? 'Canceled' : ($subscription->subscription_status === 2 ? 'Active' : 'Unknown')) }}</td>
                <td>{{$sensorUnit->subscription_price}},- nok</td>
            </tr>
        </tbody>
    </table>
    <div class="center-container">
        @if ($subscription->subscription_status==0) {{-- Inactive --}}
        <Button class="checkout-button btn-7g"
            data-product-id="{{ $sensorUnit->product_id }}"
            data-product-name="{{ $sensorUnit->product_name }}"
            data-subscription-price="{{ $sensorUnit->subscription_price }}"
            data-serialnumber="{{ $sensorUnit->serialnumber}}">
            Activate
        </Button>
        @elseif ($subscription->subscription_status==1) {{--  Canceled --}}
        <form action="{{ route('reactivateSubscription') }}" method="POST">
            @csrf
            <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
            <input type="hidden" name="subscriptionId" value="{{ $subscription->subscription_id }}" />
            <button class="btn-7r" type="submit">
                Reactivate
            </button>
        </form>
        @elseif ($subscription->subscription_status==2) {{--  Active --}}
        <form action="{{ route('manageSubscription') }}" method="POST">
            @csrf
            <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
            <input type="hidden" name="subscriptionId" value="{{ $subscription->subscription_id }}" />
            <button class="btn-7g" type="submit">
                Manage payment method
            </button>
        </form>
        <form action="{{ route('cancelSubscription') }}" method="POST">
            @csrf
            <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
            <input type="hidden" name="subscriptionId" value="{{ $subscription->subscription_id }}" />
            <button class="btn-7r" type="submit">
                Cancel subscription
            </button>
        </form>
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
    var cancelSubscriptionRoute = "{{ route('cancelSubscription') }}";
    console.log("sensorUnit:", {!! json_encode($sensorUnit) !!});

</script>
<script src="{{asset('js/shop.js')}}"></script>
<script src="{{asset('js/subscriptiondetails.js')}}"></script>