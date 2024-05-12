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
                <th>Description</th>
                <th>Product Serial Number</th>
                <th>{{$subscription->subscription_status === 0 ? 'Last active' : ($subscription->subscription_status === 1 ? 'Active until' : ($subscription->subscription_status === 2 ? 'Next payment' : 'Next payment')) }}</th>
                <th>Price</th>
                <th>Payment method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$sensorUnit->product_name}}</td>
                <td>{{$sensorUnit->product_description}}</td>
                <td>{{$sensorUnit->serialnumber}}</td>
                <td>{{$subscription->next_payment}}</td>
                <td>{{$sensorUnit->subscription_price}},- nok</td>
                <td>{{$cardType}} {{$maskedPan}}</td>
                <td>{{$subscription->subscription_status === 0 ? 'Inactive' : ($subscription->subscription_status === 1 ? 'Canceled' : ($subscription->subscription_status === 2 ? 'Active' : 'Unknown')) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="center-container">
        @if ($subscription->subscription_status==0) {{-- Inactive --}}
        <Button class="checkout-button btn-7g"
            data-serialnumber="{{ $sensorUnit->serialnumber }}"
            data-subscription-order="{{ true }}"
            data-new-order="{{ false }}">
            Activate
        </Button>
        @elseif ($subscription->subscription_status==1) {{--  Cancelled --}}
        <form action="{{ route('reactivateSubscription') }}" method="POST">
            @csrf
            <input type="hidden" name="subscriptionId" value="{{$subscription->subscription_id}}">
            <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
            <button class="btn-7r" type="submit">
                Reactivate
            </button>
        </form>
        @elseif ($subscription->subscription_status==2) {{--  Active --}}
        <div id="container1">
            <button class="manage-button btn-7g"
            data-serialnumber="{{ $sensorUnit->serialnumber }}"
            data-subscription-id="{{ $subscription->subscription_id }}"
            data-new-order="{{ false }}"
            data-subscription-order="{{ true }}">
                Manage payment method
            </button>
        </div>
        <div id="container2">
            <form id="cancelSubscriptionForm" action="{{ route('cancelSubscription') }}" method="POST">
                @csrf
                <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
                <input type="hidden" name="subscriptionId" value="{{ $subscription->subscription_id }}" />
                <button class="btn-7r" type="button" onclick="confirmCancellation()">
                    Cancel subscription
                </button>
            </form>
        </div>
        @endif
    </div>
</section>
@endsection

<style>
    #container2 {
        margin-top: 1em;
    }
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
    var manageRoute = "{{ route('managebilling') }}";
    var cancelSubscriptionRoute = "{{ route('cancelSubscription') }}";
    console.log("sensorUnit:", {!! json_encode($sensorUnit) !!});

    function confirmCancellation() {
        if (confirm("Are you sure you want to cancel the subscription?")) {
            document.getElementById("cancelSubscriptionForm").submit();
        }
    }

</script>
<script src="{{asset('js/shop.js')}}"></script>
<script src="{{asset('js/subscriptiondetails.js')}}"></script>