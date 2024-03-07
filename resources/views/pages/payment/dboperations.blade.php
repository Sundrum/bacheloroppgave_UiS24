@extends('layouts.app')
@section('content')

<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>New sensorunit</h4>
        </div>
    </div>
    <form method="POST" action=" {{ route('dboperationsnewunit') }}">
        @csrf
        <input type="text" name="payment_id" placeholder="An existing payment ID" size="30">
        <input type="text" name="serialnumber" placeholder="A new serialnumber" >
        <input type="text" name="product_id_ref" placeholder="The corresponding product ID" size="30">
        <input type="text" name="customer_id_ref" placeholder="The corresponding customer ID" size="30"> <br> <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <br>
</section>

<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Edit and delete database tables</h4>
        </div>
    </div>
    <h4>Payments table</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Created At</th>
                <th>Payment Status</th>
                <th>Customer ID</th>
                <th>Save</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <form method="POST" action="{{ route('dboperationsupdated') }}">
                @csrf
                <tr>
                    <td>
                        {{$payment->payment_id}}
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}" />
                    </td>
                    <td>{{$payment->created_at}}</td>
                    <td><input type="text" name="payment_status" value="{{$payment->payment_status}}"></td>
                    <td><input type="text" name="customer_id_ref" value="{{$payment->customer_id_ref}}"></td>
                    <td><button class="btn-7g" type="submit"><i class="fa fa-lg fa-check"></i></button></td>
                </form>
                <td>
                    <form action="{{ route('dboperationsdeleted') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}" />
                        <button type="submit" class="btn-7r">
                            <i class="fa fa-lg fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h4>Subscriptions table</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subscription ID</th>
                <th>Customer ID</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Interval</th>
                <th>Serial Number</th>
                <th>Status</th>
                <th>Save</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
            <form method="POST" action="{{ route('dboperationsupdated') }}">
                @csrf
                <tr>
                    <td>
                        {{$subscription->subscription_id}}
                        <input type="hidden" name="subscription_id" value="{{$subscription->subscription_id}}" />
                    </td>
                    <td><input type="text" name="customer_id_ref" value="{{$subscription->customer_id_ref}}" size="7"></td>
                    <td>{{$subscription->created_at}}</td>
                    <td>{{$subscription->updated_at}}</td>
                    <td><input type="text" name="interval" value="{{$subscription->interval}}" size="15"></td>
                    <td><input type="text" name="serialnumber" value="{{$subscription->serialnumber}}"></td>
                    <td><input type="text" name="subscription_status" value="{{$subscription->subscription_status}}" size="7"></td>
                    <td><button class="btn-7g" type="submit"><i class="fa fa-lg fa-check"></i></button></td>
            </form>
                    <td>
                        <form action="{{ route('dboperationsdeleted') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{$subscription->subscription_id}}" />
                            <button type="submit" class="btn-7r">
                                <i class="fa fa-lg fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
