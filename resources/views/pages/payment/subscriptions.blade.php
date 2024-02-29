@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Subscriptions for {{ $user->user_name }}</h4>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subscription Name</th>
                                <th>Payment</th>
                                <th>Subscription</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sensorUnits as $sensorUnit)
                            <tr>
                                <td>{{ $sensorUnit->product_name }}, {{ $sensorUnit->serialnumber }}</td>
                                {{-- @if ($sensorUnit->paid_subscription)
                                <td>Paid</td>
                                @else
                                <td>Not Paid</td>
                                @endif --}}
                                @if ($sensorUnit->paymentData)
                                <td>Data</td>
                                @else
                                <td>No Data</td>
                                @endif
                                @if ($sensorUnit->subscriptionData)
                                <td>Data</td>
                                @else
                                <td>No Data</td>
                                @endif
                                <td>
                                    <form action="{{ route('subscriptiondetails') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$sensorUnit->sensorunit_id}}" />
                                        <button type="submit" style="border: none; background-color: transparent;">
                                            <i class="fa fa-2x fa-bars"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                <h4>Payment</h4>
                <button id="checkout-button" class="btn btn-primary">Proceed to Checkout</button>
                <button id="retrievePayment" class="btn btn-primary">retrievePayment</button>

                {{-- Blade Routes --}}
                <script>
                    var checkoutRoute = "{{ route('checkout') }}";
                    var updateUserDataRoute = "{{ route('updateUserData') }}";
                    var retrievePaymentRoute = "{{ route('retrievePayment') }}";
                </script>                
                <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>

                </div>
            </div>
        </div>
    </div>
    <script>
        console.log(@json(compact('sensorUnits', 'user')));
    </script>
</section>
@endsection