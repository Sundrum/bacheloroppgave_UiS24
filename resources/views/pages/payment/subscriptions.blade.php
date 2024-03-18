@extends('layouts.app')

@section('content')
{{-- ALLOCATED SUBSCRIPTION SENSORS --}}
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
        @if($serialnumber !== null)
            <div class="alert alert-success" role="alert">
                Payment details for sensorunit {{ $serialnumber }} have been successfully updated.
            </div>
        @endif
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
                                $status = $sensorUnit->subscription_status === 0 ? 'Inactive' : ($sensorUnit->subscription_status === 1 ? 'Canceled' : ($sensorUnit->subscription_status === 2 ? 'Active' : 'Unknown'));
                                @endphp
                                <tr>
                                    {{-- SENSORS  --}} 
                                    <td>{{ $sensorUnit->serialnumber ? $sensorUnit->product_name . ', ' . $sensorUnit->serialnumber : $sensorUnit->product_name . '' }} <br> ID={{$sensorUnit->subscription_id}}</td>
                                    <td>{{ $sensorUnit->serialnumber ? $status : 'Ordered' }}</td>                                    
                                    <td>
                                        @if ($sensorUnit->serialnumber)
                                            <form action="{{ route('subscriptiondetails') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="sensorunitId" value="{{$sensorUnit->serialnumber}}" />
                                                <input type="hidden" name="subscriptionId" value="{{ $sensorUnit->subscription_id }}" />
                                                <button class="{{ $status === 'Active' ? 'btn-7g' : 'btn-7r' }}" type="submit">
                                                    MANAGE
                                                </button>
                                            </form>
                                        @endif
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