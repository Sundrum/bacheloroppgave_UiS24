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
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sensorUnits as $sensorUnit)
                            <tr>
                                <td>{{ $sensorUnit->product_name }}</td>
                                @if ($sensorUnit->paid_subscription)
                                <td>Paid</td>
                                @else
                                <td>Not Paid</td>
                                @endif
                                <td>
                                    {{-- <form action="{{ route('subscriptiondetails') }}" method="POST">
                                        @csrf --}}
                                        {{-- <input type="hidden" name="id" value="{{$sensorUnit->sensorunit_id}}" /> --}}
                                        <a onclick="loadContent('{{ route('subscriptiondetails', ['sensorunit_id' => $sensorUnit->sensorunit_id]) }}')" 
                                            href="{{ route('subscriptiondetails', ['sensorunit_id' => $sensorUnit->sensorunit_id]) }}">
                                        {{-- <button type="submit"> --}}
                                            <i class="fa fa-2x fa-bars"></i>
                                        {{-- </button> --}}
                                        </a>
                                    {{-- </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                <h4>Payment</h4>
                <button id="checkout-button" class="btn btn-primary">Proceed to Checkout</button>

                {{-- Blade Routes --}}
                <script>
                    var checkoutRoute = "{{ route('checkout') }}";
                    var updateUserDataRoute = "{{ route('updateUserData') }}";
                </script>                
                <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection