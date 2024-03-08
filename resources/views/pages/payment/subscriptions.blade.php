@extends('layouts.app')

@section('content')
{{-- ALLOCATED SUBSCRIPTION SENSORS --}}
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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subscription Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($allocatedSensorUnitsSub as $sensorUnit) --}}
                            @foreach ($subscriptions as $sensorUnit)
                                @php
                                $isActive = $sensorUnit->paymentData && !$sensorUnit->subscriptionData ? 'false' : ($sensorUnit->subscriptionData ? 'true' : 'null');
                                @endphp
                                <tr>
                                    {{-- UNACTIVATED SENSORS  --}}
                                    @if ($isActive=='false') 
                                        <td>{{ $sensorUnit->product_name }}, {{ $sensorUnit->serialnumber }}</td>
                                        <td>Inactive</td>
                                    {{-- ACTIVE SENSORS  --}}
                                    @elseif ($isActive=='true') 
                                        <td>{{ $sensorUnit->product_name }}, {{ $sensorUnit->serialnumber }}</td>
                                        <td>Active</td>
                                    {{-- NO DATA SENSORS  --}}
                                    @elseif (!$sensorUnit->paymentData && !$sensorUnit->subscriptionData) 
                                        <td>{{ $sensorUnit->product_name }}, {{ $sensorUnit->serialnumber }}</td>
                                        <td></td>
                                    {{-- ELSE --}}
                                    @else 
                                        <td>{{ $sensorUnit->product_name }}, {{ $sensorUnit->serialnumber }}</td>
                                        <td></td>
                                    @endif
                                    <td>
                                        <form action="{{ route('subscriptiondetails') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$sensorUnit->serialnumber}}" />
                                            <input type="hidden" name="isActive" value="{{ $isActive }}" />
                                            <button class="{{ $isActive === 'true' ? 'btn-7g' : ($isActive === 'false' ? 'btn-7r' : 'btn-7g') }}" type="submit">
                                                MANAGE
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>           
                <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>
                </div>
            </div>
        </div>
    </div>
    <script>
        console.log(@json(compact('subscriptions', 'user')));
    </script>


    {{-- SUBSCRIPTION ORDERS --}}
    {{-- <section class="bg-white card-rounded">
        <div class="row mt-3 text-center">
        </div>
        <div class="row text-center mt-5">
            <div class="col-12">
                <h4>Orders for {{ $user->user_name }}</h4>
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
                                    <th>Amount</th>
                                    <th>Payment ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unallocatedSensorUnitsSub as $sensorUnit)
                                <tr>
                                    <td>{{ $sensorUnit->product_name }}</td>
                                    <td>{{ $sensorUnit->Amount }}</td>
                                    <td>{{ $sensorUnit->payment_id }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>             
                    <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>

                    </div>
                </div>
            </div>
        </div>
        <script>
            console.log(@json(compact('allocatedSensorUnitsSub','unallocatedSensorUnitsSub', 'user')));
        </script>
    </section> --}}

</section>
@endsection